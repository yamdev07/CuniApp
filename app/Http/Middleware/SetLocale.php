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
        if (Auth::check() && in_array(Auth::user()->language, ['fr', 'en'])) {
            App::setLocale(Auth::user()->language);
            view()->share('currentLocale', Auth::user()->language);
            $request->headers->set('Accept-Language', Auth::user()->language);
        } else {
            $browserLang = $request->getPreferredLanguage(['fr', 'en']);
            $locale = $browserLang ?: config('app.locale', 'fr');
            App::setLocale($locale);
            view()->share('currentLocale', $locale);
        }

        return $next($request);
    }
}
