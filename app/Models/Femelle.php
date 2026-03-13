<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Saillie;
use App\Models\Naissance;
use App\Traits\BelongsToUser;

class Femelle extends Model
{
    use HasFactory;
    use BelongsToUser;

    protected $table = 'femelles';

    protected $fillable = [
        'user_id',
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
        return $this->hasManyThrough(
            Naissance::class,     // Final model we want to access
            MiseBas::class,       // Intermediate model
            'femelle_id',         // Foreign key on mises_bas table (links to femelles)
            'mise_bas_id',        // Foreign key on naissances table (links to mises_bas)
            'id',                 // Local key on femelles table
            'id'                  // Local key on mises_bas table
        );
    }

    // app/Models/Femelle.php (add this)
    public function sales()
    {
        return $this->morphMany(SaleRabbit::class, 'rabbit', 'rabbit_type', 'rabbit_id')
            ->where('rabbit_type', 'female');
    }
}
