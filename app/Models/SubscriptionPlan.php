<?php
// app/Models/SubscriptionPlan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'duration_months',
        'price',
        'is_active',
        'description',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Get active plans only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get plans by duration
     */
    public function scopeByDuration($query, int $months)
    {
        return $query->where('duration_months', $months);
    }

    /**
     * Relationship: Plan has many subscriptions
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Format price for display
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Get duration label
     */
    public function getDurationLabelAttribute(): string
    {
        return match ($this->duration_months) {
            1 => 'Mensuel',
            3 => 'Trimestriel',
            6 => 'Semestriel',
            12 => 'Annuel',
            default => "{$this->duration_months} mois",
        };
    }
}