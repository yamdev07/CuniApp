<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        // Skip in local development
        if (app()->environment('local')) {
            return $next($request);
        }

        // Force HTTPS
        if (!$request->secure() && config('app.force_https', false)) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
