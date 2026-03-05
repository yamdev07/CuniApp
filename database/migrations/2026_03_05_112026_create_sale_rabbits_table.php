// database/migrations/2026_03_06_000000_create_sale_rabbits_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_rabbits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('rabbit_type'); // 'male', 'female', 'lapereau'
            $table->foreignId('rabbit_id'); // ID from males, femelles, or lapereaux table
            $table->decimal('sale_price', 10, 2)->nullable(); // Individual sale price
            $table->timestamps();
            
            $table->index(['sale_id', 'rabbit_type']);
            $table->unique(['sale_id', 'rabbit_type', 'rabbit_id'], 'unique_sale_rabbit');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_rabbits');
    }
};