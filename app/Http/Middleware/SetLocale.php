<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set application locale based on user preference
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fixed to properly handle language setting
        if (Auth::check() && in_array(Auth::user()->language, ['fr', 'en'])) {
            App::setLocale(Auth::user()->language);
            view()->share('currentLocale', Auth::user()->language);
            // Also set HTML lang attribute for proper accessibility
            $request->headers->set('Accept-Language', Auth::user()->language);
        } else {
            $default = config('app.locale', 'fr');
            App::setLocale($default);
            view()->share('currentLocale', $default);
        }

        return $next($request);
    }
}
