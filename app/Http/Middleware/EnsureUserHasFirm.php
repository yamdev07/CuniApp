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
            return redirect()->route('profile.edit')
                ->withErrors(['error' => 'Votre compte doit être associé à une entreprise. Contactez le support.']);
        }

        return $next($request);
    }
}
