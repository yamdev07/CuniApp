<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Add firm_id column if it doesn't exist
            if (!Schema::hasColumn('payment_transactions', 'firm_id')) {
                $table->foreignId('firm_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('firms')
                    ->onDelete('cascade');

                $table->index('firm_id');
            }
        });

        // Backfill existing records with user's firm_id
        DB::statement('
            UPDATE payment_transactions pt
            JOIN users u ON pt.user_id = u.id
            SET pt.firm_id = u.firm_id
            WHERE u.firm_id IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_transactions', 'firm_id')) {
                $table->dropForeign(['firm_id']);
                $table->dropColumn('firm_id');
            }
        });
    }
};
