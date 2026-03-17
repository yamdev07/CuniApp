<?php
// app/Services/FedaPayService.php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class FedaPayService
{
    protected $publicKey;
    protected $secretKey;
    protected $environment;
    protected $baseUrl;

    public function __construct()
    {
        // ✅ PRIORITY 1: Environment variables (most secure)
        // ✅ PRIORITY 2: Config (cached)
        // ✅ PRIORITY 3: Settings table (fallback only)
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

    /**
     * ✅ INITIATE PAYMENT WITH FEDAPAY
     * Creates a transaction and returns checkout URL
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
                // ✅ IMPORTANT: Callback URL for redirect after payment
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

    /**
     * ✅ VERIFY TRANSACTION STATUS WITH FEDAPAY API
     * Used for callback security - verify payment directly with FedaPay
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

    /**
     * ✅ VERIFY TRANSACTION BY REFERENCE (our transaction_id)
     * Alternative verification method using our reference
     */
    public function verifyTransactionByReference($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/v1/transactions', [
                'reference' => $reference,
            ]);

            if ($response->successful()) {
                $transactions = $response->json('transactions', []);
                if (!empty($transactions)) {
                    return [
                        'success' => true,
                        'data' => $transactions[0],
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Transaction not found',
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay transaction verification by reference failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Erreur de vérification',
            ];
        }
    }

    /**
     * ✅ GET FEDAPAY METHOD CODE
     * Maps our payment method to FedaPay's method codes
     */
    private function getFedaPayMethod($paymentMethod)
    {
        return match ($paymentMethod) {
            'momo' => 'mtn_bj',      // MTN Mobile Money Benin
            'moov' => 'moov_bj',     // Moov Money Benin
            'celtis' => 'celtis_bj', // Celtis Cash Benin
            default => 'mtn_bj',
        };
    }
}
