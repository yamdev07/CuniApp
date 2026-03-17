<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedaPayService
{
    protected $publicKey;
    protected $secretKey;
    protected $environment;
    protected $baseUrl;

    public function __construct()
    {
        // ✅ PRIORITY: Environment variables first
        $this->publicKey = Config::get('services.fedapay.public_key') 
            ?? $_ENV['FEDAPAY_PUBLIC_KEY'] 
            ?? Setting::get('fedapay_public_key');
            
        $this->secretKey = Config::get('services.fedapay.secret_key') 
            ?? $_ENV['FEDAPAY_SECRET_KEY'] 
            ?? Setting::get('fedapay_secret_key');
            
        $this->environment = Config::get('services.fedapay.environment') 
            ?? $_ENV['FEDAPAY_ENVIRONMENT'] 
            ?? Setting::get('fedapay_environment', 'sandbox');
            
        $this->baseUrl = $this->environment === 'production' 
            ? 'https://api.fedapay.com' 
            : 'https://sandbox.fedapay.com';
    }

    // ✅ SECURE: Never log secrets
    public static function verifyWebhookSignature($payload, $signature)
    {
        $secretKey = Config::get('services.fedapay.webhook_secret')
            ?? $_ENV['FEDAPAY_WEBHOOK_SECRET']
            ?? Setting::get('fedapay_webhook_secret');

        if (!$secretKey) {
            // ✅ Log error WITHOUT exposing secret
            Log::channel('webhooks')->error('FedaPay: Webhook secret not configured', [
                'timestamp' => now()->toIso8601String(),
            ]);
            return false;
        }

        if (empty($payload)) {
            Log::channel('webhooks')->error('FedaPay: Empty webhook payload');
            return false;
        }

        if (empty($signature)) {
            Log::channel('webhooks')->error('FedaPay: Missing webhook signature');
            return false;
        }

        // FedaPay uses HMAC-SHA256
        $expectedSignature = hash_hmac('sha256', $payload, $secretKey);

        // Timing-safe comparison
        $isValid = hash_equals($expectedSignature, $signature);

        if (!$isValid) {
            // ✅ Log verification failure WITHOUT exposing secrets
            Log::channel('webhooks')->warning('FedaPay: Signature verification failed', [
                'expected_prefix' => substr($expectedSignature, 0, 8) . '...',
                'received_prefix' => substr($signature, 0, 8) . '...',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        return $isValid;
    }

    /**
     * ✅ INITIATE PAYMENT
     */
    public function initiatePayment($transaction)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/v1/transactions', [
                'amount' => (int) $transaction->amount,
                'currency' => 'XOF',
                'description' => 'Abonnement CuniApp Élevage',
                'reference' => $transaction->transaction_id,
                'callback_url' => route('payment.callback', ['provider' => 'fedapay']),
                'return_url' => route('subscription.status'),
                'customer' => [
                    'email' => $transaction->user->email,
                    'name' => $transaction->user->name,
                    'phone_number' => $transaction->phone_number,
                ],
                'settings' => [
                    'methods' => [$this->getFedaPayMethod($transaction->payment_method)],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'checkout_url' => $data['transaction']['url'] ?? null,
                    'transaction_id' => $data['transaction']['id'] ?? null,
                    'response' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message') ?? 'Erreur FedaPay',
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay payment initiation failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Erreur de connexion à FedaPay',
                'response' => null,
            ];
        }
    }

    // UPDATE verifyWebhookSignature()
    public static function verifyWebhookSignature($payload, $signature)
    {
        $secretKey = Setting::get('fedapay_webhook_secret');

        if (!$secretKey) {
            Log::channel('webhooks')->error('FedaPay: Webhook secret not configured in Settings');
            return false;
        }

        if (empty($payload)) {
            Log::channel('webhooks')->error('FedaPay: Empty webhook payload');
            return false;
        }

        if (empty($signature)) {
            Log::channel('webhooks')->error('FedaPay: Missing webhook signature');
            return false;
        }

        // FedaPay uses HMAC-SHA256
        $expectedSignature = hash_hmac('sha256', $payload, $secretKey);

        // Timing-safe comparison
        $isValid = hash_equals($expectedSignature, $signature);

        if (!$isValid) {
            Log::channel('webhooks')->warning('FedaPay: Signature verification failed', [
                'expected' => substr($expectedSignature, 0, 10) . '...',
                'received' => substr($signature, 0, 10) . '...',
            ]);
        }

        return $isValid;
    }

    /**
     * ✅ GET FEDAPAY METHOD CODE
     */
    private function getFedaPayMethod($paymentMethod)
    {
        return match ($paymentMethod) {
            'momo' => 'mtn_ml', // or 'mtn_bj' for Benin
            'moov' => 'moov_bj',
            'celtis' => 'celtis_bj',
            default => 'mtn_bj',
        };
    }

    /**
     * ✅ VERIFY TRANSACTION STATUS (optional - for manual checks)
     */
    public function verifyTransaction($fedapayTransactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/v1/transactions/' . $fedapayTransactionId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => 'Transaction not found',
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay transaction verification failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Erreur de vérification',
            ];
        }
    }
}
