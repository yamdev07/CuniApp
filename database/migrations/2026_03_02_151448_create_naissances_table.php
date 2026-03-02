<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('naissances', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('femelle_id')->constrained('femelles')->onDelete('cascade');
            $table->foreignId('saillie_id')->nullable()->constrained('saillies')->onDelete('set null');
            $table->foreignId('mise_bas_id')->nullable()->constrained('mises_bas')->onDelete('set null');
            
            // Birth Information
            $table->date('date_naissance');
            $table->time('heure_naissance')->nullable();
            $table->string('lieu_naissance')->nullable(); // Box/cage location
            
            // Litter Details
            $table->integer('nb_vivant')->default(0);
            $table->integer('nb_mort_ne')->default(0);
            $table->integer('nb_total')->storedAs('nb_vivant + nb_mort_ne');
            $table->integer('nb_sevre')->default(0);
            
            // Weight Tracking
            $table->decimal('poids_moyen_naissance', 5, 2)->nullable(); // in grams
            $table->decimal('poids_total_portee', 5, 2)->nullable();
            
            // Health Status
            $table->enum('etat_sante', ['Excellent', 'Bon', 'Moyen', 'Faible'])->default('Bon');
            $table->text('observations')->nullable();
            
            // Next Expected Events
            $table->date('date_sevrage_prevue')->nullable();
            $table->date('date_vaccination_prevue')->nullable();
            
            // Tracking
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('femelle_id');
            $table->index('date_naissance');
            $table->index('etat_sante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naissances');
    }
};