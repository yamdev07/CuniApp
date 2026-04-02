<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'notifications_email',
        'notifications_dashboard',
        'theme',
        'language',
        'google_id',
        'google_token',
        'google_refresh_token',
        'role',                      // ✅ Ensure this exists
        'subscription_status',       // ✅ Ensure this exists
        'subscription_ends_at',      // ✅ Ensure this exists
        'firm_id',  // ✅ ADD THIS
        'role',     // ✅ Ensure this includes new roles
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_ends_at' => 'datetime',
            'last_subscription_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relationship: User has many subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    /**
     * ✅ NEW: Relationship for payment transactions
     */
    public function paymentTransactions()
    {
        return $this->hasMany(\App\Models\PaymentTransaction::class);
    }

    /**
     * Proper relationship for eager loading active subscription
     * UPDATED: Now excludes archived subscriptions and uses the model scope
     */
    public function activeSubscriptionRelation()
    {
        return $this->hasOne(\App\Models\Subscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->whereNull('archived_at')
            ->latest('created_at');
    }

    public function hasActiveSubscription(): bool
    {
        // 1. Super Admins and Admins always have full access
        if ($this->isSuperAdmin() || $this->role === 'admin') {
            return true;
        }

        // 2. Check for an active, non-archived subscription
        // If the user has a firm_id, we check the firm's subscription
        $query = \App\Models\Subscription::where('status', 'active')
            ->where('end_date', '>=', now())
            ->whereNull('archived_at');

        if ($this->firm_id) {
            $query->where('firm_id', $this->firm_id);
        } else {
            $query->where('user_id', $this->id);
        }

        return $query->exists();
    }

    public function isSubscribed(): bool
    {
        return $this->hasActiveSubscription();
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'firm_admin', 'admin']);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->whereNull('archived_at') // ✅ EXCLUDE ARCHIVED
            ->first();
    }


    /**
     * ✅ NEW: Get the effective subscription (own or firm's)
     */
    public function getEffectiveSubscriptionAttribute()
    {
        // 1. Direct subscription
        $sub = $this->activeSubscription();
        if ($sub) return $sub;

        // 2. Firm subscription if employee
        if ($this->isEmployee() && $this->firm) {
            return $this->firm->activeSubscription()->first();
        }

        return null;
    }

    /**
     * ✅ NEW: Get the effective plan name
     */
    public function getEffectivePlanNameAttribute(): string
    {
        $sub = $this->effective_subscription;
        if (!$sub) return 'Aucun';

        return ($sub->plan->name ?? 'Plan Inconnu') . ($this->isEmployee() ? ' (Via Entreprise)' : '');
    }


    // Add this method to User model
    public function customNotifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id');
    }

    // ====================================================================
    // NEW RELATIONSHIPS
    // ====================================================================

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'firm_id')->where('role', 'employee');
    }


    // ====================================================================
    // ROLE HELPER METHODS
    // ====================================================================

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isFirmAdmin(): bool
    {
        return $this->role === 'firm_admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) <= 5;
    }

    public function dailyActivities()
    {
        return $this->hasMany(UserDailyActivity::class);
    }

    // ====================================================================
    // FIRM-BASED METHODS
    // ====================================================================

    public function canAddMoreUsers(): bool
    {
        if (!$this->firm || !$this->isFirmAdmin()) {
            return false;
        }

        return $this->firm->can_add_more_users;
    }

    public function getFirmUsagePercentageAttribute(): float
    {
        if (!$this->firm) {
            return 0;
        }

        return $this->firm->usage_percentage;
    }
}
