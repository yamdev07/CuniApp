<?php
// database/migrations/2026_03_10_103747_add_webhook_settings_to_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            // MTN MoMo Webhook
            [
                'key' => 'momo_webhook_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'MTN MoMo Webhook Secret',
                'description' => 'Secret key for verifying MTN MoMo webhook signatures',
            ],
            [
                'key' => 'momo_webhook_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'payments',
                'label' => 'MTN MoMo Webhooks Enabled',
            ],

            // Celtis Cash Webhook
            [
                'key' => 'celtis_webhook_secret',
                'value' => '',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'Celtis Cash Webhook Secret',
                'description' => 'Secret key for verifying Celtis Cash webhook signatures',
            ],
            [
                'key' => 'celtis_webhook_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'payments',
                'label' => 'Celtis Cash Webhooks Enabled',
            ],

            // Moov Pay Webhook
            [
                'key' => 'moov_webhook_secret',
                'value' => '',
                'value' => '',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'Moov Pay Webhook Secret',
                'description' => 'Secret key for verifying Moov Pay webhook signatures',
            ],
            [
                'key' => 'moov_webhook_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'payments',
                'label' => 'Moov Pay Webhooks Enabled',
            ],

            // General Webhook Settings
            [
                'key' => 'webhook_log_retention_days',
                'value' => '90',
                'type' => 'number',
                'group' => 'payments',
                'label' => 'Webhook Log Retention (Days)',
                'description' => 'How long to keep webhook logs',
            ],
            [
                'key' => 'webhook_ip_whitelist',
                'value' => '',
                'type' => 'string',
                'group' => 'payments',
                'label' => 'Webhook IP Whitelist',
                'description' => 'Comma-separated list of allowed webhook IPs (optional)',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    public function down(): void
    {
        $keys = [
            'momo_webhook_secret',
            'momo_webhook_enabled',
            'celtis_webhook_secret',
            'celtis_webhook_enabled',
            'moov_webhook_secret',
            'moov_webhook_enabled',
            'webhook_log_retention_days',
            'webhook_ip_whitelist',
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
