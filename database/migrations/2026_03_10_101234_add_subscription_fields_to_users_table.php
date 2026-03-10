<?php
// database/migrations/2026_03_10_000005_add_subscription_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'subscription_status')) {
                $table->enum('subscription_status', ['active', 'inactive', 'expired', 'grace_period'])
                    ->default('inactive')
                    ->after('role');
            }
            
            if (!Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->datetime('subscription_ends_at')->nullable()->after('subscription_status');
            }
            
            if (!Schema::hasColumn('users', 'last_subscription_at')) {
                $table->datetime('last_subscription_at')->nullable()->after('subscription_ends_at');
            }
            
            // Indexes for performance
            $table->index('subscription_status');
            $table->index('subscription_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'subscription_status')) {
                $table->dropColumn('subscription_status');
            }
            if (Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->dropColumn('subscription_ends_at');
            }
            if (Schema::hasColumn('users', 'last_subscription_at')) {
                $table->dropColumn('last_subscription_at');
            }
        });
    }
};