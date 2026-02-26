<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    Password::defaults(function () {
        return Password::min(8)
            ->letters()          // Doit contenir des lettres
            ->mixedCase()        // Doit contenir Majuscules + Minuscules
            ->numbers()          // Doit contenir des chiffres
            ->symbols() ;         // Doit contenir des caractères spéciaux (@, $, !, etc.)
    });
}
}
