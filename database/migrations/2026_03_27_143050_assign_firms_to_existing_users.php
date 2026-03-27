<?php
// database/migrations/2026_03_27_000002_assign_firms_to_existing_users.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Firm;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            // Get all users without a firm
            $usersWithoutFirm = User::whereNull('firm_id')->get();

            foreach ($usersWithoutFirm as $user) {
                // Create a firm for each user without one
                $firm = Firm::create([
                    'name' => "Entreprise de {$user->name}",
                    'description' => 'Entreprise créée automatiquement lors de la migration multi-tenancy',
                    'owner_id' => $user->id,
                    'status' => 'active',
                ]);

                // Update user with firm_id
                $user->update(['firm_id' => $firm->id]);

                // Update all breeding records for this user
                $tables = ['males', 'femelles', 'saillies', 'mises_bas', 'naissances', 'lapereaux', 'sales', 'expenses'];

                foreach ($tables as $table) {
                    if (Schema::hasTable($table)) {
                        DB::table($table)
                            ->where('user_id', $user->id)
                            ->update(['firm_id' => $firm->id]);
                    }
                }

                \Log::info("Firm assigned to user", [
                    'user_id' => $user->id,
                    'firm_id' => $firm->id,
                ]);
            }
        });
    }

    public function down(): void
    {
        // Optional: Remove auto-created firms
        Firm::where('description', 'LIKE', '%créée automatiquement lors de la migration%')
            ->each(function ($firm) {
                $firm->delete();
            });
    }
};
