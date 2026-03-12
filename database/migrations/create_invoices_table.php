<?php
// database/migrations/2026_03_15_000000_create_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->foreignId('payment_transaction_id')->nullable()->constrained('payment_transactions')->onDelete('set null');
            
            // Invoice Details
            $table->string('invoice_number')->unique(); // Format: INV-2026-00001
            $table->string('invoice_type')->default('subscription'); // subscription, payment, refund
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('XOF');
            
            // Status
            $table->enum('status', ['draft', 'paid', 'pending', 'cancelled', 'refunded'])->default('pending');
            
            // Dates
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // PDF Storage
            $table->string('pdf_path')->nullable();
            $table->boolean('pdf_generated')->default(false);
            $table->timestamp('pdf_generated_at')->nullable();
            
            // Billing Information
            $table->json('billing_details')->nullable(); // Customer info snapshot
            $table->json('line_items')->nullable(); // Invoice items
            
            // Metadata
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_reference')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('invoice_number');
            $table->index('user_id');
            $table->index('status');
            $table->index('invoice_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};