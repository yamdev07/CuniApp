<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SaleRabbit extends Model 
{
    protected $fillable = ['sale_id', 'rabbit_type', 'rabbit_id', 'sale_price'];

    // ✅ Use proper polymorphic relationship
    public function rabbit(): MorphTo
    {
        return $this->morphTo('rabbit', 'rabbit_type', 'rabbit_id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}