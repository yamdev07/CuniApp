<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetAppLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale');

        if (auth()->check() && auth()->user()->language) {
            // Priority 1: Authenticated User Preference
            $locale = auth()->user()->language;
        } elseif (Session::has('locale')) {
            // Priority 2: Session (for guests or before login)
            $locale = Session::get('locale');
        }

        if (in_array($locale, ['en', 'fr'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
