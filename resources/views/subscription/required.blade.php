{{-- resources/views/subscription/required.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Abonnement Requis - CuniApp Élevage')

@section('content')
    <div
        style="
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    padding: 40px 20px;
">
        <div class="cuni-card" style="max-width: 600px; width: 100%; text-align: center;">
            <div class="card-body" style="padding: 48px 32px;">
                <div
                    style="
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: rgba(245, 158, 11, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 24px;
            ">
                    <i class="bi bi-lock" style="font-size: 40px; color: var(--accent-orange);"></i>
                </div>

                <h2 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px;">
                    Abonnement Requis
                </h2>

                <p style="color: var(--text-secondary); font-size: 15px; line-height: 1.6; margin-bottom: 32px;">
                    Cette fonctionnalité nécessite un abonnement actif. Veuillez souscrire à un plan pour continuer à
                    utiliser toutes les fonctionnalités de CuniApp Élevage.
                </p>

                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('subscription.plans') }}" class="btn-cuni primary">
                        <i class="bi bi-cart-plus"></i> Voir les abonnements
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-cuni secondary">
                        <i class="bi bi-arrow-left"></i> Retour au tableau de bord
                    </a>
                </div>

                <div
                    style="
                margin-top: 32px;
                padding: 20px;
                background: var(--surface-alt);
                border-radius: var(--radius-lg);
                text-align: left;
            ">
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">
                        <i class="bi bi-info-circle" style="color: var(--primary);"></i>
                        Pourquoi un abonnement ?
                    </h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; color: var(--text-secondary);">
                        <li style="padding: 6px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-check-circle-fill" style="color: var(--accent-green);"></i>
                            <span>Support technique prioritaire</span>
                        </li>
                        <li style="padding: 6px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-check-circle-fill" style="color: var(--accent-green);"></i>
                            <span>Sauvegarde automatique des données</span>
                        </li>
                        <li style="padding: 6px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-check-circle-fill" style="color: var(--accent-green);"></i>
                            <span>Mises à jour régulières</span>
                        </li>
                        <li style="padding: 6px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-check-circle-fill" style="color: var(--accent-green);"></i>
                            <span>Accès à toutes les fonctionnalités</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
