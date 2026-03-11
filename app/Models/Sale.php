<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{

    use BelongsToUser;

    protected $fillable = [
        'user_id',
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
        'user_id',
        'total_amount'
    ];

    protected $casts = [
        'date_sale' => 'date',
        'unit_price' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ NEW: Relationship with sold rabbits
    public function rabbits(): HasMany
    {
        return $this->hasMany(SaleRabbit::class);
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

    // ✅ Get sold rabbit IDs by type
    public function getSoldRabbitIdsAttribute(): array
    {
        return [
            'males' => $this->rabbits()->where('rabbit_type', 'male')->pluck('rabbit_id')->toArray(),
            'females' => $this->rabbits()->where('rabbit_type', 'female')->pluck('rabbit_id')->toArray(),
            'lapereaux' => $this->rabbits()->where('rabbit_type', 'lapereau')->pluck('rabbit_id')->toArray(),
        ];
    }
}
