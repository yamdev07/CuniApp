<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $tables = [
        'males',
        'femelles',
        'saillies',
        'mises_bas',
        'naissances',
        'lapereaux',
        'sales',
        'invoices'
        // 'sale_rabbits' ← removed
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                // Step 1: Add column only if it doesn't exist
                if (!Schema::hasColumn($table, 'firm_id')) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->foreignId('firm_id')
                            ->nullable()
                            ->after('user_id')
                            ->constrained('firms')
                            ->onDelete('cascade');
                        $table->index('firm_id');
                    });
                }

                // Step 2: Backfill existing records (safe to run even if column already exists)
                DB::statement("
                UPDATE {$table} t
                JOIN users u ON t.user_id = u.id
                SET t.firm_id = u.firm_id
                WHERE u.firm_id IS NOT NULL
            ");
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['firm_id']);
                    $table->dropColumn('firm_id');
                });
            }
        }
    }
};
