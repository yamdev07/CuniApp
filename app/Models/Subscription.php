<?php
// app/Models/Subscription.php
namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Subscription extends Model
{
    use BelongsToUser;
    use HasFactory, SoftDeletes;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'start_date',
        'end_date',
        'cancelled_at',
        'price',
        'payment_method',
        'transaction_id',
        'payment_reference',
        'auto_renew',
        'cancellation_reason',
        'archived_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'cancelled_at' => 'datetime',
                'archived_at' => 'datetime', 
        'price' => 'decimal:2',
        'auto_renew' => 'boolean',

    ];

    /**
     * Relationship: Subscription belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Subscription belongs to Plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Relationship: Subscription has many transactions
     */
    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Scope: Active subscriptions only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>=', now())
            ->whereNull('archived_at'); 
    }

    /**
     * Scope: Expiring soon (within X days)
     */
public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays($days)])
            ->whereNull('archived_at'); // <-- On exclut aussi les archivés ici
    }

    /**
     * Check if subscription is currently active
     */
  public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->end_date?->isFuture()
            && $this->archived_at === null; // <-- Vérification ajoutée
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->end_date) return null;
        return max(0, Carbon::parse($this->end_date)->diffInDays(now(), false));
    }

    /**
     * Check if expired
     */
    public function isExpired(): bool
    {
        return $this->end_date?->isPast()
            && !in_array($this->status, ['active', 'grace_period']);
    }

    /**
     * Format price for display
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }


    // Scope pour ne prendre que les archivés
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

}
