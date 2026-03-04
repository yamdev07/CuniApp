<?php
// app/Models/Naissance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'nb_vivant', // Now calculated from children
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
        // nb_mort_ne removed
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

    // ✅ NEW: Relationship to individual rabbits
    public function lapereaux(): HasMany
    {
        return $this->hasMany(Lapereau::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    // ... (Keep existing scopes like PendingVerification, etc.)
    public function scopePendingVerification($query)
    {
        return $query->where('sex_verified', false)->where('is_archived', false);
    }

    // ✅ UPDATED: Calculate nb_vivant based on children if not set
    public function getNbVivantAttribute()
    {
        // If explicitly set, use it. Otherwise count alive children.
        if ($this->attributes['nb_vivant'] > 0) {
            return $this->attributes['nb_vivant'];
        }
        return $this->lapereaux()->where('etat', 'vivant')->count();
    }

    // ✅ UPDATED: Taux de survie based on children
    public function getTauxSurvieAttribute(): float
    {
        $total = $this->lapereaux()->count();
        if ($total == 0) return 0;
        $vivants = $this->lapereaux()->where('etat', 'vivant')->count();
        return round(($vivants / $total) * 100, 2);
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

    // ... (Keep other existing methods)
    public function markSexAsVerified(): void
    {
        $this->update(['sex_verified' => true, 'sex_verified_at' => now()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date_naissance', now()->month)
            ->whereYear('date_naissance', now()->year);
    }
}
