<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class WebhookSignatureVerifier
{
    /**
     * ✅ Verify MTN MoMo webhook signature
     * 
     * MTN uses HMAC-SHA256 with API secret
     * Signature is sent in X-Reference-Id or Authorization header
     */
    public static function verifyMTNMoMo(string $payload, string $signature, ?string $referenceId = null): bool
    {
        try {
            $apiSecret = Setting::get('momo_api_secret');
            
            if (!$apiSecret) {
                Log::error('MTN MoMo: API secret not configured');
                return false;
            }

            // MTN MoMo signature verification
            // Signature = HMAC-SHA256(payload, api_secret)
            $expectedSignature = hash_hmac('sha256', $payload, $apiSecret);
            
            // Use timing-safe comparison to prevent timing attacks
            $isValid = hash_equals($expectedSignature, $signature);

            if (!$isValid) {
                Log::warning('MTN MoMo: Signature verification failed', [
                    'expected' => substr($expectedSignature, 0, 10) . '...',
                    'received' => substr($signature, 0, 10) . '...',
                    'reference_id' => $referenceId,
                ]);
            }

            return $isValid;
        } catch (\Exception $e) {
            Log::error('MTN MoMo: Signature verification error', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * ✅ Verify Celtis Cash webhook signature
     * 
     * Celtis typically uses SHA256 hash of payload + secret
     */
    public static function verifyCeltisCash(string $payload, string $signature): bool
    {
        try {
            $apiSecret = Setting::get('celtis_api_secret');
            
            if (!$apiSecret) {
                Log::error('Celtis Cash: API secret not configured');
                return false;
            }

            // Celtis Cash signature verification
            $expectedSignature = hash('sha256', $payload . $apiSecret);
            
            $isValid = hash_equals($expectedSignature, $signature);

            if (!$isValid) {
                Log::warning('Celtis Cash: Signature verification failed', [
                    'expected' => substr($expectedSignature, 0, 10) . '...',
                    'received' => substr($signature, 0, 10) . '...',
                ]);
            }

            return $isValid;
        } catch (\Exception $e) {
            Log::error('Celtis Cash: Signature verification error', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * ✅ Verify Moov Pay webhook signature
     * 
     * Moov typically uses HMAC-SHA512
     */
    public static function verifyMoovPay(string $payload, string $signature): bool
    {
        try {
            $apiSecret = Setting::get('moov_api_secret');
            
            if (!$apiSecret) {
                Log::error('Moov Pay: API secret not configured');
                return false;
            }

            // Moov Pay signature verification (HMAC-SHA512)
            $expectedSignature = hash_hmac('sha512', $payload, $apiSecret);
            
            $isValid = hash_equals($expectedSignature, $signature);

            if (!$isValid) {
                Log::warning('Moov Pay: Signature verification failed', [
                    'expected' => substr($expectedSignature, 0, 10) . '...',
                    'received' => substr($signature, 0, 10) . '...',
                ]);
            }

            return $isValid;
        } catch (\Exception $e) {
            Log::error('Moov Pay: Signature verification error', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * ✅ Generic verification method that routes to provider-specific verifier
     */
    public static function verify(string $provider, string $payload, string $signature, array $headers = []): bool
    {
        Log::info('Webhook signature verification requested', [
            'provider' => $provider,
            'signature_length' => strlen($signature),
            'payload_length' => strlen($payload),
        ]);

        return match ($provider) {
            'momo' => self::verifyMTNMoMo($payload, $signature, $headers['x-reference-id'] ?? null),
            'celtis' => self::verifyCeltisCash($payload, $signature),
            'moov' => self::verifyMoovPay($payload, $signature),
            default => false,
        };
    }

    /**
     * ✅ Extract signature from request headers (provider-specific)
     */
    public static function extractSignature(string $provider, array $headers): ?string
    {
        // Normalize header names (lowercase)
        $normalizedHeaders = [];
        foreach ($headers as $key => $value) {
            $normalizedHeaders[strtolower($key)] = is_array($value) ? $value[0] : $value;
        }

        return match ($provider) {
            'momo' => $normalizedHeaders['x-reference-id'] ?? $normalizedHeaders['authorization'] ?? null,
            'celtis' => $normalizedHeaders['x-celtis-signature'] ?? $normalizedHeaders['signature'] ?? null,
            'moov' => $normalizedHeaders['x-moov-signature'] ?? $normalizedHeaders['signature'] ?? null,
            default => null,
        };
    }

    /**
     * ✅ Generate signature for testing (useful for webhook testing)
     */
    public static function generateTestSignature(string $provider, string $payload): ?string
    {
        return match ($provider) {
            'momo' => hash_hmac('sha256', $payload, Setting::get('momo_api_secret')),
            'celtis' => hash('sha256', $payload . Setting::get('celtis_api_secret')),
            'moov' => hash_hmac('sha512', $payload, Setting::get('moov_api_secret')),
            default => null,
        };
    }
}