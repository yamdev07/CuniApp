<?php
// app/Services/FedaPayService.php
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
        // ✅ Priority: .env > config > settings
        $this->publicKey = $_ENV['FEDAPAY_PUBLIC_KEY']
            ?? config('services.fedapay.public_key')
            ?? Setting::get('fedapay_public_key');

        $this->secretKey = $_ENV['FEDAPAY_SECRET_KEY']
            ?? config('services.fedapay.secret_key')
            ?? Setting::get('fedapay_secret_key');

        $this->environment = $_ENV['FEDAPAY_ENVIRONMENT']
            ?? config('services.fedapay.environment')
            ?? Setting::get('fedapay_environment', 'sandbox');

        // ✅ Log configuration for debugging
        Log::info('FedaPay config loaded', [
            'public_key_set' => !empty($this->publicKey),
            'secret_key_set' => !empty($this->secretKey),
            'environment' => $this->environment,
        ]);

        $this->baseUrl = $this->environment === 'production'
            ? 'https://api.fedapay.com'
            : 'https://sandbox.fedapay.com';

        Log::info('FedaPay base URL: ' . $this->baseUrl);
    }

    /**
     * ✅ INITIATE PAYMENT WITH FEDAPAY
     */
    public function initiatePayment($transaction)
    {
        try {
            Log::info('Initiating FedaPay payment', [
                'transaction_id' => $transaction->transaction_id,
                'amount' => $transaction->amount,
                'phone' => $transaction->phone_number,
                'method' => $transaction->payment_method,
            ]);

            // ✅ CORRECT ENDPOINT: /transactions (NOT /v1/transactions)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/transactions', [ // ← Removed /v1/
                'amount' => (int) $transaction->amount * 100, // ← FedaPay expects amount in cents!
                'currency' => 'XOF',
                'description' => 'Abonnement CuniApp Élevage',
                'reference' => $transaction->transaction_id,
                'callback_url' => route('payment.callback', ['provider' => 'fedapay']),
                'return_url' => route('subscription.status'),
                'customer' => [
                    'email' => $transaction->user->email,
                    'name' => $transaction->user->name,
                    'phone_number' => $this->formatPhoneNumber($transaction->phone_number),
                ],
                'settings' => [
                    'methods' => [$this->getFedaPayMethod($transaction->payment_method)],
                ],
            ]);

            Log::info('FedaPay API response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'checkout_url' => $data['transaction']['url'] ?? $data['url'] ?? null,
                    'transaction_id' => $data['transaction']['id'] ?? $data['id'] ?? null,
                    'response' => $data,
                ];
            }

            Log::error('FedaPay payment failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json('message') ?? 'Erreur FedaPay: ' . $response->status(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay payment initiation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'error' => 'Erreur de connexion à FedaPay: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * ✅ VERIFY TRANSACTION STATUS
     */
    public function verifyTransaction($fedapayTransactionId)
    {
        try {
            // ✅ Also remove /v1/ here
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transactions/' . $fedapayTransactionId);

            if ($response->successful()) {
                $jsonData = $response->json();
                return [
                    'success' => true,
                    'data' => $jsonData,
                ];
            }

            return [
                'success' => false,
                'error' => 'Transaction not found (HTTP ' . $response->status() . ')',
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay verification failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * ✅ GET FEDAPAY METHOD CODE
     */
    private function getFedaPayMethod($paymentMethod)
    {
        return match ($paymentMethod) {
            'momo' => 'mtn_bj',
            'moov' => 'moov_bj',
            'celtis' => 'celtis_bj',
            default => 'mtn_bj',
        };
    }

    // ✅ ADD THIS HELPER: Format phone number for FedaPay
    private function formatPhoneNumber($phone)
    {
        // Remove spaces, dashes, and ensure +229 prefix
        $cleaned = preg_replace('/[\s\-]/', '', $phone);

        if (strpos($cleaned, '+229') === 0) {
            return $cleaned;
        }
        if (strpos($cleaned, '229') === 0) {
            return '+' . $cleaned;
        }
        if (strpos($cleaned, '01') === 0) {
            return '+229' . substr($cleaned, 1);
        }

        return '+229' . $cleaned;
    }
}
