<?php

namespace App\Traits;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivityNotificationMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;



trait BelongsToUser
{
    protected static function bootBelongsToUser()
    {
        // Auto-assign user_id AND firm_id on create
        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();

                if (!$model->user_id) {
                    $model->user_id = $user->id;
                }

                // ✅ NEW: Auto-assign firm_id for employees/firm_admins
                if ($user->firm_id && !$model->firm_id) {
                    $model->firm_id = $user->firm_id;
                }
            }
        });

        // Auto-filter queries by firm_id (multi-tenancy) OR user_id (legacy)
        static::addGlobalScope('firm', function ($builder) {
            if (auth()->check()) {
                $user = auth()->user();

                // Super Admin sees all data
                if ($user->isSuperAdmin()) {
                    Log::channel('audit')->info('Super Admin Data Access', [
                        'admin_id' => $user->id,
                        'model' => get_class($builder->getModel()),
                        'timestamp' => now(),
                    ]);
                    return;
                }

                // ✅ FIRM-BASED SCOPING (Multi-Tenancy)
                if ($user->firm_id && in_array($user->role, ['firm_admin', 'employee'])) {
                    $table = $builder->getModel()->getTable();
                    if (Schema::hasColumn($table, 'firm_id')) {
                        $builder->where("{$table}.firm_id", $user->firm_id);
                    }
                }
                // ✅ USER-BASED SCOPING (Legacy/Fallback)
                else {
                    $table = $builder->getModel()->getTable();
                    if (Schema::hasColumn($table, 'user_id')) {
                        $builder->where("{$table}.user_id", $user->id);
                    }
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // ✅ NEW: Firm relationship for models with firm_id
    public function firm()
    {
        return $this->belongsTo(\App\Models\Firm::class);
    }
}
