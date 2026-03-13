<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToUser;

class Notification extends Model {
    use BelongsToUser; 

    protected $fillable = [
        'user_id', 
        'type',
        'title',
        'message',
        'action_url',
        'icon',
        'is_read',
        'emailed',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'emailed' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }
}