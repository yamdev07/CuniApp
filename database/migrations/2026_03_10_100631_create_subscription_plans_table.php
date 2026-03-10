<?php
// database/migrations/2026_03_10_000001_create_subscription_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Monthly", "Quarterly"
            $table->integer('duration_months'); // 1, 3, 6, 12
            $table->decimal('price', 10, 2); // Price in FCFA
            $table->boolean('is_active')->default(true);
            $table->string('description')->nullable();
            $table->json('features')->nullable(); // Array of features included
            $table->timestamps();
            
            // Indexes
            $table->index('is_active');
            $table->index('duration_months');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};