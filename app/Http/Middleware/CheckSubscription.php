<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Subscription;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. SUPER ADMIN : Accès total illimité (Pas de vérification)
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // 2. VÉRIFICATION MULTI-TENANT (Basée sur la FIRME)
        if (!$user->firm_id) {
            // Cas edge : Utilisateur sans firme (sauf super admin)
            abort(403, 'Compte non rattaché à une entreprise. Veuillez contacter le support.');
        }

        // On cherche un abonnement ACTIF pour cette FIRME (et non plus juste pour l'user)
        $hasValidAccess = Subscription::where('firm_id', $user->firm_id)
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->exists();

        if (!$hasValidAccess) {
            $message = 'Votre période d\'essai est terminée ou aucun abonnement n\'est actif.';
            
            if ($user->isEmployee()) {
                $message = "L'abonnement de votre entreprise (" . $user->firm->name . ") est expiré. Veuillez contacter votre administrateur.";
            }

            session(['intended_url' => $request->fullUrl()]);
            
            return redirect()->route('subscription.plans')
                ->with('warning', $message);
        }

        return $next($request);
    }
}