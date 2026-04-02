<?php
// app/Http/Middleware/EnsureUserHasFirm.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasFirm
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->firm_id) {
            // ✅ Only redirect to setup if not already on the setup page
            if (!$request->routeIs('firm.setup') && !$request->routeIs('firm.setup.store') && !$request->routeIs('logout')) {
                return redirect()->route('firm.setup');
            }
        }

        return $next($request);
    }
}
