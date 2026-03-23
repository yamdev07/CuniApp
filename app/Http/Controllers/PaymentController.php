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
        // Validate request first
        $request->validate([
            'transaction_id' => 'required|exists:payment_transactions,transaction_id',
            'phone_number' => 'nullable|string|min:8',
            'payment_method' => 'required|in:momo,moov,celtis,manual',
        ]);

        // ✅ STEP 1: Fetch transaction with pessimistic lock to prevent race conditions
        $transaction = PaymentTransaction::where('transaction_id', $request->transaction_id)
            ->where('user_id', Auth::id())
            ->lockForUpdate()  // ← Critical: Locks the row until transaction completes
            ->firstOrFail();

        // ✅ STEP 2: Check status INSIDE the locked context - prevents double-processing
        if (!in_array($transaction->status, ['pending', 'failed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction déjà traitée. Statut actuel: ' . ucfirst($transaction->status),
                'current_status' => $transaction->status,
            ], 409); // 409 = Conflict
        }

        // ✅ STEP 3: If previously failed, allow retry but log it
        if ($transaction->status === 'failed') {
            Log::info('Payment retry initiated', [
                'transaction_id' => $transaction->transaction_id,
                'user_id' => $transaction->user_id,
                'previous_failure' => $transaction->failure_reason,
            ]);
        }

        DB::beginTransaction();
        try {
            // ✅ STEP 4: Re-check status AFTER beginning transaction (defense in depth)
            $transaction->refresh(); // Refresh to get latest DB state
            if (!in_array($transaction->status, ['pending', 'failed'])) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction modifiée entre-temps. Veuillez rafraîchir.',
                ], 409);
            }

            // ✅ STEP 5: Update transaction metadata
            $transaction->update([
                'phone_number' => ltrim($request->phone_number, '+'), // ✅ Remove + prefix
                'provider' => 'fedapay',
                'payment_method' => $request->payment_method,
            ]);

            // ✅ STEP 6: Call FedaPay API
            $fedaPayService = new FedaPayService();
            $paymentResult = $fedaPayService->initiatePayment($transaction);

            if ($paymentResult['success']) {
                DB::commit();

                // ✅ Send initiated notification (only if not already sent)
                if (!$transaction->user->notifications()->where('type', 'info')
                    ->where('title', 'LIKE', '%Paiement en Cours%')
                    ->where('created_at', '>', now()->subMinutes(5))
                    ->exists()) {
                    $transaction->user->notify(new PaymentInitiatedNotification($transaction));
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Redirection vers FedaPay...',
                    'checkout_url' => $paymentResult['checkout_url'],
                    'redirect' => route('subscription.status'),
                    'transaction_id' => $transaction->transaction_id,
                ]);
            } else {
                // ✅ Handle FedaPay API failure
                $transaction->update([
                    'status' => 'failed',
                    'failure_reason' => $paymentResult['error'],
                    'provider_response' => $paymentResult['response'] ?? null,
                ]);
                DB::commit();

                $transaction->user->notify(new PaymentFailedNotification($transaction));

                return response()->json([
                    'success' => false,
                    'message' => 'Échec du paiement: ' . $paymentResult['error'],
                    'error_code' => 'FEDAPAY_API_ERROR',
                ], 400);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // ✅ Handle database lock timeout or constraint violations
            DB::rollBack();
            Log::error('Payment processing DB error: ' . $e->getMessage(), [
                'transaction_id' => $request->transaction_id,
                'error_code' => $e->getCode(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de base de données. Veuillez réessayer.',
                'error_code' => 'DB_ERROR',
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'transaction_id' => $request->transaction_id,
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ]);

            // Update transaction status to failed for audit
            $transaction->update([
                'status' => 'failed',
                'failure_reason' => 'Erreur système: ' . $e->getMessage(),
            ]);

            $transaction->user->notify(new PaymentFailedNotification($transaction));

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement du paiement',
                'error_code' => 'SYSTEM_ERROR',
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
