<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Add polymorphic columns if they don't exist
            if (!Schema::hasColumn('notifications', 'notifiable_type')) {
                $table->string('notifiable_type')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('notifications', 'notifiable_id')) {
                $table->unsignedBigInteger('notifiable_id')->nullable()->after('notifiable_type');
            }

            // Add index for polymorphic queries
            if (!Schema::hasIndex('notifications', 'notifications_notifiable_index')) {
                $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['notifiable_type', 'notifiable_id']);
            $table->dropColumn(['notifiable_type', 'notifiable_id']);
        });
    }
};
