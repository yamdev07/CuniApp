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
     */
    public function activeSubscriptionRelation()
    {
        return $this->hasOne(\App\Models\Subscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest('created_at');
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->exists();
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
            ->first();
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
