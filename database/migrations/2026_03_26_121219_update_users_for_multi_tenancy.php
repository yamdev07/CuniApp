// database/migrations/2026_03_24_000002_update_users_for_multi_tenancy.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add firm_id column
            $table->foreignId('firm_id')->nullable()->after('id')->constrained('firms')->onDelete('set null');

            // Update role enum to include new roles
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'firm_admin', 'employee', 'user') DEFAULT 'user'");

            // Index for firm scoping
            $table->index('firm_id');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropColumn('firm_id');

            // Revert role enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') DEFAULT 'user'");
        });
    }
};
