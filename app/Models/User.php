<?php namespace App\Models;

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
        return $this->role === 'admin';
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();
    }
}