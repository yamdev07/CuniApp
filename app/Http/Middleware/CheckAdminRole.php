<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || ($request->user()->role !== 'admin' && !$request->user()->isSuperAdmin())) {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        
        return $next($request);
    }
}