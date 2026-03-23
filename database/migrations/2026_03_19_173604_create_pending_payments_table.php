<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // database/migrations/2026_03_20_000000_create_pending_payments_table.php
        Schema::create('pending_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique(); // FedaPay Transaction ID
            $table->string('type')->default('subscription'); // subscription, sale, etc.
            $table->json('data'); // Store all payment data
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_payments');
    }
};
