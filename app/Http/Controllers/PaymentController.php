<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Setting;
use App\Services\WebhookSignatureVerifier;
use App\Notifications\PaymentSuccessfulNotification;
use App\Notifications\PaymentFailedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Initiate payment process
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
        $provider = $transaction->payment_method;

        return view('payment.initiate', compact('transaction', 'subscription', 'provider'));
    }

    /**
     * Process payment with selected provider
     */
    public function process(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:payment_transactions,transaction_id',
            'payment_method' => 'required|in:momo,celtis,moov',
            'phone_number' => 'required|string|min:8',
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
            // Update transaction with phone number
            $transaction->update([
                'phone_number' => $request->phone_number,
                'provider' => $request->payment_method,
            ]);

            // Process payment based on provider
            $paymentResult = $this->processPaymentByProvider(
                $request->payment_method,
                $transaction
            );

            if ($paymentResult['success']) {
                $transaction->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'provider_response' => $paymentResult['response'],
                ]);

                // Activate subscription
                $this->activateSubscription($transaction->subscription);

                DB::commit();

                // Send notification
                $transaction->user->notify(new PaymentSuccessfulNotification($transaction));

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement réussi ! Votre abonnement est activé.',
                    'redirect' => route('subscription.status')
                ]);
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'failure_reason' => $paymentResult['error'],
                    'provider_response' => $paymentResult['response'],
                ]);

                DB::commit();

                // Send notification
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
     * Process payment by provider (MTN, Celtis, Moov)
     */
    private function processPaymentByProvider($provider, $transaction)
    {
        // Get API credentials from settings
        $apiKey = Setting::get("{$provider}_api_key");
        $apiSecret = Setting::get("{$provider}_api_secret");
        $environment = Setting::get("{$provider}_environment", 'sandbox');

        if (!$apiKey || !$apiSecret) {
            return [
                'success' => false,
                'error' => 'Configuration du paiement non disponible',
                'response' => null
            ];
        }

        // Process payment based on provider
        switch ($provider) {
            case 'momo':
                return $this->processMTNMoMo($transaction, $apiKey, $apiSecret, $environment);
            case 'celtis':
                return $this->processCeltisCash($transaction, $apiKey, $apiSecret, $environment);
            case 'moov':
                return $this->processMoovPay($transaction, $apiKey, $apiSecret, $environment);
            default:
                return [
                    'success' => false,
                    'error' => 'Méthode de paiement non supportée',
                    'response' => null
                ];
        }
    }

    /**
     * MTN MoMo Payment Processing
     */
    private function processMTNMoMo($transaction, $apiKey, $apiSecret, $environment)
    {
        // TODO: Implement actual MTN MoMo API integration
        // This is a mock implementation for demonstration
        $baseUrl = $environment === 'sandbox' 
            ? 'https://sandbox.momodeveloper.mtn.com' 
            : 'https://ericssonbasicapi2.azure-api.net';

        // Mock successful payment
        return [
            'success' => true,
            'error' => null,
            'response' => [
                'transaction_id' => $transaction->transaction_id,
                'status' => 'SUCCESSFUL',
                'amount' => $transaction->amount,
                'currency' => 'XOF',
            ]
        ];
    }

    /**
     * Celtis Cash Payment Processing
     */
    private function processCeltisCash($transaction, $apiKey, $apiSecret, $environment)
    {
        // TODO: Implement actual Celtis Cash API integration
        return [
            'success' => true,
            'error' => null,
            'response' => [
                'transaction_id' => $transaction->transaction_id,
                'status' => 'SUCCESSFUL',
                'amount' => $transaction->amount,
            ]
        ];
    }

    /**
     * Moov Pay Payment Processing
     */
    private function processMoovPay($transaction, $apiKey, $apiSecret, $environment)
    {
        // TODO: Implement actual Moov Pay API integration
        return [
            'success' => true,
            'error' => null,
            'response' => [
                'transaction_id' => $transaction->transaction_id,
                'status' => 'SUCCESSFUL',
                'amount' => $transaction->amount,
            ]
        ];
    }

    /**
     * Activate subscription after successful payment
     */
    private function activateSubscription($subscription)
    {
        if (!$subscription) {
            return;
        }

        // Update user subscription status
        $subscription->user->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => $subscription->end_date,
        ]);

        // Send notification
        $subscription->user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));
    }

    /**
     * ✅ HANDLE PAYMENT PROVIDER CALLBACK (SECURE)
     */
    public function callback(Request $request, $provider)
    {
        Log::info("Payment callback received from {$provider}", $request->all());

        // Verify callback signature (implement based on provider)
        // Update transaction status based on callback data

        return response()->json(['status' => 'received']);
    }

    /**
     * ✅ HANDLE PAYMENT WEBHOOKS (SECURE - WITH SIGNATURE VERIFICATION)
     */
    public function webhook(Request $request, $provider)
    {
        // ✅ STEP 1: Log webhook receipt immediately (for debugging)
        $webhookId = uniqid('wh_');
        Log::channel('webhooks')->info("Webhook received", [
            'webhook_id' => $webhookId,
            'provider' => $provider,
            'ip' => $request->ip(),
            'timestamp' => now()->toIso8601String(),
        ]);

        // ✅ STEP 2: Get raw payload for signature verification
        $payload = $request->getContent();
        $headers = $request->headers->all();

        // ✅ STEP 3: Extract signature from headers (provider-specific)
        $signature = WebhookSignatureVerifier::extractSignature($provider, $headers);

        if (!$signature) {
            Log::channel('webhooks')->warning('Webhook: Missing signature', [
                'webhook_id' => $webhookId,
                'provider' => $provider,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Missing signature',
            ], 400);
        }

        // ✅ STEP 4: Verify webhook signature
        $isValid = WebhookSignatureVerifier::verify($provider, $payload, $signature, $headers);

        if (!$isValid) {
            Log::channel('webhooks')->error('Webhook: Signature verification failed', [
                'webhook_id' => $webhookId,
                'provider' => $provider,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 401);
        }

        Log::channel('webhooks')->info('Webhook: Signature verified', [
            'webhook_id' => $webhookId,
            'provider' => $provider,
        ]);

        // ✅ STEP 5: Process webhook based on provider
        DB::beginTransaction();
        try {
            $webhookData = json_decode($payload, true);

            // Process based on provider
            $result = $this->processWebhookByProvider($provider, $webhookData, $headers);

            DB::commit();

            // ✅ STEP 6: Log successful processing
            Log::channel('webhooks')->info('Webhook processed successfully', [
                'webhook_id' => $webhookId,
                'provider' => $provider,
                'result' => $result,
            ]);

            return response()->json([
                'status' => 'processed',
                'webhook_id' => $webhookId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ STEP 7: Log error
            Log::channel('webhooks')->error('Webhook processing failed', [
                'webhook_id' => $webhookId,
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Processing failed',
            ], 500);
        }
    }

    /**
     * ✅ PROCESS WEBHOOK BY PROVIDER
     */
    private function processWebhookByProvider($provider, $data, $headers)
    {
        switch ($provider) {
            case 'momo':
                return $this->processMTNMoMoWebhook($data, $headers);
            case 'celtis':
                return $this->processCeltisCashWebhook($data, $headers);
            case 'moov':
                return $this->processMoovPayWebhook($data, $headers);
            default:
                throw new \Exception("Unsupported provider: {$provider}");
        }
    }

    /**
     * ✅ PROCESS MTN MOMO WEBHOOK
     */
    private function processMTNMoMoWebhook($data, $headers)
    {
        // Extract transaction ID from webhook data
        $transactionId = $data['transaction_id'] ?? $data['reference_id'] ?? null;

        if (!$transactionId) {
            throw new \Exception('Missing transaction_id in webhook data');
        }

        // Find transaction
        $transaction = PaymentTransaction::where('transaction_id', $transactionId)->firstOrFail();

        // Update transaction status based on webhook data
        $status = $data['status'] ?? 'UNKNOWN';

        if ($status === 'SUCCESSFUL' || $status === 'COMPLETED') {
            // ✅ Payment successful
            $transaction->update([
                'status' => 'completed',
                'paid_at' => now(),
                'provider_response' => $data,
            ]);

            // Activate subscription
            if ($transaction->subscription) {
                $this->activateSubscription($transaction->subscription);
            }

            // Send notification
            $transaction->user->notify(new PaymentSuccessfulNotification($transaction));

            return ['action' => 'payment_completed', 'transaction_id' => $transactionId];

        } elseif ($status === 'FAILED' || $status === 'REJECTED') {
            // ✅ Payment failed
            $transaction->update([
                'status' => 'failed',
                'failure_reason' => $data['failure_reason'] ?? 'Payment failed',
                'provider_response' => $data,
            ]);

            // Send notification
            $transaction->user->notify(new PaymentFailedNotification($transaction));

            return ['action' => 'payment_failed', 'transaction_id' => $transactionId];
        }

        return ['action' => 'status_updated', 'transaction_id' => $transactionId, 'status' => $status];
    }

    /**
     * ✅ PROCESS CELTIS CASH WEBHOOK
     */
    private function processCeltisCashWebhook($data, $headers)
    {
        // Similar structure to MTN MoMo
        return $this->processMTNMoMoWebhook($data, $headers);
    }

    /**
     * ✅ PROCESS MOOV PAY WEBHOOK
     */
    private function processMoovPayWebhook($data, $headers)
    {
        // Similar structure to MTN MoMo
        return $this->processMTNMoMoWebhook($data, $headers);
    }

    /**
     * Verify payment status
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
     * Manual payment confirmation (Admin only)
     */
    public function manualConfirm(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:payment_transactions,transaction_id',
        ]);

        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403, 'Seuls les administrateurs peuvent confirmer manuellement les paiements.');
        }

        $transaction = PaymentTransaction::where('transaction_id', $request->transaction_id)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $transaction->update([
                'status' => 'completed',
                'paid_at' => now(),
                'payment_method' => 'manual',
            ]);

            // Activate subscription
            $this->activateSubscription($transaction->subscription);

            DB::commit();

            return redirect()->route('admin.subscriptions.index')
                ->with('success', 'Paiement confirmé manuellement avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}