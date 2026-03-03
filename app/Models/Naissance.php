<?php
// app/Models/Naissance.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Naissance extends Model
{
    use HasFactory;

    protected $table = 'naissances';

    protected $fillable = [
        'femelle_id',
        'saillie_id',
        'mise_bas_id',
        'date_naissance',
        'heure_naissance',
        'lieu_naissance',
        'nb_vivant',
        'nb_mort_ne',
        'nb_sevre',
        'poids_moyen_naissance',
        'poids_total_portee',
        'etat_sante',
        'observations',
        'date_sevrage_prevue',
        'date_vaccination_prevue',
        'user_id',
        'is_archived',
        'archived_at',
        'sex_verified',
        'sex_verified_at',
        'first_reminder_sent_at',
        'last_reminder_sent_at',
        'reminder_count',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'heure_naissance' => 'datetime:H:i',
        'date_sevrage_prevue' => 'date',
        'date_vaccination_prevue' => 'date',
        'archived_at' => 'datetime',
        'sex_verified_at' => 'datetime',
        'first_reminder_sent_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
        'is_archived' => 'boolean',
        'sex_verified' => 'boolean',
        'nb_vivant' => 'integer',
        'nb_mort_ne' => 'integer',
        'nb_sevre' => 'integer',
        'poids_moyen_naissance' => 'decimal:2',
        'poids_total_portee' => 'decimal:2',
        'reminder_count' => 'integer',
    ];

    public function femelle(): BelongsTo
    {
        return $this->belongsTo(Femelle::class);
    }

    public function saillie(): BelongsTo
    {
        return $this->belongsTo(Saillie::class);
    }

    public function miseBas(): BelongsTo
    {
        return $this->belongsTo(MiseBas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopePendingVerification($query)
    {
        return $query->where('sex_verified', false)
            ->where('is_archived', false);
    }

    public function scopeNeedsInitialReminder($query, $days = 15)
    {
        return $query->where('sex_verified', false)
            ->where('is_archived', false)
            ->where('first_reminder_sent_at', null)
            ->where('date_naissance', '<=', Carbon::now()->subDays($days));
    }

    public function scopeNeedsFollowupReminder($query, $interval = 5)
    {
        return $query->where('sex_verified', false)
            ->where('is_archived', false)
            ->whereNotNull('first_reminder_sent_at')
            ->where('last_reminder_sent_at', '<=', Carbon::now()->subDays($interval));
    }

    public function getTauxSurvieAttribute(): float
    {
        $total = $this->nb_vivant + $this->nb_mort_ne;
        if ($total == 0) return 0;
        return round(($this->nb_vivant / $total) * 100, 2);
    }

    public function getJoursAvantSevrageAttribute(): int
    {
        if (!$this->date_sevrage_prevue) return 0;
        return max(0, $this->date_sevrage_prevue->diffInDays(now()));
    }

    public function getJoursDepuisNaissanceAttribute(): int
    {
        return $this->date_naissance->diffInDays(now());
    }

    public function getVerificationStatusAttribute(): string
    {
        if ($this->sex_verified) {
            return 'verified';
        } elseif (!$this->first_reminder_sent_at) {
            return 'pending';
        } else {
            return 'overdue';
        }
    }

    public function markSexAsVerified(): void
    {
        $this->update([
            'sex_verified' => true,
            'sex_verified_at' => now(),
        ]);
    }

    /**
     * Scope a query to only include records from the current month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereYear('date_naissance', now()->year)
            ->whereMonth('date_naissance', now()->month);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('date_naissance', now()->year);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereYear('date_naissance', now()->subMonth()->year)
            ->whereMonth('date_naissance', now()->subMonth()->month);
    }

    public function scopeWhereDateRange($query, $start, $end)
    {
        return $query->whereBetween('date_naissance', [$start, $end]);
    }
}
