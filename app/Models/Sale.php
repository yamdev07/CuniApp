<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'date_sale',
        'quantity',
        'type',
        'category',
        'unit_price',
        'buyer_name',
        'buyer_contact',
        'buyer_address',
        'notes',
        'payment_status',
        'amount_paid',
        'user_id'
    ];

    protected $casts = [
        'date_sale' => 'date',
        'unit_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for remaining balance
    public function getBalanceAttribute(): float
    {
        return $this->total_amount - $this->amount_paid;
    }

    // Scope for paid sales
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Scope for pending payments
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}