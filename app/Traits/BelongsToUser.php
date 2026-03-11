<?php

namespace App\Traits;

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
            if (auth()->check() && !auth()->user()->isAdmin()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
