<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Firm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ====================================================================
    // RELATIONSHIPS
    // ====================================================================

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc');
    }

    // Breeding Data Relationships
    public function males(): HasMany
    {
        return $this->hasMany(Male::class);
    }

    public function femelles(): HasMany
    {
        return $this->hasMany(Femelle::class);
    }

    public function saillies(): HasMany
    {
        return $this->hasMany(Saillie::class);
    }

    public function misesBas(): HasMany
    {
        return $this->hasMany(MiseBas::class);
    }

    public function naissances(): HasMany
    {
        return $this->hasMany(Naissance::class);
    }

    public function lapereaux(): HasMany
    {
        return $this->hasMany(Lapereau::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    // ====================================================================
    // ACCESSORS & ATTRIBUTES
    // ====================================================================

    public function getActiveUsersCountAttribute(): int
    {
        return $this->users()
            ->where('role', '!=', 'super_admin')
            ->where('status', 'active')
            ->count();
    }

    public function getSubscriptionLimitAttribute(): int
    {
        $subscription = $this->activeSubscription()->first();
        if (!$subscription || !$subscription->plan) {
            return 5; // Default limit
        }
        return $subscription->plan->max_users ?? 5;
    }

    public function getCanAddMoreUsersAttribute(): bool
    {
        return $this->active_users_count < $this->subscription_limit;
    }

    public function getRemainingUsersAttribute(): int
    {
        return max(0, $this->subscription_limit - $this->active_users_count);
    }

    // ====================================================================
    // SCOPES
    // ====================================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBanned($query)
    {
        return $query->where('status', 'banned');
    }

    // ====================================================================
    // HELPER METHODS
    // ====================================================================

    public function canAddMoreUsers(): bool
    {
        return $this->can_add_more_users;
    }

    public function getUsagePercentageAttribute(): float
    {
        if ($this->subscription_limit === 0) {
            return 0;
        }

        return round(($this->active_users_count / $this->subscription_limit) * 100, 2);
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // ====================================================================
    // STATISTICS
    // ====================================================================

    public function getTotalRevenueAttribute(): float
    {
        return $this->sales()
            ->where('payment_status', 'paid')
            ->sum('total_amount');
    }

    public function getTotalMalesAttribute(): int
    {
        return $this->males()->count();
    }

    public function getTotalFemellesAttribute(): int
    {
        return $this->femelles()->count();
    }

    public function getTotalSailliesAttribute(): int
    {
        return $this->saillies()->count();
    }

    public function getTotalNaissancesAttribute(): int
    {
        return $this->naissances()->count();
    }
}
    