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

        $this->baseUrl = $this->environment === 'production'
            ? 'https://api.fedapay.com'
            : 'https://sandbox-api.fedapay.com';

        Log::info('FedaPay initialized', [
            'environment' => $this->environment,
            'base_url' => $this->baseUrl,
            'has_secret' => !empty($this->secretKey),
        ]);
    }

    /**
     * Initiate payment with FedaPay
     */
    public function initiatePayment($transaction)
    {
        try {
            // ✅ Amount in smallest unit (XOF has no decimals, so just cast to int)
            $amount = (int) round($transaction->amount);

            // ✅ Format phone: remove +, ensure Benin format
            $phone = $this->formatPhoneNumber($transaction->phone_number);

            Log::info('FedaPay payment request', [
                'transaction_id' => $transaction->transaction_id,
                'amount' => $amount,
                'phone' => $phone,
                'method' => $transaction->payment_method,
            ]);

            $payload = [
                // ✅ REQUIRED: description field
                'description' => 'Abonnement CuniApp - ' . $transaction->transaction_id,
                'amount' => $amount,
                'currency' => ['iso' => 'XOF'],
                'reference' => $transaction->transaction_id,
                'callback_url' => route('payment.callback', ['provider' => 'fedapay'], true),
                'return_url' => route('subscription.status', [], true),

                // ✅ Customer object - simplified structure
                'customer' => [
                    'email' => $transaction->user->email,
                    'firstname' => explode(' ', $transaction->user->name)[0] ?? $transaction->user->name,
                    'lastname' => implode(' ', array_slice(explode(' ', $transaction->user->name), 1)) ?? '',
                    'phone_number' => [
                        'number' => preg_replace('/^\+?229/', '', $phone), // Send without +229 prefix
                        'country' => 'bj',
                    ],
                ],

                // ✅ Payment method mapping
                'payment_method' => $this->getFedaPayMethod($transaction->payment_method),
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->baseUrl . '/v1/transactions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                $checkoutUrl = $data['transaction']['url']
                    ?? $data['url']
                    ?? $data['checkout_url']
                    ?? null;

                if ($checkoutUrl) {
                    return [
                        'success' => true,
                        'checkout_url' => $checkoutUrl,
                        'fedapay_transaction_id' => $data['transaction']['id'] ?? $data['id'] ?? null,
                        'raw_response' => $data,
                    ];
                }
            }

            Log::error('FedaPay payment initiation failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'request_payload' => $payload,
                'headers_sent' => ['Authorization' => 'Bearer ***', 'Content-Type' => 'application/json'],
            ]);

            return [
                'success' => false,
                'error' => 'FedaPay API Error: ' . ($response->json('error') ?? 'HTTP ' . $response->status()),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay service exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'transaction_id' => $transaction->transaction_id ?? 'N/A',
            ]);

            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify transaction status via API
     */
    public function verifyTransaction($fedapayId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept' => 'application/json',
            ])->get($this->baseUrl . '/v1/transactions/' . $fedapayId);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => 'Not found (HTTP ' . $response->status() . ')'];
        } catch (\Exception $e) {
            Log::error('FedaPay verification failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature, $secret)
    {
        // FedaPay signature format: t=TIMESTAMP,s=SIGNATURE
        parse_str(parse_url('http://x?' . $signature, PHP_URL_QUERY), $parts);

        if (!isset($parts['t']) || !isset($parts['s'])) {
            return false;
        }

        $timestamp = $parts['t'];
        $expectedSignature = $parts['s'];

        // Reject if timestamp is too old (>5 minutes)
        if (abs(time() - $timestamp) > 300) {
            return false;
        }

        // Compute expected signature: HMAC-SHA256 of "timestamp.payload"
        $signedPayload = $timestamp . '.' . $payload;
        $computedSignature = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($computedSignature, $expectedSignature);
    }

    /**
     * Map our payment method to FedaPay's method code
     */
    private function getFedaPayMethod($method)
    {
        return match ($method) {
            'momo' => 'mtn_bj',
            'moov' => 'moov_bj',
            'celtis' => 'celtis_bj',
            default => 'mtn_bj',
        };
    }

    /**
     * Format phone number for Benin (+229XXXXXXXXX)
     */
    private function formatPhoneNumber($phone)
    {
        if (!$phone) return null;

        // Remove all non-digit except +
        $clean = preg_replace('/[^\d+]/', '', $phone);

        // Already international
        if (str_starts_with($clean, '+229')) {
            return $clean;
        }

        // Has 229 prefix but no +
        if (str_starts_with($clean, '229') && strlen($clean) === 11) {
            return '+' . $clean;
        }

        // Local Benin format: 01XXXXXXXX (10 digits)
        if (preg_match('/^01\d{8}$/', $clean)) {
            return '+229' . substr($clean, 1);
        }

        // Fallback: extract last 8 digits and prefix
        $digits = preg_replace('/\D/', '', $clean);
        if (strlen($digits) >= 8) {
            return '+229' . substr($digits, -8);
        }

        return $clean; // Return as-is if unsure
    }
}
