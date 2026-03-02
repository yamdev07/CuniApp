<?php
// database/migrations/2026_03_05_100000_add_verification_tracking_to_naissances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('naissances', function (Blueprint $table) {
            $table->boolean('sex_verified')->default(false)->after('is_archived');
            $table->timestamp('sex_verified_at')->nullable()->after('sex_verified');
            $table->timestamp('first_reminder_sent_at')->nullable()->after('sex_verified_at');
            $table->timestamp('last_reminder_sent_at')->nullable()->after('first_reminder_sent_at');
            $table->integer('reminder_count')->default(0)->after('last_reminder_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('naissances', function (Blueprint $table) {
            $table->dropColumn([
                'sex_verified',
                'sex_verified_at',
                'first_reminder_sent_at',
                'last_reminder_sent_at',
                'reminder_count'
            ]);
        });
    }
};
