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

        if (auth()->check()) {
            if (auth()->user()->language) {
                // Priority 1: Authenticated User Preference
                $locale = auth()->user()->language;
            } else {
                // Priority 2: If no DB pref, check if explicitly chosen in session
                if (Session::get('locale_explicit') && Session::has('locale')) {
                    $locale = Session::get('locale');
                } else {
                    // Priority 3: Auto-detect from browser's Accept-Language header
                    $browserLang = $request->getPreferredLanguage(['fr', 'en']);
                    if ($browserLang) {
                        $locale = $browserLang;
                    }
                }

                // Persist the detected/chosen language to the database
                if (in_array($locale, ['en', 'fr'])) {
                    auth()->user()->update(['language' => $locale]);
                }
            }
        } else {
            // Guest Flow
            if (Session::get('locale_explicit') && Session::has('locale')) {
                // Priority 1: Explicitly chosen via language switcher
                $locale = Session::get('locale');
            } else {
                // Priority 2: Auto-detect from browser's Accept-Language header
                $browserLang = $request->getPreferredLanguage(['fr', 'en']);
                if ($browserLang) {
                    $locale = $browserLang;
                }
            }
        }

        if (in_array($locale, ['en', 'fr'])) {
            App::setLocale($locale);
            view()->share('currentLocale', $locale);
        }

        return $next($request);
    }
}
