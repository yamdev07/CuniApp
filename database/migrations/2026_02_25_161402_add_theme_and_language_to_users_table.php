<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Add AFTER notification preferences columns
            $table->string('theme')->default('dark')->after('notifications_dashboard');
            $table->string('language')->default('fr')->after('theme');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['theme', 'language']);
        });
    }
};