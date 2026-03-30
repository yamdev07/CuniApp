<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiseBas extends Model
{

    use BelongsToUser;
    protected $table = 'mises_bas';

    protected $fillable = [
        'user_id',
        'firm_id',
        'femelle_id',
        'saillie_id',
        'date_mise_bas',
        'date_sevrage',
        'poids_moyen_sevrage',
    ];

    protected $casts = [
        'date_mise_bas' => 'date',
        'date_sevrage' => 'date',
        'poids_moyen_sevrage' => 'decimal:2',
    ];

    public function femelle(): BelongsTo
    {
        return $this->belongsTo(Femelle::class);
    }

    public function saillie(): BelongsTo
    {
        return $this->belongsTo(Saillie::class);
    }

    public function naissances(): HasMany
    {
        return $this->hasMany(Naissance::class);
    }

    public function lapereaux(): HasManyThrough
    {
        return $this->hasManyThrough(Lapereau::class, Naissance::class);
    }

    // ✅ CALCULATED: Total rabbits from this birth
    public function getTotalLapereauxAttribute(): int
    {
        return $this->lapereaux()->count();
    }

    // ✅ CALCULATED: Living rabbits
    public function getNbVivantAttribute(): int
    {
        return $this->lapereaux()->where('etat', 'vivant')->count();
    }

    // ✅ CALCULATED: Dead rabbits
    public function getNbMortNeAttribute(): int
    {
        return $this->lapereaux()->where('etat', 'mort')->count();
    }

    // ✅ CALCULATED: Sold rabbits
    public function getNbVenduAttribute(): int
    {
        return $this->lapereaux()->where('etat', 'vendu')->count();
    }
}
