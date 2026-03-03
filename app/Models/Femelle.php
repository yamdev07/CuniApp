<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Saillie;
use App\Models\Naissance;

class Femelle extends Model
{
    use HasFactory;

    protected $table = 'femelles';

    protected $fillable = [
        'code',
        'nom',
        'race',
        'origine',
        'date_naissance',
        'etat'  // ✅ ADD THIS
    ];

    // ✅ ADD THIS: Cast date fields to Carbon instances
    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function saillies()
    {
        return $this->hasMany(Saillie::class);
    }

    public function naissances()
    {
        return $this->hasMany(Naissance::class);
    }
}
