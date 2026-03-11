<?php
// database/migrations/2026_03_10_000006_add_payment_settings_to_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'momo_api_key', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'MTN MoMo API Key'],
            ['key' => 'momo_api_secret', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'MTN MoMo API Secret'],
            ['key' => 'momo_environment', 'value' => 'sandbox', 'type' => 'string', 'group' => 'payments', 'label' => 'MTN MoMo Environment'],
            ['key' => 'celtis_api_key', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'Celtis Cash API Key'],
            ['key' => 'celtis_api_secret', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'Celtis Cash API Secret'],
            ['key' => 'moov_api_key', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'Moov Pay API Key'],
            ['key' => 'moov_api_secret', 'value' => '', 'type' => 'string', 'group' => 'payments', 'label' => 'Moov Pay API Secret'],
            ['key' => 'grace_period_days', 'value' => '3', 'type' => 'number', 'group' => 'subscriptions', 'label' => 'Grace Period (Days)'],
            ['key' => 'enable_auto_renew', 'value' => '1', 'type' => 'boolean', 'group' => 'subscriptions', 'label' => 'Enable Auto-Renewal'],
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
            'momo_api_key', 'momo_api_secret', 'momo_environment',
            'celtis_api_key', 'celtis_api_secret',
            'moov_api_key', 'moov_api_secret',
            'grace_period_days', 'enable_auto_renew'
        ];
        
        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};