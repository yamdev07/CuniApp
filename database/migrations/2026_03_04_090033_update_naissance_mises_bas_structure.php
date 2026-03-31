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

            // Drop femelle_id foreign key, index and column
            if (in_array('femelle_id', $columns)) {
                try {
                    $table->dropForeign(['femelle_id']);
                } catch (\Exception $e) {
                    try {
                        DB::statement('ALTER TABLE naissances DROP FOREIGN KEY naissances_femelle_id_foreign');
                    } catch (\Exception $e2) {
                        // ignore if not mysql or not exists
                    }
                }
                
                try {
                    $table->dropIndex('naissances_femelle_id_index');
                } catch (\Exception $e) {
                    // index may not exist
                }
                
                $table->dropColumn('femelle_id');
            }

            // Drop redundant count/date columns
            $colsToDrop = ['nb_vivant', 'nb_mort_ne', 'nb_total', 'date_naissance', 'heure_naissance', 'lieu_naissance'];
            
            // For SQLite, we need to check if indexes exist first
            $existingIndexes = [];
            if (DB::getDriverName() === 'sqlite') {
                $existingIndexes = collect(DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name='naissances'"))->pluck('name')->toArray();
            }

            foreach ($colsToDrop as $col) {
                if (in_array($col, $columns)) {
                    Schema::table('naissances', function (Blueprint $table) use ($col, $existingIndexes) {
                        $indexName = "naissances_{$col}_index";
                        if (DB::getDriverName() !== 'sqlite' || in_array($indexName, $existingIndexes)) {
                            try {
                                $table->dropIndex($indexName);
                            } catch (\Exception $e) {}
                        }
                        $table->dropColumn($col);
                    });
                }
            }

            // Add verification tracking if not exists
            Schema::table('naissances', function (Blueprint $table) {
                $columns = Schema::getColumnListing('naissances');
                if (!in_array('sex_verified', $columns)) {
                    $table->boolean('sex_verified')->default(false);
                    $table->timestamp('sex_verified_at')->nullable();
                    $table->timestamp('first_reminder_sent_at')->nullable();
                    $table->timestamp('last_reminder_sent_at')->nullable();
                    $table->integer('reminder_count')->default(0);
                }
            });
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
        Schema::table('naissances', function (Blueprint $table) {
            // Guard against columns already existing (added by later migrations during rollback)
            if (!Schema::hasColumn('naissances', 'nb_mort_ne')) {
                $table->integer('nb_mort_ne')->default(0);
            }
            if (!Schema::hasColumn('naissances', 'nb_total')) {
                $table->integer('nb_total')->storedAs('nb_vivant + nb_mort_ne');
            }
        });

        Schema::table('lapereaux', function (Blueprint $table) {
            $table->dropForeign(['naissance_id']);
            $table->dropColumn(['naissance_id', 'sex', 'nom', 'code', 'etat']);
            $table->foreignId('mise_bas_id')->nullable(false)->change();
        });
    }
};
