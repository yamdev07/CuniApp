// database/migrations/2026_03_24_000004_add_max_users_to_subscription_plans.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->integer('max_users')->default(5)->after('price');
            $table->index('max_users');
        });

        // Set defaults based on duration
        DB::table('subscription_plans')->update([
            'max_users' => DB::raw('CASE 
                WHEN duration_months <= 3 THEN 5
                WHEN duration_months <= 6 THEN 8
                ELSE 10
            END')
        ]);
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('max_users');
        });
    }
};
