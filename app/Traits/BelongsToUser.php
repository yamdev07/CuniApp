<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivityNotificationMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait BelongsToUser
{
    protected static function bootBelongsToUser()
    {
        // Auto-assign user_id on create
        static::creating(function ($model) {
            if (auth()->check() && !$model->user_id) {
                $model->user_id = auth()->id();
            }
        });

        // Auto-filter queries by user_id
        static::addGlobalScope('user', function ($builder) {
            if (auth()->check()) {
                if (!auth()->user()->isAdmin()) {
                    // ✅ FIX: Use table-qualified column name to avoid ambiguity in JOINs
                    $table = $builder->getModel()->getTable();
                    $builder->where("{$table}.user_id", auth()->id());
                } else {
                    // ✅ ADMIN AUDIT LOGGING
                    \Log::channel('audit')->info('Admin Data Access', [
                        'admin_id' => auth()->id(),
                        'model' => get_class($builder->getModel()),
                        'timestamp' => now(),
                        'ip' => request()->ip()
                    ]);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
