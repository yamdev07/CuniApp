<?php
// app/Console/Commands/TestWebhookSignature.php

namespace App\Console\Commands;

use App\Services\WebhookSignatureVerifier;
use Illuminate\Console\Command;

class TestWebhookSignature extends Command
{
    protected $signature = 'webhook:test-signature {provider} {payload} {signature}';
    protected $description = 'Test webhook signature verification';

    public function handle()
    {
        $provider = $this->argument('provider');
        $payload = $this->argument('payload');
        $signature = $this->argument('signature');

        $this->info("Testing webhook signature verification for: {$provider}");
        $this->line("Payload: " . substr($payload, 0, 100) . '...');
        $this->line("Signature: " . substr($signature, 0, 20) . '...');

        // ✅ Support FedaPay
        $isValid = WebhookSignatureVerifier::verify($provider, $payload, $signature);

        if ($isValid) {
            $this->info('✅ Signature verification: SUCCESS');
        } else {
            $this->error('❌ Signature verification: FAILED');
            $this->error('Check your webhook secret in Settings > FedaPay Configuration');
        }

        return Command::SUCCESS;
    }
}
