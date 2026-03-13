<?php
// tests/Feature/WebhookSignatureVerificationTest.php

namespace Tests\Feature;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\Setting;
use App\Services\WebhookSignatureVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebhookSignatureVerificationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_webhook_rejects_invalid_signature()
    {
        $payload = json_encode(['transaction_id' => 'TXN-123', 'status' => 'SUCCESSFUL']);
        $invalidSignature = 'invalid_signature_hash';
        
        $response = $this->postJson('/payment/webhook/momo', [], [
            'X-Reference-Id' => $invalidSignature,
        ]);
        
        $response->assertStatus(401);
        $response->assertJson(['status' => 'error', 'message' => 'Invalid signature']);
    }
    
    public function test_webhook_accepts_valid_momo_signature()
    {
        // Setup
        Setting::set('momo_api_secret', 'test_secret_key');
        
        $payload = json_encode(['transaction_id' => 'TXN-123', 'status' => 'SUCCESSFUL']);
        $validSignature = hash_hmac('sha256', $payload, 'test_secret_key');
        
        $transaction = PaymentTransaction::create([
            'user_id' => 1,
            'transaction_id' => 'TXN-123',
            'amount' => 2500,
            'payment_method' => 'momo',
            'status' => 'pending',
            'provider' => 'momo',
        ]);
        
        $response = $this->postJson('/payment/webhook/momo', json_decode($payload, true), [
            'X-Reference-Id' => $validSignature,
            'Content-Type' => 'application/json',
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
        
        // Verify transaction was updated
        $this->assertDatabaseHas('payment_transactions', [
            'transaction_id' => 'TXN-123',
            'status' => 'completed',
        ]);
    }
    
    public function test_webhook_idempotency_prevents_duplicate_processing()
    {
        Setting::set('momo_api_secret', 'test_secret_key');
        
        $payload = json_encode(['transaction_id' => 'TXN-456', 'status' => 'SUCCESSFUL']);
        $signature = hash_hmac('sha256', $payload, 'test_secret_key');
        
        // First request
        $this->postJson('/payment/webhook/momo', json_decode($payload, true), [
            'X-Reference-Id' => $signature,
        ])->assertStatus(200);
        
        // Second request (should be marked as duplicate)
        $response = $this->postJson('/payment/webhook/momo', json_decode($payload, true), [
            'X-Reference-Id' => $signature,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['duplicate' => true]);
    }
    
    public function test_webhook_verifier_momo_signature()
    {
        Setting::set('momo_api_secret', 'test_secret');
        
        $payload = '{"test": "data"}';
        $signature = hash_hmac('sha256', $payload, 'test_secret');
        
        $this->assertTrue(WebhookSignatureVerifier::verifyMTNMoMo($payload, $signature));
        $this->assertFalse(WebhookSignatureVerifier::verifyMTNMoMo($payload, 'wrong_signature'));
    }
    
    public function test_webhook_verifier_celtis_signature()
    {
        Setting::set('celtis_api_secret', 'celtis_secret');
        
        $payload = '{"test": "data"}';
        $signature = hash('sha256', $payload . 'celtis_secret');
        
        $this->assertTrue(WebhookSignatureVerifier::verifyCeltisCash($payload, $signature));
        $this->assertFalse(WebhookSignatureVerifier::verifyCeltisCash($payload, 'wrong_signature'));
    }
    
    public function test_webhook_verifier_moov_signature()
    {
        Setting::set('moov_api_secret', 'moov_secret');
        
        $payload = '{"test": "data"}';
        $signature = hash_hmac('sha512', $payload, 'moov_secret');
        
        $this->assertTrue(WebhookSignatureVerifier::verifyMoovPay($payload, $signature));
        $this->assertFalse(WebhookSignatureVerifier::verifyMoovPay($payload, 'wrong_signature'));
    }
}