<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirmAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isFirmAdmin()) {
            abort(403, 'Accès réservé aux administrateurs de l\'entreprise.');
        }

        // Check if firm is banned
        if ($request->user()->firm && $request->user()->firm->isBanned()) {
            auth()->logout();
            return redirect()->route('welcome')
                ->withErrors(['error' => 'Votre entreprise a été suspendue. Contactez le support.']);
        }

        return $next($request);
    }
}
