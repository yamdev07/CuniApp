<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Remove redundant columns from mises_bas
        Schema::table('mises_bas', function (Blueprint $table) {
            $columns = Schema::getColumnListing('mises_bas');
            
            // Remove count columns (will be calculated from lapereaux)
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
            
            // Make saillie_id nullable (not all births have recorded mating)
            $table->foreignId('saillie_id')->nullable()->change();
        });

        // 2. Update naissances table
        Schema::table('naissances', function (Blueprint $table) {
            $columns = Schema::getColumnListing('naissances');
            
            // Remove direct femelle_id (get through mise_bas)
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
            
            // Remove redundant date (get from mise_bas)
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
            
            // Ensure mise_bas_id is required
            $table->foreignId('mise_bas_id')->nullable(false)->change();
        });

        // 3. Update lapereaux table
        Schema::table('lapereaux', function (Blueprint $table) {
            $columns = Schema::getColumnListing('lapereaux');
            
            // Make code REQUIRED and unique
            if (in_array('code', $columns)) {
                $table->string('code', 20)->unique()->nullable(false)->change();
            }
            
            // Make nom recommended (not required but encouraged)
            if (in_array('nom', $columns)) {
                $table->string('nom', 50)->nullable()->change();
            }
            
            // Sex can be null initially (verified after 10 days)
            if (in_array('sex', $columns)) {
                $table->enum('sex', ['male', 'female'])->nullable()->change();
            }
            
            // Ensure naissance_id is required
            $table->foreignId('naissance_id')->nullable(false)->change();
            
            // Remove redundant date_naissance (get from naissance->mise_bas)
            if (in_array('date_naissance', $columns)) {
                $table->dropColumn('date_naissance');
            }
        });
    }

    public function down(): void {
        // Rollback changes if needed
        Schema::table('lapereaux', function (Blueprint $table) {
            $table->string('code')->nullable()->change();
            $table->string('nom')->nullable()->change();
            $table->enum('sex', ['male', 'female'])->nullable(false)->change();
        });

        Schema::table('naissances', function (Blueprint $table) {
            $table->foreignId('femelle_id')->constrained('femelles')->onDelete('cascade');
            $table->integer('nb_vivant')->default(0);
            $table->integer('nb_mort_ne')->default(0);
            $table->date('date_naissance');
        });

        Schema::table('mises_bas', function (Blueprint $table) {
            $table->integer('nb_vivant')->default(0);
            $table->integer('nb_mort_ne')->default(0);
        });
    }
};