<?php
// database/migrations/2026_03_10_000002_create_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->onDelete('restrict');
            $table->enum('status', ['active', 'expired', 'cancelled', 'pending', 'grace_period'])->default('pending');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('cancelled_at')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('payment_method', ['momo', 'celtis', 'moov', 'manual'])->nullable();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('payment_reference')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Keep records for audit
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('end_date');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};