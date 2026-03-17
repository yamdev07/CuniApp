<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Services\FedaPayService;
use App\Notifications\PaymentSuccessfulNotification;
use App\Notifications\PaymentFailedNotification;
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

        if ($transaction->status !== 'pending') {
            return redirect()->route('subscription.status')
                ->with('warning', 'Cette transaction est déjà traitée.');
        }

        $subscription = $transaction->subscription;
        return view('payment.initiate', compact('transaction', 'subscription'));
    }

    /**
     * ✅ PROCESS PAYMENT WITH FEDAPAY
     */
    public function process(Request $request)
    {
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
                // ✅ Redirect user to FedaPay checkout
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

                $transaction->user->notify(new PaymentFailedNotification($transaction));

                return response()->json([
                    'success' => false,
                    'message' => 'Échec du paiement: ' . $paymentResult['error']
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du paiement'
            ], 500);
        }
    }

    /**
     * ✅ FEDAPAY CALLBACK (PRIMARY PAYMENT CONFIRMATION)
     * This replaces the webhook - user is redirected here after payment
     */
    public function callback(Request $request)
    {
        Log::channel('payments')->info('FedaPay Callback received', [
            'query' => $request->query(),
            'ip' => $request->ip(),
        ]);

        // FedaPay sends 'reference' as primary identifier
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
            Log::channel('payments')->error('FedaPay Callback: Transaction not found', [
                'transaction_id' => $transactionId
            ]);
            return redirect()->route('subscription.status')
                ->with('error', 'Transaction non trouvée');
        }

        // ✅ SECURITY: Verify transaction belongs to authenticated user
        if ($transaction->user_id !== Auth::id()) {
            Log::channel('payments')->warning('FedaPay Callback: User mismatch', [
                'transaction_id' => $transactionId,
                'expected_user' => $transaction->user_id,
                'actual_user' => Auth::id()
            ]);
            return redirect()->route('subscription.status')
                ->with('error', 'Non autorisé');
        }

        // ✅ Verify payment status with FedaPay API (defense-in-depth)
        $status = $request->get('status') ?? $request->get('transaction_status');

        // For additional security, verify with FedaPay API
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
                            $transaction->user->notify(new \App\Notifications\InvoiceEmailNotification($invoice));
                        }
                    } catch (\Exception $e) {
                        Log::channel('payments')->error('Invoice creation failed: ' . $e->getMessage(), [
                            'transaction_id' => $transactionId,
                        ]);
                        // Don't fail the payment if invoice creation fails
                    }
                }

                // Send notification
                $transaction->user->notify(new PaymentSuccessfulNotification($transaction));

                DB::commit();

                Log::channel('payments')->info('FedaPay Callback: Payment completed', [
                    'transaction_id' => $transactionId
                ]);

                return redirect()->route('subscription.status')
                    ->with('success', 'Paiement réussi ! Facture générée.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::channel('payments')->error('Callback processing failed: ' . $e->getMessage());
                return redirect()->route('subscription.status')
                    ->with('error', 'Erreur lors de la confirmation du paiement');
            }
        }

        // Payment failed or pending
        Log::channel('payments')->warning('FedaPay Callback: Payment not completed', [
            'transaction_id' => $transactionId,
            'status' => $status
        ]);

        return redirect()->route('subscription.status')
            ->with('error', 'Paiement échoué ou en attente');
    }

    /**
     * ✅ VERIFY PAYMENT STATUS WITH FEDAPAY API
     * Additional security layer - verify directly with FedaPay
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

        $subscription->user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));
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
