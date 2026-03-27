<?php
// app/Models/FirmAuditLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FirmAuditLog extends Model
{
    protected $fillable = [
        'firm_id',
        'user_id',
        'action',
        'field',
        'old_value',
        'new_value',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log($firmId, $userId, $action, $field = null, $oldValue = null, $newValue = null)
    {
        return self::create([
            'firm_id' => $firmId,
            'user_id' => $userId,
            'action' => $action,
            'field' => $field,
            'old_value' => $oldValue ? json_encode($oldValue) : null,
            'new_value' => $newValue ? json_encode($newValue) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
