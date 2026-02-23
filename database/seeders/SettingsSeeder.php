<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder {
    public function run(): void {
        $settings = [
            // Général
            ['key' => 'farm_name', 'value' => 'Ma Ferme', 'type' => 'string', 'group' => 'general', 'label' => 'Nom de la ferme'],
            ['key' => 'farm_address', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Adresse'],
            ['key' => 'farm_phone', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Téléphone'],
            ['key' => 'farm_email', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Email'],
            
            // Élevage
            ['key' => 'gestation_days', 'value' => '31', 'type' => 'number', 'group' => 'breeding', 'label' => 'Jours de gestation'],
            ['key' => 'weaning_weeks', 'value' => '6', 'type' => 'number', 'group' => 'breeding', 'label' => 'Semaines de sevrage'],
            ['key' => 'alert_threshold', 'value' => '80', 'type' => 'number', 'group' => 'breeding', 'label' => 'Seuil d\'alerte'],
            
            // Utilisateur
            ['key' => 'theme', 'value' => 'dark', 'type' => 'string', 'group' => 'user', 'label' => 'Thème'],
            ['key' => 'language', 'value' => 'fr', 'type' => 'string', 'group' => 'user', 'label' => 'Langue'],
            ['key' => 'notifications_email', 'value' => '0', 'type' => 'boolean', 'group' => 'user', 'label' => 'Notifications email'],
            ['key' => 'notifications_dashboard', 'value' => '1', 'type' => 'boolean', 'group' => 'user', 'label' => 'Notifications dashboard'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}