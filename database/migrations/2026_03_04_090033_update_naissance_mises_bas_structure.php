<?php
// database/migrations/2026_03_04_090033_update_naissance_mises_bas_structure.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Remove redundant columns from mises_bas
        Schema::table('mises_bas', function (Blueprint $table) {
            $columns = Schema::getColumnListing('mises_bas');

            if (in_array('nb_vivant', $columns)) {
                $table->dropColumn('nb_vivant');
            }
            if (in_array('nb_mort_ne', $columns)) {
                $table->dropColumn('nb_mort_ne');
            }
            if (in_array('nb_retire', $columns)) {
                $table->dropColumn('nb_retire');
            }
            if (in_array('nb_adopte', $columns)) {
                $table->dropColumn('nb_adopte');
            }

            // Make saillie_id nullable
            $table->foreignId('saillie_id')->nullable()->change();
        });

        // 2. Update naissances table - ✅ FIXED ORDER FOR mise_bas_id
        Schema::table('naissances', function (Blueprint $table) {
            $columns = Schema::getColumnListing('naissances');

            // Remove direct femelle_id
            if (in_array('femelle_id', $columns)) {
                $table->dropForeign(['femelle_id']);
                $table->dropColumn('femelle_id');
            }

            // Remove redundant count columns
            if (in_array('nb_vivant', $columns)) {
                $table->dropColumn('nb_vivant');
            }
            if (in_array('nb_mort_ne', $columns)) {
                $table->dropColumn('nb_mort_ne');
            }
            if (in_array('nb_total', $columns)) {
                $table->dropColumn('nb_total');
            }

            // Remove redundant date fields
            if (in_array('date_naissance', $columns)) {
                $table->dropColumn('date_naissance');
            }
            if (in_array('heure_naissance', $columns)) {
                $table->dropColumn('heure_naissance');
            }
            if (in_array('lieu_naissance', $columns)) {
                $table->dropColumn('lieu_naissance');
            }

            // Add sex verification tracking (if not exists)
            if (!in_array('sex_verified', $columns)) {
                $table->boolean('sex_verified')->default(false);
            }
            if (!in_array('sex_verified_at', $columns)) {
                $table->timestamp('sex_verified_at')->nullable();
            }
            if (!in_array('first_reminder_sent_at', $columns)) {
                $table->timestamp('first_reminder_sent_at')->nullable();
            }
            if (!in_array('last_reminder_sent_at', $columns)) {
                $table->timestamp('last_reminder_sent_at')->nullable();
            }
            if (!in_array('reminder_count', $columns)) {
                $table->integer('reminder_count')->default(0);
            }

            // ✅ FIX: Drop foreign key FIRST, then modify column
            if (in_array('mise_bas_id', $columns)) {
                // Step 1: Drop existing foreign key constraint
                $table->dropForeign(['mise_bas_id']);
                
                // Step 2: Modify column to NOT NULL with CASCADE
                $table->foreignId('mise_bas_id')
                    ->nullable(false)
                    ->constrained('mises_bas')
                    ->onDelete('cascade')
                    ->change();
            }
        });

        // 3. Update lapereaux table
        Schema::table('lapereaux', function (Blueprint $table) {
            $columns = Schema::getColumnListing('lapereaux');

            if (in_array('code', $columns)) {
                $table->string('code', 20)->unique()->nullable(false)->change();
            }

            if (in_array('nom', $columns)) {
                $table->string('nom', 50)->nullable()->change();
            }

            if (in_array('sex', $columns)) {
                $table->enum('sex', ['male', 'female'])->nullable()->change();
            }

            if (in_array('naissance_id', $columns)) {
                $table->foreignId('naissance_id')->nullable(false)->change();
            }

            if (in_array('date_naissance', $columns)) {
                $table->dropColumn('date_naissance');
            }
        });
    }

    public function down(): void
    {
        // Rollback lapereaux
        Schema::table('lapereaux', function (Blueprint $table) {
            $table->string('code')->nullable()->change();
            $table->string('nom')->nullable()->change();
            $table->enum('sex', ['male', 'female'])->nullable(false)->change();
            $table->foreignId('naissance_id')->nullable()->change();
        });

        // Rollback naissances
        Schema::table('naissances', function (Blueprint $table) {
            // Restore femelle_id
            $table->foreignId('femelle_id')
                ->nullable()
                ->constrained('femelles')
                ->onDelete('cascade');
            
            // Restore count columns
            $table->integer('nb_vivant')->default(0);
            $table->integer('nb_mort_ne')->default(0);
            $table->integer('nb_total')->storedAs('nb_vivant + nb_mort_ne');
            
            // Restore date fields
            $table->date('date_naissance')->nullable();
            $table->time('heure_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            
            // Restore mise_bas_id foreign key with SET NULL for rollback
            $table->dropForeign(['mise_bas_id']);
            $table->foreignId('mise_bas_id')
                ->nullable()
                ->constrained('mises_bas')
                ->onDelete('set null')
                ->change();
        });

        // Rollback mises_bas
        Schema::table('mises_bas', function (Blueprint $table) {
            $table->integer('nb_vivant')->default(0);
            $table->integer('nb_mort_ne')->default(0);
            $table->integer('nb_retire')->default(0);
            $table->integer('nb_adopte')->default(0);
            $table->foreignId('saillie_id')->nullable(false)->change();
        });
    }
};