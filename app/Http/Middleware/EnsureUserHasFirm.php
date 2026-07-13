<?php
// app/Http/Middleware/EnsureUserHasFirm.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasFirm
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && !$user->firm_id) {
            if ($user->isSuperAdmin()) {
                return $next($request);
            }

            if (!$request->routeIs('firm.setup') && !$request->routeIs('firm.setup.store') && !$request->routeIs('logout')) {
                return redirect()->route('firm.setup');
            }
        }

        return $next($request);
    }
}
