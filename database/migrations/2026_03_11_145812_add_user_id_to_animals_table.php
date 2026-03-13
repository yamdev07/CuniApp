<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['males', 'femelles', 'saillies', 'mises_bas', 'naissances', 'lapereaux'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                // Step 1: Add column as nullable first
                if (!Schema::hasColumn($table, 'user_id')) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->foreignId('user_id')
                            ->nullable()  // ← Make nullable initially
                            ->after('id')
                            ->constrained('users')
                            ->onDelete('cascade');
                        $table->index('user_id');
                    });
                }

                // Step 2: Backfill existing records with default user
                $defaultUserId = \App\Models\User::where('role', 'admin')->first()?->id
                    ?? \App\Models\User::first()?->id
                    ?? 1;

                DB::table($table)
                    ->whereNull('user_id')
                    ->update(['user_id' => $defaultUserId]);

                // Step 3: Make column non-nullable (optional, if you want to enforce it)
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('user_id')->nullable(false)->change();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['males', 'femelles', 'saillies', 'mises_bas', 'naissances', 'lapereaux'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'user_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                });
            }
        }
    }
};
