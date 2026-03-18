<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation; // ← Add this import
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production' || str_contains(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
        // Password defaults (your existing code)
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();
        });

        // ✅ ADD THIS: MorphMap for polymorphic relationships
        Relation::morphMap([
            'male' => \App\Models\Male::class,
            'female' => \App\Models\Femelle::class,  // Note: Your model is Femelle
            'lapereau' => \App\Models\Lapereau::class,
        ]);
    }
}
