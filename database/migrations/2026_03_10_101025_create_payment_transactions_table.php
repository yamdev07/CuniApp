<?php
// database/migrations/2026_03_10_000003_create_payment_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['momo', 'celtis', 'moov', 'manual']);
            $table->string('transaction_id')->unique();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->json('provider_response')->nullable(); // Store full API response
            $table->string('provider')->nullable(); // mtn, celtis, moov
            $table->string('phone_number')->nullable(); // For mobile money
            $table->text('failure_reason')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->datetime('refunded_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('payment_method');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};