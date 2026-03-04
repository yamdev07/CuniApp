<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting CuniApp Élevage Database Seeding...');
        $this->command->info('═══════════════════════════════════════════════════');
        
        // Disable foreign key constraints temporarily
        Schema::disableForeignKeyConstraints();
        
        // Clear all tables (except migrations)
        $this->cleanDatabase();
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
        
        // Run the main CuniApp seeder
        $this->call([
            CuniAppSeeder::class,
        ]);
        
        $this->command->info('═══════════════════════════════════════════════════');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('═══════════════════════════════════════════════════');
    }
    
    /**
     * Clean all database tables
     */
    private function cleanDatabase(): void
    {
        $this->command->info('🧹 Cleaning database tables...');
        
        $tables = [
            'notifications',
            'sales',
            'lapereaux',
            'naissances',
            'mises_bas',
            'saillies',
            'femelles',
            'males',
            'settings',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'password_reset_tokens',
            'users',
        ];
        
        foreach ($tables as $table) {
            try {
                DB::table($table)->truncate();
                $this->command->info("   ✓ Truncated: {$table}");
            } catch (\Exception $e) {
                $this->command->warn("   ⚠ Could not truncate: {$table}");
            }
        }
        
        // Reset auto-increment counters
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->command->info('   ✓ Foreign key constraints disabled');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}