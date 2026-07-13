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
        } elseif (Session::get('locale_explicit') && Session::has('locale')) {
            // Priority 2: Explicitly chosen via language switcher
            $locale = Session::get('locale');
        } else {
            // Priority 3: Auto-detect from browser's Accept-Language header
            $browserLang = $request->getPreferredLanguage(['fr', 'en']);
            if ($browserLang) {
                $locale = $browserLang;
            }
        }

        if (in_array($locale, ['en', 'fr'])) {
            App::setLocale($locale);
            view()->share('currentLocale', $locale);
        }

        return $next($request);
    }
}
