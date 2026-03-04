<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting CuniApp Élevage Database Seeding...');
        $this->command->newLine();

        // Seed in correct order (respecting foreign keys)
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            MaleSeeder::class,
            FemelleSeeder::class,
            SaillieSeeder::class,
            MiseBasSeeder::class,
            NaissanceSeeder::class,
            LapereauSeeder::class,
            SaleSeeder::class,
            NotificationSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
    }
}