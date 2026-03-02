<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'heure_naissance' => 'datetime:H:i',
        'date_sevrage_prevue' => 'date',
        'date_vaccination_prevue' => 'date',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean',
        'nb_vivant' => 'integer',
        'nb_mort_ne' => 'integer',
        'nb_sevre' => 'integer',
        'poids_moyen_naissance' => 'decimal:2',
        'poids_total_portee' => 'decimal:2',
    ];

    // Relations
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeByHealthStatus($query, $status)
    {
        return $query->where('etat_sante', $status);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('date_naissance', now()->year)
                    ->whereMonth('date_naissance', now()->month);
    }

    // Accessors
    public function getTauxSurvieAttribute(): float
    {
        if ($this->nb_total == 0) return 0;
        return round(($this->nb_vivant / $this->nb_total) * 100, 2);
    }

    public function getJoursAvantSevrageAttribute(): int
    {
        if (!$this->date_sevrage_prevue) return 0;
        return max(0, $this->date_sevrage_prevue->diffInDays(now()));
    }
}