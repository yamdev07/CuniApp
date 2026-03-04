<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lapereaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mise_bas_id')->constrained('mises_bas')->onDelete('cascade');
            $table->integer('age_semaines')->nullable();
            $table->enum('categorie', ['<5 semaines', '5-8 semaines', '8-12 semaines', '+12 semaines'])->nullable();
            $table->decimal('alimentation_jour', 6, 2)->default(0.00);
            $table->decimal('alimentation_semaine', 6, 2)->default(0.00);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapereaux');
    }
};
