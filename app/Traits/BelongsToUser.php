<?php
// app/Traits/BelongsToUser.php
namespace App\Traits;

trait BelongsToUser
{
    protected static function bootBelongsToUser()
    {
        // Auto-assign user_id and firm_id on creation
        static::creating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();

                if (!$model->user_id) {
                    $model->user_id = $user->id;
                }

                // CRITICAL: Only set firm_id if user has one
                if ($user->firm_id && !$model->firm_id) {
                    $model->firm_id = $user->firm_id;
                } elseif (!$model->firm_id) {
                    // Fallback: try to get firm from user relationship
                    $model->firm_id = $user->firm?->id;
                }
            }
        });

        // Global Scope for data isolation
        static::addGlobalScope('firm', function ($builder) {
            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();
            $modelTable = $builder->getModel()->getTable();

            // Super Admin sees all
            if ($user->isSuperAdmin()) {
                return;
            }

            // Firm Admin/Employee: scope by firm_id
            if ($user->firm_id && in_array($user->role, ['firm_admin', 'employee'])) {
                $builder->where("{$modelTable}.firm_id", $user->firm_id);
            }
            // Fallback: scope by user_id only (for users without firm)
            elseif (auth()->id()) {
                $builder->where("{$modelTable}.user_id", auth()->id());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function firm()
    {
        return $this->belongsTo(\App\Models\Firm::class);
    }
}
