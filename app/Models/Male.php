<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Male extends Model
{
    protected $table = 'males'; // nom de la table
    protected $fillable = ['code', 'nom', 'race', 'origine', 'date_naissance', 'etat'];

    // ✅ ADD THIS: Cast date fields to Carbon instances
    protected $casts = [
        'date_naissance' => 'date',
    ];
}
