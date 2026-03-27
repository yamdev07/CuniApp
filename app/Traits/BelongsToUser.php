<?php

namespace App\Traits;

trait BelongsToUser
{

    protected static function bootBelongsToUser()
    {
        // Auto-assign user_id et firm_id à la création
        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                if (!$model->user_id) {
                    $model->user_id = $user->id;
                }
                if ($user->firm_id && !$model->firm_id) {
                    $model->firm_id = $user->firm_id;
                }
            }
        });

        // ✅ Global Scope FIX: Use explicit table names to avoid ambiguity
        static::addGlobalScope('firm', function ($builder) {
            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();
            $modelTable = $builder->getModel()->getTable(); // ← Get the main model's table

            // Super Admin voit tout
            if ($user->isSuperAdmin()) {
                return;
            }

            // Employer/Firm Admin : scope par firm_id
            if ($user->firm_id && in_array($user->role, ['firm_admin', 'employee'])) {
                // ✅ Explicitly specify table name
                $builder->where("{$modelTable}.firm_id", $user->firm_id);
            }
            // Fallback : scope par user_id
            elseif (auth()->id()) {
                // ✅ Explicitly specify table name to avoid ambiguity in JOINs
                $builder->where("{$modelTable}.user_id", auth()->id());
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
