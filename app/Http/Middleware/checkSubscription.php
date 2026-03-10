<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Admin users have full access
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        // Check if user has active subscription
        if (!$user->hasActiveSubscription()) {
            // Store intended URL for redirect after subscription
            session(['intended_url' => $request->fullUrl()]);
            
            // Redirect to subscription page
            return redirect()->route('subscription.plans')
                ->with('warning', 'Vous devez avoir un abonnement actif pour effectuer cette action.');
        }
        
        return $next($request);
    }
}