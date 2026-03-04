<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Lapereau extends Model {
    protected $table = 'lapereaux';

    protected $fillable = [
        'naissance_id',
        'code',
        'nom',
        'sex',
        'etat',
        'categorie',
        'alimentation_jour',
        'alimentation_semaine',
    ];

    protected $casts = [
        'sex' => 'string',
        'etat' => 'string',
    ];

    public function naissance(): BelongsTo {
        return $this->belongsTo(Naissance::class);
    }

    public function miseBas(): HasOneThrough {
        return $this->hasOneThrough(MiseBas::class, Naissance::class, 'id', 'id', 'naissance_id', 'mise_bas_id');
    }

    public function femelle(): HasOneThrough {
        return $this->hasOneThrough(Femelle::class, MiseBas::class, 'id', 'id', 'naissance_id', 'femelle_id');
    }

    public function saillie(): HasOneThrough {
        return $this->hasOneThrough(Saillie::class, MiseBas::class, 'id', 'id', 'naissance_id', 'saillie_id');
    }

    // ✅ AUTO-GENERATE CODE (like Males/Femelles)
    public static function boot() {
        parent::boot();
        
        static::creating(function ($lapereau) {
            if (empty($lapereau->code)) {
                $lapereau->code = self::generateUniqueCode();
            }
        });
    }

    // ✅ Generate unique code: LAP-YYYY-XXXX
    public static function generateUniqueCode(): string {
        $year = date('Y');
        $prefix = "LAP-{$year}-";
        
        $lastLapereau = self::where('code', 'LIKE', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->first();
        
        if ($lastLapereau) {
            $lastNumber = intval(substr($lastLapereau->code, -4));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // ✅ Get birth date from naissance
    public function getDateNaissanceAttribute(): ?\Carbon\Carbon {
        return $this->naissance?->date_naissance;
    }

    // ✅ Get age in weeks
    public function getAgeSemainesAttribute(): int {
        if (!$this->date_naissance) return 0;
        return floor($this->date_naissance->diffInDays(now()) / 7);
    }
}