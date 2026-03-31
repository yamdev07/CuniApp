<?php
// database/migrations/2026_03_26_121219_update_users_for_multi_tenancy.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add firm_id column (guard against duplicate)
            if (!Schema::hasColumn('users', 'firm_id')) {
                $table->foreignId('firm_id')->nullable()->after('id')->constrained('firms')->onDelete('set null');
                $table->index('firm_id');
            }

            if (!Schema::hasIndex('users', 'users_role_index')) {
                $table->index('role');
            }
        });

        // ✅ STEP 1: Expand ENUM to include BOTH old and new values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'super_admin', 'firm_admin', 'employee') DEFAULT 'user'");
        }

        // ✅ STEP 2: Remap existing 'admin' rows to new roles
        $firstAdmin = DB::table('users')->where('role', 'admin')->orderBy('id')->first();
        if ($firstAdmin) {
            // First admin becomes super_admin
            DB::table('users')->where('id', $firstAdmin->id)->update(['role' => 'super_admin']);

            // Other admins become firm_admin
            DB::table('users')
                ->where('role', 'admin')
                ->where('id', '!=', $firstAdmin->id)
                ->update(['role' => 'firm_admin']);
        }

        // ✅ STEP 3: Now that no rows use 'admin' anymore, tighten the ENUM
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'firm_admin', 'employee', 'user') DEFAULT 'user'");
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'firm_id')) {
                $table->dropForeign(['firm_id']);
                $table->dropColumn('firm_id');
            }

            // Revert ENUM (map firm_admin/super_admin back to admin)
            DB::table('users')->whereIn('role', ['firm_admin', 'super_admin'])->update(['role' => 'admin']);
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') DEFAULT 'user'");
            }
        });
    }
};