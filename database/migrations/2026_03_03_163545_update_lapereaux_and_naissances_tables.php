<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update lapereaux table
        Schema::table('lapereaux', function (Blueprint $table) {
            $table->foreignId('naissance_id')->nullable()->after('mise_bas_id')->constrained('naissances')->onDelete('cascade');
            $table->enum('sex', ['male', 'female'])->nullable()->after('categorie');
            $table->string('nom')->nullable()->after('sex');
            $table->string('code')->unique()->nullable()->after('nom');
            $table->enum('etat', ['vivant', 'vendu', 'mort', 'archivé'])->default('vivant')->after('code');
            $table->foreignId('mise_bas_id')->nullable()->change();
        });

        // 2. Remove nb_mort_ne from naissances - FIXED ORDER
        Schema::table('naissances', function (Blueprint $table) {
            // ✅ FIRST: Drop the generated column that depends on nb_mort_ne
            if (Schema::hasColumn('naissances', 'nb_total')) {
                $table->dropColumn('nb_total');
            }

            // ✅ THEN: Drop nb_mort_ne
            if (Schema::hasColumn('naissances', 'nb_mort_ne')) {
                $table->dropColumn('nb_mort_ne');
            }
        });
    }

    public function down(): void
    {
        Schema::table('naissances', function (Blueprint $table) {
            // Restore in reverse order
            $table->integer('nb_mort_ne')->default(0);
            $table->integer('nb_total')->storedAs('nb_vivant + nb_mort_ne');
        });

        Schema::table('lapereaux', function (Blueprint $table) {
            $table->dropForeign(['naissance_id']);
            $table->dropColumn(['naissance_id', 'sex', 'nom', 'code', 'etat']);
            $table->foreignId('mise_bas_id')->nullable(false)->change();
        });
    }
};
