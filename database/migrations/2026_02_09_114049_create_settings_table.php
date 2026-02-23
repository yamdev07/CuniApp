<?php
// database/migrations/xxxx_create_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('category')->default('general');
            $table->string('group')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'group']);
        });
        
        // Insertion des paramètres par défaut
        $this->seedDefaultSettings();
    }
    
    private function seedDefaultSettings()
    {
        $defaultSettings = [
            // Général
            ['key' => 'general.farm_name', 'value' => 'AnyxTech Élevage', 'type' => 'string', 'category' => 'general'],
            ['key' => 'general.address', 'value' => '', 'type' => 'string', 'category' => 'general'],
            ['key' => 'general.phone', 'value' => '', 'type' => 'string', 'category' => 'general'],
            ['key' => 'general.email', 'value' => '', 'type' => 'string', 'category' => 'general'],
            ['key' => 'general.currency', 'value' => 'EUR', 'type' => 'string', 'category' => 'general'],
            ['key' => 'general.timezone', 'value' => 'Europe/Paris', 'type' => 'string', 'category' => 'general'],
            ['key' => 'general.language', 'value' => 'fr', 'type' => 'string', 'category' => 'general'],
            
            // Élevage
            ['key' => 'elevage.rabbit_types', 'value' => json_encode(['viande', 'fourrure']), 'type' => 'array', 'category' => 'elevage'],
            ['key' => 'elevage.gestation_days', 'value' => '31', 'type' => 'integer', 'category' => 'elevage'],
            ['key' => 'elevage.min_mating_age', 'value' => '120', 'type' => 'integer', 'category' => 'elevage'],
            ['key' => 'elevage.temperature_alert', 'value' => '30', 'type' => 'float', 'category' => 'elevage'],
            ['key' => 'elevage.measurement_unit', 'value' => 'metric', 'type' => 'string', 'category' => 'elevage'],
            
            // Apparence
            ['key' => 'appearance.theme', 'value' => 'dark', 'type' => 'string', 'category' => 'appearance'],
            ['key' => 'appearance.accent_color', 'value' => '#00D9FF', 'type' => 'string', 'category' => 'appearance'],
            ['key' => 'appearance.density', 'value' => 'normal', 'type' => 'string', 'category' => 'appearance'],
            
            // Notifications
            ['key' => 'notifications.email_enabled', 'value' => '1', 'type' => 'boolean', 'category' => 'notifications'],
            ['key' => 'notifications.saillies_reminder', 'value' => '1', 'type' => 'boolean', 'category' => 'notifications'],
            ['key' => 'notifications.birth_alerts', 'value' => '1', 'type' => 'boolean', 'category' => 'notifications'],
            ['key' => 'notifications.vaccine_reminders', 'value' => '1', 'type' => 'boolean', 'category' => 'notifications'],
            ['key' => 'notifications.health_alerts', 'value' => '0', 'type' => 'boolean', 'category' => 'notifications'],
            
            // Backup
            ['key' => 'backup.auto_backup', 'value' => '1', 'type' => 'boolean', 'category' => 'backup'],
            ['key' => 'backup.backup_frequency', 'value' => 'daily', 'type' => 'string', 'category' => 'backup'],
            ['key' => 'backup.keep_backups', 'value' => '30', 'type' => 'integer', 'category' => 'backup'],
        ];
        
        foreach ($defaultSettings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}