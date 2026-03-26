<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('firm_id')
                ->nullable()
                ->after('user_id')
                ->constrained('firms')
                ->onDelete('cascade');
            $table->index('firm_id');
        });

        // Backfill existing notifications
        DB::statement("
        UPDATE notifications n
        JOIN users u ON n.user_id = u.id
        SET n.firm_id = u.firm_id
        WHERE u.firm_id IS NOT NULL
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
