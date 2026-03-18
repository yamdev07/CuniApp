<?php
// database/migrations/2026_03_15_000001_remove_webhook_settings.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Remove all webhook-related settings
        DB::table('settings')->whereIn('key', [
            'momo_webhook_secret',
            'momo_webhook_enabled',
            'celtis_webhook_secret',
            'celtis_webhook_enabled',
            'moov_webhook_secret',
            'moov_webhook_enabled',
            'webhook_ip_whitelist',
            'webhook_log_retention_days',
            'fedapay_webhook_secret',
        ])->delete();
    }

    public function down(): void
    {
        // Optionally restore if needed (but shouldn't be needed)
        DB::table('settings')->insert([
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
};
