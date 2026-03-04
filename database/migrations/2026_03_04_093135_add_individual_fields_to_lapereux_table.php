// database/migrations/2026_03_05_100000_add_individual_fields_to_lapereaux_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('lapereaux', function (Blueprint $table) {
            // Poids individuel à la naissance (grammes)
            if (!Schema::hasColumn('lapereaux', 'poids_naissance')) {
                $table->decimal('poids_naissance', 6, 2)->nullable()->after('etat')
                    ->comment('Poids individuel à la naissance en grammes');
            }
            
            // État de santé individuel
            if (!Schema::hasColumn('lapereaux', 'etat_sante')) {
                $table->enum('etat_sante', ['Excellent', 'Bon', 'Moyen', 'Faible'])
                    ->default('Bon')->after('poids_naissance');
            }
            
            // Observations individuelles
            if (!Schema::hasColumn('lapereaux', 'observations')) {
                $table->text('observations')->nullable()->after('etat_sante');
            }
            
            // Index pour recherche
            $table->index('poids_naissance');
            $table->index('etat_sante');
        });
    }

    public function down(): void {
        Schema::table('lapereaux', function (Blueprint $table) {
            $table->dropIndex(['poids_naissance']);
            $table->dropIndex(['etat_sante']);
            $table->dropColumn(['poids_naissance', 'etat_sante', 'observations']);
        });
    }
};