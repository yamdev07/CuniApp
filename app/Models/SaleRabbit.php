<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleRabbit extends Model {
    protected $fillable = ['sale_id', 'rabbit_type', 'rabbit_id', 'sale_price'];

    public function sale(): BelongsTo {
        return $this->belongsTo(Sale::class);
    }

    public function rabbit() {
        return match($this->rabbit_type) {
            'male' => $this->belongsTo(Male::class, 'rabbit_id'),
            'female' => $this->belongsTo(Femelle::class, 'rabbit_id'),
            'lapereau' => $this->belongsTo(Lapereau::class, 'rabbit_id'),
            default => null,
        };
    }
}