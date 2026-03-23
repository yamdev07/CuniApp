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
        $this->publicKey = env('FEDAPAY_PUBLIC_KEY')
            ?? config('services.fedapay.public_key')
            ?? Setting::get('fedapay_public_key');

        $this->secretKey = env('FEDAPAY_SECRET_KEY')
            ?? config('services.fedapay.secret_key')
            ?? Setting::get('fedapay_secret_key');

        $this->environment = env('FEDAPAY_ENVIRONMENT', 'sandbox')
            ?? config('services.fedapay.environment')
            ?? Setting::get('fedapay_environment', 'sandbox');

        $this->baseUrl = env('FEDAPAY_BASE_URL')
            ?? ($this->environment === 'production'
                ? 'https://api.fedapay.com'
                : 'https://sandbox-api.fedapay.com');

        Log::info('FedaPay config loaded', [
            'public_key_set' => !empty($this->publicKey),
            'secret_key_set' => !empty($this->secretKey),
            'environment' => $this->environment,
            'base_url' => $this->baseUrl,
        ]);
    }

    /**
     * ✅ INITIATE PAYMENT WITH FEDAPAY - FIXED
     */
    public function initiatePayment($transaction)
    {
        try {
            $amount = (int) round(floatval($transaction->amount));

            // ✅ FIX: Remove + from phone number for FedaPay
            $phone = ltrim($this->formatPhoneNumber($transaction->phone_number), '+');

            Log::info('Initiating FedaPay payment', [
                'transaction_id' => $transaction->transaction_id,
                'amount_fcfa' => $amount,
                'phone' => $phone,
                'method' => $transaction->payment_method,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/v1/transactions', [
                // ✅ FIX: Add description field (REQUIRED)
                'description' => 'Abonnement CuniApp Élevage - ' . $transaction->transaction_id,
                'amount' => $amount,
                'currency' => ['iso' => 'XOF'],
                'reference' => $transaction->transaction_id,
                'callback_url' => route('payment.callback', ['provider' => 'fedapay'], true),
                'return_url' => route('subscription.status', [], true),
                // ✅ FIX: Customer structure
                'customer' => [
                    'email' => $transaction->user->email,
                    'firstname' => $transaction->user->name,
                    // ✅ FIX: Phone without + prefix
                    'phone_number' => $phone,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $checkoutUrl = $data['transaction']['url'] ?? $data['url'] ?? $data['checkout_url'] ?? null;

                if ($checkoutUrl) {
                    return [
                        'success' => true,
                        'checkout_url' => $checkoutUrl,
                        'transaction_id' => $data['transaction']['id'] ?? $data['id'] ?? null,
                        'response' => $data,
                    ];
                }
            }

            // ✅ FIX: Better error logging
            Log::error('FedaPay payment failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'request_body' => [
                    'amount' => $amount,
                    'currency' => ['iso' => 'XOF'],
                    'reference' => $transaction->transaction_id,
                    'phone' => $phone,
                ],
            ]);

            return [
                'success' => false,
                'error' => 'Erreur FedaPay: ' . ($response->json('error') ?? 'HTTP ' . $response->status()),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay payment initiation failed: ' . $e->getMessage());
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/v1/transactions/' . $fedapayTransactionId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => 'Transaction non trouvée (HTTP ' . $response->status() . ')',
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

    /**
     * ✅ FORMAT PHONE NUMBER FOR BENIN
     */
    private function formatPhoneNumber($phone)
    {
        if (!$phone) return null;

        // Remove all non-digit characters except +
        $cleaned = preg_replace('/[^\d+]/', '', $phone);

        // Already in international format
        if (strpos($cleaned, '+229') === 0) {
            return $cleaned;
        }

        // Starts with 229, add +
        if (strpos($cleaned, '229') === 0) {
            return '+' . $cleaned;
        }

        // Local Benin format: 01XXXXXXXX (10 digits)
        if (preg_match('/^01\d{8}$/', $cleaned)) {
            return '+229' . substr($cleaned, 1);
        }

        // Fallback: try to extract last 8 digits and prefix with +229
        $digits = preg_replace('/\D/', '', $cleaned);
        if (strlen($digits) >= 8) {
            return '+229' . substr($digits, -8);
        }

        // Last resort
        return '+229' . $digits;
    }
}
