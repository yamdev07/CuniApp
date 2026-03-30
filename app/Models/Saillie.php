<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Saillie extends Model
{
    use BelongsToUser;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firm_id',
        'femelle_id',
        'male_id',
        'date_saillie',
        'date_palpage',
        'palpation_resultat',
        'date_mise_bas_theorique'
    ];

    // ✅ ADD THIS: Cast date fields to Carbon instances
    protected $casts = [
        'date_saillie' => 'date',
        'date_palpage' => 'date',
        'date_mise_bas_theorique' => 'date',
    ];

    public function femelle()
    {
        return $this->belongsTo(Femelle::class);
    }

    public function male()
    {
        return $this->belongsTo(Male::class);
    }

    public function misesBas()
    {
        return $this->hasMany(MiseBas::class);
    }
}
