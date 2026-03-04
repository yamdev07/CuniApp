<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapereau extends Model
{
    use HasFactory;

    protected $table = 'lapereaux';

    protected $fillable = [
        'naissance_id', // ✅ NEW
        'mise_bas_id',
        'sex',          // ✅ NEW
        'nom',          // ✅ NEW
        'code',         // ✅ NEW
        'etat',         // ✅ NEW
        'age_semaines',
        'categorie',
        'alimentation_jour',
        'alimentation_semaine'
    ];

    protected $casts = [
        'date_naissance' => 'date', // If added later
    ];

    public function naissance() { 
        return $this->belongsTo(Naissance::class); 
    }

    public function miseBas() { 
        return $this->belongsTo(MiseBas::class); 
    }
    
    // Auto-generate code if not set
    public static function boot()
    {
        parent::boot();
        static::creating(function ($lapereau) {
            if (empty($lapereau->code)) {
                $lapereau->code = 'LAP-' . strtoupper(uniqid());
            }
        });
    }
}