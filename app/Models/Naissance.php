<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Carbon\Carbon;

class Naissance extends Model {
    protected $table = 'naissances';
    
    protected $fillable = [
        'mise_bas_id',
        'poids_moyen_naissance',  // Garder pour moyenne de la portée
        'etat_sante',              // Garder pour état général de la portée
        'observations',
        'date_sevrage_prevue',
        'date_vaccination_prevue',
        'sex_verified',
        'sex_verified_at',
        'first_reminder_sent_at',
        'last_reminder_sent_at',
        'reminder_count',
        'is_archived',
        'archived_at',
    ];

    protected $casts = [
        'sex_verified' => 'boolean',
        'is_archived' => 'boolean',
        'sex_verified_at' => 'datetime',
        'first_reminder_sent_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
        'archived_at' => 'datetime',
        'poids_moyen_naissance' => 'decimal:2',
    ];

    public function miseBas(): BelongsTo {
        return $this->belongsTo(MiseBas::class);
    }

    // ✅ CORRECTION: Get femelle through mise_bas (NOT direct femelle_id)
    public function femelle(): HasOneThrough {
        return $this->hasOneThrough(
            Femelle::class,
            MiseBas::class,
            'id',           // Foreign key on mises_bas table
            'id',           // Foreign key on femelles table
            'mise_bas_id',  // Local key on naissances table
            'femelle_id'    // Local key on mises_bas table
        );
    }

    public function saillie(): HasOneThrough {
        return $this->hasOneThrough(
            Saillie::class,
            MiseBas::class,
            'id',
            'id',
            'mise_bas_id',
            'saillie_id'
        );
    }

    public function lapereaux(): HasMany {
        return $this->hasMany(Lapereau::class);
    }

    // ✅ CALCULATED: Get birth date from mise_bas
    public function getDateNaissanceAttribute(): ?Carbon {
        return $this->miseBas?->date_mise_bas;
    }

    // ✅ CALCULATED: Days since birth
    public function getJoursDepuisNaissanceAttribute(): int {
        if (!$this->date_naissance) return 0;
        return $this->date_naissance->diffInDays(now());
    }

    // ✅ CALCULATED: Can verify sex? (after 10 days)
    public function getCanVerifySexAttribute(): bool {
        return $this->jours_depuis_naissance >= 10;
    }

    // ✅ CALCULATED: Total rabbits
    public function getTotalLapereauxAttribute(): int {
        return $this->lapereaux()->count();
    }

    // ✅ CALCULATED: Living rabbits
    public function getNbVivantAttribute(): int {
        return $this->lapereaux()->where('etat', 'vivant')->count();
    }

    // ✅ CALCULATED: Dead rabbits
    public function getNbMortNeAttribute(): int {
        return $this->lapereaux()->where('etat', 'mort')->count();
    }

    // ✅ CALCULATED: Sold rabbits
    public function getNbVenduAttribute(): int {
        return $this->lapereaux()->where('etat', 'vendu')->count();
    }

    // ✅ CALCULATED: Survival rate
    public function getTauxSurvieAttribute(): float {
        $total = $this->total_lapereaux;
        if ($total === 0) return 0;
        return round(($this->nb_vivant / $total) * 100, 2);
    }

    // ✅ SCOPE: Pending verification
    public function scopePendingVerification($query) {
        return $query->where('sex_verified', false)
            ->where('is_archived', false)
            ->whereHas('miseBas', function($q) {
                $q->where('date_mise_bas', '<=', now()->subDays(10));
            });
    }

    // ✅ SCOPE: Active births
    public function scopeActive($query) {
        return $query->where('is_archived', false);
    }

    // ✅ Mark sex as verified
    public function markSexAsVerified(): void {
        $this->update([
            'sex_verified' => true,
            'sex_verified_at' => now(),
        ]);
    }

    // ✅ VALIDATION: Get max allowed lapereaux from mise_bas
    public function getMaxAllowedLapereauxAttribute(): int {
        if (!$this->miseBas) return 0;
        
        // Get from mise_bas counts (if they exist)
        $vivant = $this->miseBas->nb_vivant ?? 0;
        $mortNe = $this->miseBas->nb_mort_ne ?? 0;
        
        // If mise_bas doesn't have counts, calculate from existing lapereaux
        if ($vivant === 0 && $mortNe === 0) {
            return $this->lapereaux()->count();
        }
        
        return $vivant + $mortNe;
    }
}