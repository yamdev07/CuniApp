<?php

// database/migrations/2026_03_04_090033_update_naissance_mises_bas_structure.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update mises_bas table - drop redundant columns
        Schema::table('mises_bas', function (Blueprint $table) {
            $columns = Schema::getColumnListing('mises_bas');
            if (in_array('nb_vivant', $columns))    $table->dropColumn('nb_vivant');
            if (in_array('nb_mort_ne', $columns))   $table->dropColumn('nb_mort_ne');
            if (in_array('nb_retire', $columns))    $table->dropColumn('nb_retire');
            if (in_array('nb_adopte', $columns))    $table->dropColumn('nb_adopte');
            $table->foreignId('saillie_id')->nullable()->change();
        });

        // 2. Update naissances table
        Schema::table('naissances', function (Blueprint $table) {
            $columns = Schema::getColumnListing('naissances');

            // Drop femelle_id foreign key and column
            if (in_array('femelle_id', $columns)) {
                try {
                    $table->dropForeign(['femelle_id']);
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE naissances DROP FOREIGN KEY naissances_femelle_id_foreign');
                }
                $table->dropColumn('femelle_id');
            }

            // Drop redundant count/date columns
            foreach (['nb_vivant', 'nb_mort_ne', 'nb_total', 'date_naissance', 'heure_naissance', 'lieu_naissance'] as $col) {
                if (in_array($col, $columns)) $table->dropColumn($col);
            }

            // Add verification tracking if not exists
            if (!in_array('sex_verified', $columns)) {
                $table->boolean('sex_verified')->default(false);
                $table->timestamp('sex_verified_at')->nullable();
                $table->timestamp('first_reminder_sent_at')->nullable();
                $table->timestamp('last_reminder_sent_at')->nullable();
                $table->integer('reminder_count')->default(0);
            }
        });

        // ✅ FIX: Update mise_bas_id FK in a SEPARATE Schema::table call
        // This avoids "Duplicate column" error from foreignId()->change() bug.
        Schema::table('naissances', function (Blueprint $table) {
            $columns = Schema::getColumnListing('naissances');

            if (in_array('mise_bas_id', $columns)) {
                // Step 1: Drop existing FK constraint
                try {
                    $table->dropForeign(['mise_bas_id']);
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE naissances DROP FOREIGN KEY naissances_mise_bas_id_foreign');
                }

                // Step 2: Alter the column to NOT NULL using unsignedBigInteger (avoids foreignId() bug)
                $table->unsignedBigInteger('mise_bas_id')->nullable(false)->change();

                // Step 3: Re-add the foreign key constraint
                $table->foreign('mise_bas_id')
                    ->references('id')
                    ->on('mises_bas')
                    ->onDelete('cascade');
            }
        });

        // 3. Update lapereaux table
        Schema::table('lapereaux', function (Blueprint $table) {
            $columns = Schema::getColumnListing('lapereaux');

            if (in_array('code', $columns)) {
                // Drop unique index first to avoid "Duplicate key name" error on ->change()
                try {
                    $table->dropUnique('lapereaux_code_unique');
                } catch (\Exception $e) {
                    // Index may not exist yet, safe to ignore
                }
                $table->string('code', 20)->nullable(false)->change();
                $table->unique('code');
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
            $table->foreignId('femelle_id')->nullable()->constrained('femelles')->onDelete('cascade');
            $table->integer('nb_vivant')->default(0);
            $table->integer('nb_mort_ne')->default(0);
            $table->integer('nb_total')->storedAs('nb_vivant + nb_mort_ne');
            $table->date('date_naissance')->nullable();
            $table->time('heure_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();

            try {
                $table->dropForeign(['mise_bas_id']);
            } catch (\Exception $e) {
                DB::statement('ALTER TABLE naissances DROP FOREIGN KEY naissances_mise_bas_id_foreign');
            }
            $table->foreignId('mise_bas_id')->nullable()->constrained('mises_bas')->onDelete('set null')->change();
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