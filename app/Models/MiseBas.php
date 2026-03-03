<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiseBas extends Model
{
    use HasFactory;

    // Nom de la table
    protected $table = 'mises_bas';

    protected $fillable = [
        'femelle_id',
        'saillie_id',
        'date_mise_bas',
        'nb_vivant',
        'nb_mort_ne',
        'nb_retire',
        'nb_adopte',
        'date_sevrage',
        'poids_moyen_sevrage'
    ];

    /**
     * Relation : une mise bas appartient à une saillie
     */
    public function saillie()
    {
        return $this->belongsTo(Saillie::class);
    }

    /**
     * Relation : une mise bas a plusieurs lapereaux
     */
    public function lapereaux()
    {
        return $this->hasMany(Lapereau::class);
    }

    /**
     * Relation directe : une mise bas est liée à une femelle (via la saillie)
     */
    /**
     * Relation directe : une mise bas appartient à une femelle
     */
    public function femelle()
    {
        return $this->belongsTo(Femelle::class);
    }
}
