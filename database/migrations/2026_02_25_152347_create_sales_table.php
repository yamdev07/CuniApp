<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date_sale')->default(now());
            $table->integer('quantity')->default(1);
            $table->enum('type', ['male', 'female', 'lapereau', 'groupe'])->default('lapereau');
            $table->string('category')->nullable(); // e.g., "5-8 semaines", "reproducteur"
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2)->storedAs('quantity * unit_price');
            $table->string('buyer_name');
            $table->string('buyer_contact')->nullable();
            $table->string('buyer_address')->nullable();
            $table->text('notes')->nullable();
            $table->enum('payment_status', ['paid', 'pending', 'partial'])->default('pending');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sales');
    }
};