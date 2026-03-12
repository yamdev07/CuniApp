<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // ❌ SUPPRIMER les anciennes clés opérateurs directs
        DB::table('settings')->whereIn('key', [
            'momo_api_key', 'momo_api_secret', 'momo_environment',
            'celtis_api_key', 'celtis_api_secret',
            'moov_api_key', 'moov_api_secret',
            'momo_webhook_secret', 'celtis_webhook_secret', 'moov_webhook_secret',
        ])->delete();

        // ✅ AJOUTER les clés FedaPay
        DB::table('settings')->insert([
            [
                'key' => 'fedapay_public_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'Clé Publique FedaPay',
                'description' => 'Clé publique FedaPay (sandbox ou production)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'fedapay_secret_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'Clé Secrète FedaPay',
                'description' => 'Clé secrète FedaPay pour signature webhook',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'fedapay_environment',
                'value' => 'sandbox',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'Environnement FedaPay',
                'description' => 'sandbox ou production',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'fedapay_webhook_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'Secret Webhook FedaPay',
                'description' => 'Secret pour vérifier les signatures webhook',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void {
        DB::table('settings')->whereIn('key', [
            'fedapay_public_key',
            'fedapay_secret_key',
            'fedapay_environment',
            'fedapay_webhook_secret',
        ])->delete();
    }
};