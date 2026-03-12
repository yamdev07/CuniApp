<?php
// app/Models/Invoice.php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory, SoftDeletes, BelongsToUser;

    protected $table = 'invoices';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'payment_transaction_id',
        'invoice_number',
        'invoice_type',
        'amount',
        'tax_amount',
        'total_amount',
        'currency',
        'status',
        'invoice_date',
        'due_date',
        'paid_at',
        'pdf_path',
        'pdf_generated',
        'pdf_generated_at',
        'billing_details',
        'line_items',
        'notes',
        'payment_method',
        'transaction_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_details' => 'array',
        'line_items' => 'array',
        'pdf_generated' => 'boolean',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'pdf_generated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month);
    }

    // Accessors
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getFormattedTaxAttribute(): string
    {
        return number_format($this->tax_amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    // Generate unique invoice number
    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $prefix = "INV-{$year}-";

        $lastInvoice = self::where('invoice_number', 'LIKE', "{$prefix}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -5));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    // Mark as paid
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    // Check if overdue
    public function isOverdue(): bool
    {
        return $this->status === 'pending'
            && $this->due_date
            && $this->due_date->isPast();
    }
}
