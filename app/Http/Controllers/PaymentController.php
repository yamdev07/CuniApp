<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\Setting;
use App\Services\FedaPayService; // ← NEW
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
            'payment_method' => 'required|in:momo,moov,celtis', // ← User still chooses operator
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
                'provider' => 'fedapay', // ← Always FedaPay now
                'payment_method' => $request->payment_method, // ← User's choice (momo/moov/celtis)
            ]);

            // ✅ Call FedaPay API
            $fedaPayService = new FedaPayService();
            $paymentResult = $fedaPayService->initiatePayment($transaction);

            if ($paymentResult['success']) {
                // Return FedaPay checkout URL to frontend
                DB::commit();

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
     * ✅ FEDAPAY WEBHOOK HANDLER (REPLACES 3 SEPARATE HANDLERS)
     */
    public function webhook(Request $request)
    {
        $webhookId = uniqid('wh_fedapay_');

        Log::channel('webhooks')->info("FedaPay Webhook received", [
            'webhook_id' => $webhookId,
            'ip' => $request->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);

        // ✅ STEP 1: Get raw payload
        $payload = $request->getContent();
        $signature = $request->header('X-FedaPay-Signature');

        // ✅ STEP 2: Verify signature
        $isValid = FedaPayService::verifyWebhookSignature($payload, $signature);

        if (!$isValid) {
            Log::channel('webhooks')->error('FedaPay Webhook: Signature verification failed', [
                'webhook_id' => $webhookId,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 401);
        }

        Log::channel('webhooks')->info('FedaPay Webhook: Signature verified', [
            'webhook_id' => $webhookId,
        ]);

        // ✅ STEP 3: Process webhook
        DB::beginTransaction();
        try {
            $webhookData = json_decode($payload, true);
            $result = $this->processFedaPayWebhook($webhookData);

            DB::commit();

            Log::channel('webhooks')->info('FedaPay Webhook processed successfully', [
                'webhook_id' => $webhookId,
                'result' => $result,
            ]);

            return response()->json([
                'status' => 'processed',
                'webhook_id' => $webhookId,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::channel('webhooks')->error('FedaPay Webhook processing failed', [
                'webhook_id' => $webhookId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Processing failed',
            ], 500);
        }
    }

    /**
     * ✅ PROCESS FEDAPAY WEBHOOK DATA
     */
    private function processFedaPayWebhook($data)
    {
        // FedaPay webhook payload structure:
        // {
        //   "event": "payment.completed" | "payment.failed",
        //   "transaction": {
        //     "id": "fedapay_transaction_id",
        //     "reference": "our_transaction_id",
        //     "status": "completed" | "failed",
        //     "amount": 2500,
        //     "currency": "XOF"
        //   }
        // }

        $event = $data['event'] ?? '';
        $transactionData = $data['transaction'] ?? [];

        // Find our transaction by reference
        $ourTransactionId = $transactionData['reference'] ?? null;

        if (!$ourTransactionId) {
            throw new \Exception('Missing transaction reference in webhook');
        }

        $transaction = PaymentTransaction::where('transaction_id', $ourTransactionId)->firstOrFail();

        // Handle based on event type
        // Handle based on event type
        if (in_array($event, ['payment.completed', 'payment.success'])) {
            // ✅ Payment successful
            $transaction->update([
                'status' => 'completed',
                'paid_at' => now(),
                'provider_response' => $data,
            ]);

            // Activate subscription if exists
            if ($transaction->subscription) {
                $this->activateSubscription($transaction->subscription);
            }

            // ✅ CREATE INVOICE for completed payment
            if (class_exists(\App\Services\InvoiceService::class)) {
                try {
                    $invoiceService = new \App\Services\InvoiceService();
                    $invoice = $invoiceService->createFromTransaction($transaction);

                    // ✅ Send invoice email notification
                    if ($invoice && $transaction->user) {
                        $transaction->user->notify(new \App\Notifications\InvoiceEmailNotification($invoice));
                    }
                } catch (\Exception $e) {
                    Log::error('Invoice creation failed: ' . $e->getMessage(), [
                        'transaction_id' => $transaction->transaction_id,
                    ]);
                    // Don't fail the payment if invoice creation fails
                }
            }

            $transaction->user->notify(new PaymentSuccessfulNotification($transaction));
            return ['action' => 'payment_completed', 'transaction_id' => $ourTransactionId];
        } elseif (in_array($event, ['payment.failed', 'payment.declined'])) {
            // ✅ Payment failed
            $transaction->update([
                'status' => 'failed',
                'failure_reason' => $transactionData['failure_reason'] ?? 'Payment failed',
                'provider_response' => $data,
            ]);

            $transaction->user->notify(new PaymentFailedNotification($transaction));

            return ['action' => 'payment_failed', 'transaction_id' => $ourTransactionId];
        }

        return ['action' => 'status_updated', 'transaction_id' => $ourTransactionId];
    }

    /**
     * ✅ ACTIVATE SUBSCRIPTION (existing method - keep)
     */
    private function activateSubscription($subscription)
    {
        if (!$subscription) {
            return;
        }

        $subscription->user->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => $subscription->end_date,
        ]);

        $subscription->user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));
    }

    /**
     * ✅ VERIFY PAYMENT STATUS (keep for manual checks)
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

    /**
     * ✅ FEDAPAY CALLBACK (after payment completion)
     */
    // app/Http/Controllers/PaymentController.php - UPDATE callback() method
    // app/Http/Controllers/PaymentController.php - UPDATE callback() method
    public function callback(Request $request)
    {
        Log::channel('webhooks')->info('FedaPay Callback received', [
            'query' => $request->query(),
            'ip' => $request->ip(),
        ]);

        // FedaPay sends 'reference' as primary identifier
        $transactionId = $request->get('reference')
            ?? $request->get('transaction_id')
            ?? $request->get('id');

        if (!$transactionId) {
            Log::channel('webhooks')->error('FedaPay Callback: Missing transaction reference');
            return redirect()->route('subscription.status')
                ->with('error', 'Référence de transaction manquante');
        }

        $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            Log::channel('webhooks')->error('FedaPay Callback: Transaction not found', [
                'transaction_id' => $transactionId
            ]);
            return redirect()->route('subscription.status')
                ->with('error', 'Transaction non trouvée');
        }

        $status = $request->get('status') ?? $request->get('transaction_status');

        if (in_array($status, ['completed', 'SUCCESS', 'approved'])) {
            $transaction->update(['status' => 'completed', 'paid_at' => now()]);
            if ($transaction->subscription) {
                $this->activateSubscription($transaction->subscription);
            }

            // ✅ CREATE INVOICE for completed payment
            if (class_exists(\App\Services\InvoiceService::class)) {
                try {
                    $invoiceService = new \App\Services\InvoiceService();
                    $invoice = $invoiceService->createFromTransaction($transaction);

                    // ✅ Send invoice email notification
                    if ($invoice && $transaction->user) {
                        $transaction->user->notify(new \App\Notifications\InvoiceEmailNotification($invoice));
                    }
                } catch (\Exception $e) {
                    Log::channel('webhooks')->error('Invoice creation failed: ' . $e->getMessage(), [
                        'transaction_id' => $transactionId,
                    ]);
                    // Don't fail the payment if invoice creation fails
                }
            }

            $transaction->user->notify(new PaymentSuccessfulNotification($transaction));
            Log::channel('webhooks')->info('FedaPay Callback: Payment completed', [
                'transaction_id' => $transactionId
            ]);
            return redirect()->route('subscription.status')
                ->with('success', 'Paiement réussi ! Facture générée.');
        }

        Log::channel('webhooks')->warning('FedaPay Callback: Payment failed', [
            'transaction_id' => $transactionId,
            'status' => $status
        ]);

        return redirect()->route('subscription.status')
            ->with('error', 'Paiement échoué');
    }
}
