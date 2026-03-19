<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Services\FedaPayService;
use App\Services\InvoiceService;
use App\Notifications\PaymentInitiatedNotification;
use App\Notifications\PaymentSuccessfulNotification;
use App\Notifications\PaymentFailedNotification;
use App\Notifications\SubscriptionActivatedNotification;
use App\Notifications\InvoiceEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * ✅ INITIATE PAYMENT WITH FEDAPAY
     */

    public function initiate(Request $request, $transaction_id)
    {
        $transaction = PaymentTransaction::where('transaction_id', $transaction_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // ✅ ALLOW RETRY FOR FAILED TRANSACTIONS
        if ($transaction->status === 'failed') {
            // Reset transaction to pending for retry
            $transaction->update([
                'status' => 'pending',
                'failure_reason' => null,
                'provider_response' => null,
            ]);
        }

        if ($transaction->status !== 'pending') {
            return redirect()->route('subscription.status')
                ->with('warning', 'Cette transaction est déjà traitée.');
        }

        // ✅ Send notification when payment page is viewed
        if ($transaction->user->customNotifications()
            ->where('type', 'info')
            ->where('title', 'LIKE', '%Paiement en Cours%')
            ->where('created_at', '>', now()->subMinutes(5))
            ->exists()
        ) {
            $transaction->user->notify(new PaymentInitiatedNotification($transaction));
        }

        $subscription = $transaction->subscription;
        return view('payment.initiate', compact('transaction', 'subscription'));
    }

    /**
     * ✅ PROCESS PAYMENT WITH FEDAPAY
     */
    public function process(Request $request)
    {
        $transaction = PaymentTransaction::where('transaction_id', $request->transaction_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if already processed
        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaction déjà traitée. Statut: ' . $transaction->status
            ], 400);
        }
        $request->validate([
            'transaction_id' => 'required|exists:payment_transactions,transaction_id',
            'phone_number' => 'required|string|min:8',
            'payment_method' => 'required|in:momo,moov,celtis',
        ]);

        $transaction = PaymentTransaction::where('transaction_id', $request->transaction_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaction déjà traitée'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Update transaction
            $transaction->update([
                'phone_number' => $request->phone_number,
                'provider' => 'fedapay',
                'payment_method' => $request->payment_method,
            ]);

            // ✅ Call FedaPay API
            $fedaPayService = new FedaPayService();
            $paymentResult = $fedaPayService->initiatePayment($transaction);

            if ($paymentResult['success']) {
                DB::commit();

                // ✅ Send initiated notification
                $transaction->user->notify(new PaymentInitiatedNotification($transaction));

                return response()->json([
                    'success' => true,
                    'message' => 'Redirection vers FedaPay...',
                    'checkout_url' => $paymentResult['checkout_url'],
                    'redirect' => route('subscription.status')
                ]);
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'failure_reason' => $paymentResult['error'],
                ]);
                DB::commit();

                // ✅ Send failed notification
                $transaction->user->notify(new PaymentFailedNotification($transaction));

                return response()->json([
                    'success' => false,
                    'message' => 'Échec du paiement: ' . $paymentResult['error']
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());

            // ✅ Send error notification
            $transaction->update([
                'status' => 'failed',
                'failure_reason' => 'Erreur système: ' . $e->getMessage(),
            ]);
            $transaction->user->notify(new PaymentFailedNotification($transaction));

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du paiement'
            ], 500);
        }
    }

    /**
     * ✅ FEDAPAY CALLBACK (PRIMARY PAYMENT CONFIRMATION)
     */
    public function callback(Request $request)
    {
        Log::channel('payments')->info('FedaPay Callback received', [
            'query' => $request->query(),
            'ip' => $request->ip(),
        ]);

        $transactionId = $request->get('reference')
            ?? $request->get('transaction_id')
            ?? $request->get('id');

        if (!$transactionId) {
            Log::channel('payments')->error('FedaPay Callback: Missing transaction reference');
            return redirect()->route('subscription.status')
                ->with('error', 'Référence de transaction manquante');
        }

        $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();
        if (!$transaction) {
            Log::channel('payments')->error('FedaPay Callback: Transaction not found');
            return redirect()->route('subscription.status')
                ->with('error', 'Transaction non trouvée');
        }

        // ✅ SECURITY: Verify transaction belongs to authenticated user
        if ($transaction->user_id !== Auth::id()) {
            Log::channel('payments')->warning('FedaPay Callback: User mismatch');
            return redirect()->route('subscription.status')
                ->with('error', 'Non autorisé');
        }

        // ✅ Verify payment status with FedaPay API
        $status = $request->get('status') ?? $request->get('transaction_status');
        $verified = $this->verifyPaymentWithFedaPay($transactionId);

        if ($verified['status'] === 'completed' || $status === 'completed' || $status === 'SUCCESS' || $status === 'approved') {
            DB::beginTransaction();
            try {
                // Update transaction
                $transaction->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'provider_response' => $request->all(),
                ]);

                // Activate subscription
                if ($transaction->subscription) {
                    $this->activateSubscription($transaction->subscription);
                }

                // ✅ CREATE INVOICE
                if (class_exists(\App\Services\InvoiceService::class)) {
                    try {
                        $invoiceService = new \App\Services\InvoiceService();
                        $invoice = $invoiceService->createFromTransaction($transaction);

                        if ($invoice && $transaction->user) {
                            // ✅ Send invoice notification
                            $transaction->user->notify(new InvoiceEmailNotification($invoice));
                        }
                    } catch (\Exception $e) {
                        Log::channel('payments')->error('Invoice creation failed: ' . $e->getMessage());
                    }
                }

                // ✅ Send success notification
                $transaction->user->notify(new PaymentSuccessfulNotification($transaction));

                DB::commit();
                Log::channel('payments')->info('FedaPay Callback: Payment completed');

                return redirect()->route('subscription.status')
                    ->with('success', 'Paiement réussi ! Facture générée et envoyée par email.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::channel('payments')->error('Callback processing failed: ' . $e->getMessage());
                return redirect()->route('subscription.status')
                    ->with('error', 'Erreur lors de la confirmation du paiement');
            }
        }

        // Payment failed or pending
        Log::channel('payments')->warning('FedaPay Callback: Payment not completed');

        // ✅ Send failed notification
        $transaction->update([
            'status' => 'failed',
            'failure_reason' => 'Paiement non confirmé par FedaPay',
        ]);
        $transaction->user->notify(new PaymentFailedNotification($transaction));

        return redirect()->route('subscription.status')
            ->with('error', 'Paiement échoué ou en attente');
    }

    /**
     * ✅ VERIFY PAYMENT STATUS WITH FEDAPAY API
     */
    private function verifyPaymentWithFedaPay($transactionId)
    {
        try {
            $fedaPayService = new FedaPayService();
            $result = $fedaPayService->verifyTransaction($transactionId);
            if ($result['success']) {
                return [
                    'status' => $result['data']['status'] ?? 'unknown',
                    'verified' => true
                ];
            }
            return ['status' => 'unknown', 'verified' => false];
        } catch (\Exception $e) {
            Log::channel('payments')->error('FedaPay verification failed: ' . $e->getMessage());
            return ['status' => 'unknown', 'verified' => false];
        }
    }

    /**
     * ✅ ACTIVATE SUBSCRIPTION
     */
    private function activateSubscription($subscription)
    {
        if (!$subscription) {
            return;
        }

        $subscription->update(['status' => 'active']);
        $subscription->user->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => $subscription->end_date,
        ]);

        // ✅ Send activation notification
        $subscription->user->notify(new SubscriptionActivatedNotification($subscription));
    }

    /**
     * ✅ VERIFY PAYMENT STATUS (for manual checks)
     */
    public function verify($transaction_id)
    {
        $transaction = PaymentTransaction::where('transaction_id', $transaction_id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'transaction' => [
                'id' => $transaction->id,
                'transaction_id' => $transaction->transaction_id,
                'status' => $transaction->status,
                'amount' => $transaction->amount,
                'payment_method' => $transaction->payment_method,
                'paid_at' => $transaction->paid_at,
            ]
        ]);
    }
}
