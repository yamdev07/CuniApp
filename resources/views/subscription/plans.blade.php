{{-- resources/views/subscription/plans.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Abonnements - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-credit-card"></i> Choisir un Abonnement
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Abonnements</span>
        </div>
    </div>
</div>

@if(session('warning'))
<div class="alert-cuni warning">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>{{ session('warning') }}</div>
</div>
@endif

@if(session('error'))
<div class="alert-cuni error">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>{{ session('error') }}</div>
</div>
@endif

{{-- Current Subscription Status --}}
@if($currentSubscription && $currentSubscription->isActive())
<div class="cuni-card mb-6" style="border-left: 4px solid var(--accent-green);">
    <div class="card-body">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-check-circle-fill" style="font-size: 24px; color: var(--accent-green);"></i>
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 16px; font-weight: 600; color: var(--text-primary);">Abonnement Actif</h3>
                <p style="font-size: 13px; color: var(--text-secondary);">
                    Plan: <strong>{{ $currentSubscription->plan->name }}</strong> • 
                    Expire le: <strong>{{ $currentSubscription->end_date->format('d/m/Y') }}</strong> • 
                    Jours restants: <strong>{{ $currentSubscription->days_remaining }}</strong>
                </p>
            </div>
            <a href="{{ route('subscription.status') }}" class="btn-cuni secondary">
                <i class="bi bi-eye"></i> Voir les détails
            </a>
        </div>
    </div>
</div>
@endif

{{-- Plans Grid --}}
<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-stars"></i> Nos Offres d'Abonnement
        </h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            @foreach($plans as $plan)
            <div class="plan-card" style="
                background: var(--surface-alt);
                border: 2px solid {{ $plan->duration_months === 12 ? 'var(--primary)' : 'var(--surface-border)' }};
                border-radius: var(--radius-lg);
                padding: 24px;
                position: relative;
                transition: all 0.3s ease;
            " onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-md)'" 
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                
                @if($plan->duration_months === 12)
                <div style="
                    position: absolute;
                    top: -12px;
                    right: 24px;
                    background: var(--accent-orange);
                    color: white;
                    padding: 4px 12px;
                    border-radius: 20px;
                    font-size: 11px;
                    font-weight: 600;
                ">
                    ⭐ MEILLEURE OFFRE
                </div>
                @endif
                
                <div style="text-align: center; margin-bottom: 24px;">
                    <h4 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">
                        {{ $plan->name }}
                    </h4>
                    <div style="font-size: 36px; font-weight: 700; color: var(--primary);">
                        {{ number_format($plan->price, 0, ',', ' ') }} FCFA
                    </div>
                    <div style="font-size: 13px; color: var(--text-tertiary); margin-top: 4px;">
                        {{ $plan->duration_months }} mois • {{ number_format($plan->price / $plan->duration_months, 0, ',', ' ') }} FCFA/mois
                    </div>
                </div>
                
                <ul style="list-style: none; padding: 0; margin: 0 0 24px 0;">
                    @foreach($plan->features ?? ['Accès complet à toutes les fonctionnalités', 'Support prioritaire', 'Sauvegarde automatique'] as $feature)
                    <li style="
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        padding: 8px 0;
                        font-size: 13px;
                        color: var(--text-secondary);
                    ">
                        <i class="bi bi-check-circle-fill" style="color: var(--accent-green); font-size: 14px;"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                
                <form action="{{ route('subscription.subscribe') }}" method="GET">
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit" class="btn-cuni primary" style="width: 100%;" {{ !$plan->is_active ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus"></i> 
                        {{ $plan->is_active ? 'S\'abonner' : 'Bientôt disponible' }}
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Payment History --}}
@if($paymentHistory->count() > 0)
<div class="cuni-card" style="margin-top: 24px;">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-clock-history"></i> Historique des Paiements
        </h3>
        <a href="{{ route('subscription.status') }}" class="btn-cuni sm secondary">
            Voir tout <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body">
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--surface-border);">
                        <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--text-tertiary);">Date</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--text-tertiary);">Montant</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--text-tertiary);">Méthode</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; color: var(--text-tertiary);">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentHistory as $payment)
                    <tr style="border-bottom: 1px solid var(--surface-border);">
                        <td style="padding: 12px; font-size: 13px;">{{ $payment->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 12px; font-size: 13px; font-weight: 600;">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</td>
                        <td style="padding: 12px; font-size: 13px;">
                            <span style="
                                background: rgba(59, 130, 246, 0.1);
                                color: #3B82F6;
                                padding: 4px 8px;
                                border-radius: 4px;
                                font-size: 11px;
                                font-weight: 600;
                            ">{{ strtoupper($payment->payment_method) }}</span>
                        </td>
                        <td style="padding: 12px; font-size: 13px;">
                            @if($payment->status === 'completed')
                            <span style="color: var(--accent-green);"><i class="bi bi-check-circle"></i> Payé</span>
                            @elseif($payment->status === 'pending')
                            <span style="color: var(--accent-orange);"><i class="bi bi-clock"></i> En attente</span>
                            @else
                            <span style="color: var(--accent-red);"><i class="bi bi-x-circle"></i> Échoué</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
.plan-card:hover {
    border-color: var(--primary) !important;
}
</style>
@endpush
@endsection