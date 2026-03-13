<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('femelles', function (Blueprint $table) {
            // ✅ Ajouter 'vendu' à l'enum existant
            $table->enum('etat', ['Active', 'Gestante', 'Allaitante', 'Vide', 'vendu'])
                  ->default('Active')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('femelles', function (Blueprint $table) {
            // 🔙 Revenir à l'enum original (sans 'vendu')
            $table->enum('etat', ['Active', 'Gestante', 'Allaitante', 'Vide'])
                  ->default('Active')
                  ->change();
        });
    }
};