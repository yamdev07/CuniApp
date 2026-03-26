<?php
// database/migrations/2026_03_26_999999_migrate_existing_data_to_firms.php
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
            // Get all users who are not super admins
            $users = User::whereNull('firm_id')
                ->where('role', '!=', 'super_admin')
                ->get();

            foreach ($users as $user) {
                // Create firm for each user
                $firm = Firm::create([
                    'name' => "Entreprise de {$user->name}",
                    'description' => 'Entreprise créée automatiquement lors de la migration',
                    'owner_id' => $user->id,
                    'status' => 'active',
                ]);

                // Link user to firm
                $user->update(['firm_id' => $firm->id]);

                // If user was admin, make them firm_admin
                if ($user->role === 'admin') {
                    $user->update(['role' => 'firm_admin']);
                }

                // Update all breeding records for this user
                DB::table('males')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
                DB::table('femelles')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
                DB::table('saillies')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
                DB::table('mises_bas')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
                DB::table('naissances')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
                DB::table('lapereaux')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);
                DB::table('sales')->where('user_id', $user->id)->update(['firm_id' => $firm->id]);

                // Update subscriptions
                DB::table('subscriptions')
                    ->where('user_id', $user->id)
                    ->update(['firm_id' => $firm->id]);
            }
        });
    }

    public function down(): void
    {
        // Optional rollback
        DB::table('firms')->delete();
        User::whereNotNull('firm_id')->update(['firm_id' => null]);
    }
};
