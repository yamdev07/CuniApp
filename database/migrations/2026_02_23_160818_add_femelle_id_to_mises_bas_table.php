<?php
// database/migrations/2026_02_23_000000_add_femelle_id_to_mises_bas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('mises_bas', function (Blueprint $table) {
            // Add femelle_id column
            $table->foreignId('femelle_id')->nullable()->after('saillie_id')->constrained('femelles')->onDelete('cascade');
            // Make saillie_id nullable (since we're using femelle_id directly now)
            $table->foreignId('saillie_id')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('mises_bas', function (Blueprint $table) {
            $table->dropForeign(['femelle_id']);
            $table->dropColumn('femelle_id');
            $table->foreignId('saillie_id')->nullable(false)->change();
        });
    }
};