<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 Seeding Users...');

        // Main Admin User
        $admin = User::create([
            'name' => 'Administrateur CuniApp',
            'email' => 'admin@cuniapp.com',
            'password' => Hash::make('CuniApp2024!'),
            'email_verified_at' => now(),
            'notifications_email' => true,
            'notifications_dashboard' => true,
            'theme' => 'dark',
            'language' => 'fr',
            'remember_token' => Str::random(60),
        ]);

        // Farm Manager User
        $manager = User::create([
            'name' => 'Jean Dupont',
            'email' => 'manager@cuniapp.com',
            'password' => Hash::make('Manager2024!'),
            'email_verified_at' => now(),
            'notifications_email' => true,
            'notifications_dashboard' => true,
            'theme' => 'light',
            'language' => 'fr',
            'remember_token' => Str::random(60),
        ]);

        // Worker User
        $worker = User::create([
            'name' => 'Marie Martin',
            'email' => 'worker@cuniapp.com',
            'password' => Hash::make('Worker2024!'),
            'email_verified_at' => now(),
            'notifications_email' => false,
            'notifications_dashboard' => true,
            'theme' => 'system',
            'language' => 'fr',
            'remember_token' => Str::random(60),
        ]);

        $this->command->info('   ✓ Created 3 users');
        $this->command->newLine();
        $this->command->warn('📧 LOGIN CREDENTIALS:');
        $this->command->warn('   ┌─────────────────────────────────────────────────────┐');
        $this->command->warn('   │ ADMIN:                                              │');
        $this->command->warn('   │ Email: admin@cuniapp.com                            │');
        $this->command->warn('   │ Password: CuniApp2024!                              │');
        $this->command->warn('   ├─────────────────────────────────────────────────────┤');
        $this->command->warn('   │ MANAGER:                                            │');
        $this->command->warn('   │ Email: manager@cuniapp.com                          │');
        $this->command->warn('   │ Password: Manager2024!                              │');
        $this->command->warn('   ├─────────────────────────────────────────────────────┤');
        $this->command->warn('   │ WORKER:                                             │');
        $this->command->warn('   │ Email: worker@cuniapp.com                           │');
        $this->command->warn('   │ Password: Worker2024!                               │');
        $this->command->warn('   └─────────────────────────────────────────────────────┘');
        $this->command->newLine();
    }
}