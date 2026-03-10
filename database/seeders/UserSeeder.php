<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@cuniapp.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('admin123'),
                'role' => 'admin', // ✅ CRITICAL: Set role
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addYear(),
                'theme' => 'system',
                'language' => 'fr',
                'notifications_email' => true,
                'notifications_dashboard' => true,
            ]
        );

        // ✅ Create Regular Test User (WITHOUT subscription)
        User::updateOrCreate(
            ['email' => 'user@cuniapp.com'],
            [
                'name' => 'Utilisateur Test',
                'password' => Hash::make('user123'),
                'role' => 'user', // ✅ CRITICAL: Set role
                'email_verified_at' => now(),
                'subscription_status' => 'inactive', // ✅ No subscription
                'subscription_ends_at' => null,
                'theme' => 'system',
                'language' => 'fr',
                'notifications_email' => true,
                'notifications_dashboard' => true,
            ]
        );

        // ✅ Create Regular Test User (WITH subscription)
        User::updateOrCreate(
            ['email' => 'subscriber@cuniapp.com'],
            [
                'name' => 'Abonné Test',
                'password' => Hash::make('subscriber123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_ends_at' => now()->addMonths(3),
                'theme' => 'system',
                'language' => 'fr',
                'notifications_email' => true,
                'notifications_dashboard' => true,
            ]
        );
    }
}
