<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->warn('╔══════════════════════════════════════════════════════════════╗');
        $this->command->warn('║                                                              ║');
        $this->command->warn('║          🐰 CUNIAPP ÉLEVAGE - DATABASE SEEDER 🐰            ║');
        $this->command->warn('║                                                              ║');
        $this->command->warn('║     Gestion Intelligente de Votre Élevage de Lapins         ║');
        $this->command->warn('║                                                              ║');
        $this->command->warn('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');

        // Clear existing data (optional - comment out for production)
        $this->clearDatabase();

        // Run the main seeder
        $this->call([
            CuniAppDatabaseSeeder::class,
        ]);

        $this->command->info('');
        $this->command->warn('╔══════════════════════════════════════════════════════════════╗');
        $this->command->warn('║                                                              ║');
        $this->command->warn('║              ✅ SEEDING COMPLETED SUCCESSFULLY! ✅           ║');
        $this->command->warn('║                                                              ║');
        $this->command->warn('╚══════════════════════════════════════════════════════════════╝');
        $this->command->info('');

        // Display summary
        $this->displaySummary();
    }

    /**
     * Clear existing database tables
     */
    private function clearDatabase(): void
    {
        $this->command->info('🧹 Cleaning existing data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            'payment_transactions',
            'subscriptions',
            'subscription_plans',
            'sale_rabbits',
            'sales',
            'lapereaux',
            'naissances',
            'mises_bas',
            'saillies',
            'femelles',
            'males',
            'notifications',
            'settings',
            'users',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->command->line("   ✓ Table <fg=blue>{$table}</fg=blue> cleared");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('');
    }

    /**
     * Display seeding summary
     */
    private function displaySummary(): void
    {
        $this->command->info('📊 DATABASE SUMMARY:');
        $this->command->info('');

        $stats = [
            '👥 Users' => \App\Models\User::count(),
            '🐰 Mâles' => \App\Models\Male::count(),
            '🐰 Femelles' => \App\Models\Femelle::count(),
            '💕 Saillies' => \App\Models\Saillie::count(),
            '🥚 Mises Bas' => \App\Models\MiseBas::count(),
            '🐣 Naissances' => \App\Models\Naissance::count(),
            '🐇 Lapereaux' => \App\Models\Lapereau::count(),
            '💰 Ventes' => \App\Models\Sale::count(),
            '📋 Plans' => \App\Models\SubscriptionPlan::count(),
            '📝 Subscriptions' => \App\Models\Subscription::count(),
        ];

        foreach ($stats as $label => $count) {
            $this->command->line("   <fg=green>{$label}:</fg=green> <fg=cyan>{$count}</fg=cyan>");
        }

        $this->command->info('');
        $this->command->info('🔐 DEFAULT ADMIN CREDENTIALS:');
        $this->command->info('   Email: <fg=yellow>admin@cuniapp.com</fg=yellow>');
        $this->command->info('   Password: <fg=yellow>admin123</fg=yellow>');
        $this->command->info('');
        $this->command->info('🔐 DEFAULT USER CREDENTIALS:');
        $this->command->info('   Email: <fg=yellow>user@cuniapp.com</fg=yellow>');
        $this->command->info('   Password: <fg=yellow>user123</fg=yellow>');
        $this->command->info('');
    }
}
