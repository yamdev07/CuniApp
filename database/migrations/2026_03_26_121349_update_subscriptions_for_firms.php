<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Add firm_id (subscriptions belong to firm, not just user)
            $table->foreignId('firm_id')->nullable()->after('user_id')->constrained('firms')->onDelete('cascade');

            // Index for firm-based queries
            $table->index('firm_id');
        });

        // Link existing subscriptions to user's firm (will be created in data migration)
        DB::statement('
            UPDATE subscriptions s
            JOIN users u ON s.user_id = u.id
            SET s.firm_id = u.firm_id
            WHERE u.firm_id IS NOT NULL
        ');
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['firm_id']);
            $table->dropColumn('firm_id');
        });
    }
};
