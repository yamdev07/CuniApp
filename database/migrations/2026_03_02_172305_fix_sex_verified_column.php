<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('naissances', function (Blueprint $table) {
            if (!Schema::hasColumn('naissances', 'sex_verified')) {
                $table->boolean('sex_verified')->default(false)->after('is_archived');
            }
            if (!Schema::hasColumn('naissances', 'sex_verified_at')) {
                $table->timestamp('sex_verified_at')->nullable()->after('sex_verified');
            }
            if (!Schema::hasColumn('naissances', 'first_reminder_sent_at')) {
                $table->timestamp('first_reminder_sent_at')->nullable()->after('sex_verified_at');
            }
            if (!Schema::hasColumn('naissances', 'last_reminder_sent_at')) {
                $table->timestamp('last_reminder_sent_at')->nullable()->after('first_reminder_sent_at');
            }
            if (!Schema::hasColumn('naissances', 'reminder_count')) {
                $table->integer('reminder_count')->default(0)->after('last_reminder_sent_at');
            }
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
