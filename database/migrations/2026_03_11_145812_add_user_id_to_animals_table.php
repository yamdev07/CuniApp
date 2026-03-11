<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tables = ['males', 'femelles', 'saillies', 'mises_bas', 'naissances', 'lapereaux'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'user_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('user_id')
                        ->after('id')
                        ->constrained('users')
                        ->onDelete('cascade');
                    $table->index('user_id');
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
