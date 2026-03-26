<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['active', 'banned', 'suspended'])->default('active');
            $table->timestamps();

            // Indexes for performance
            $table->index('status');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firms');
    }
};
