<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('males', function (Blueprint $table) {
            $table->enum('etat', ['Active', 'Inactive', 'Malade'])->default('Active')->change();
        });
    }

    public function down(): void {
        Schema::table('males', function (Blueprint $table) {
            $table->string('etat')->default('Active')->change();
        });
    }
};