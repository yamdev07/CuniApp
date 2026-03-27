<?php
// database/migrations/2026_03_27_000001_update_firm_id_nullable_in_audit_logs_and_expenses.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make firm_id nullable in firm_audit_logs
        if (Schema::hasTable('firm_audit_logs')) {
            Schema::table('firm_audit_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable()->change();
            });
        }

        // Make firm_id nullable in expenses
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Revert to non-nullable (only if all records have firm_id)
        if (Schema::hasTable('firm_audit_logs')) {
            Schema::table('firm_audit_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->unsignedBigInteger('firm_id')->nullable(false)->change();
            });
        }
    }
};
