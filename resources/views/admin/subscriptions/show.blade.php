{{-- resources/views/admin/subscriptions/show.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Détails Utilisateur - Admin')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-shield-lock"></i> Gestion des Abonnements
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('admin.subscriptions.index') }}">Admin</a>
            <span>/</span>
            <span>{{ $user->name }}</span>
        </div>
    </div>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn-cuni secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

@if (session('success'))
<div class="alert-cuni success" style="margin-bottom: 24px;">
    <i class="bi bi-check-circle-fill"></i>
    <div>{{ session('success') }}</div>
</div>
@endif

@if (session('error'))
<div class="alert-cuni error" style="margin-bottom: 24px;">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>{{ session('error') }}</div>
</div>
@endif

<!-- User Info Card -->
<div class="cuni-card mb-6">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-person-circle"></i> Informations Utilisateur
        </h3>
    </div>
    <div class="card-body">
        <div class="settings-grid">
            <div class="form-group">
                <label class="form-label">Nom</label>
                <p class="fw-semibold">{{ $user->name }}</p>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <p class="fw-semibold">{{ $user->email }}</p>
            </div>
            <div class="form-group">
                <label class="form-label">Rôle</label>
                <p>
                    <span class="badge" style="background: {{ $user->role === 'admin' ? 'rgba(139, 92, 246, 0.1); color: #8B5CF6;' : 'rgba(59, 130, 246, 0.1); color: #3B82F6;' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
            </div>
            <div class="form-group">
                <label class="form-label">Statut Abonnement</label>
                <p>
                    @if($user->hasActiveSubscription())
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">
                            <i class="bi bi-check-circle"></i> Actif
                        </span>
                    @else
                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-500);">
                            <i class="bi bi-x-circle"></i> Inactif
                        </span>
                    @endif
                </p>
            </div>
            <div class="form-group">
                <label class="form-label">Expiration</label>
                <p class="fw-semibold">
                    @if($user->subscription_ends_at)
                        {{ $user->subscription_ends_at->format('d/m/Y H:i') }}
                        @php
                            $daysLeft = $user->subscription_ends_at->diffInDays(now(), false);
                        @endphp
                        @if($daysLeft < 0)
                            <span style="color: var(--accent-red); font-size: 12px;"> (Expiré depuis {{ abs($daysLeft) }} jours)</span>
                        @elseif($daysLeft <= 7)
                            <span style="color: var(--accent-orange); font-size: 12px;"> (Expire dans {{ $daysLeft }} jours)</span>
                        @endif
                    @else
                        <span style="color: var(--text-tertiary);">-</span>
                    @endif
                </p>
            </div>
            <div class="form-group">
                <label class="form-label">Inscrit le</label>
                <p style="color: var(--text-secondary); font-size: 13px;">
                    {{ $user->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--surface-border); display: flex; gap: 12px; flex-wrap: wrap;">
            @if(!$user->hasActiveSubscription())
            <button type="button" class="btn-cuni primary" onclick="showActivateModal()">
                <i class="bi bi-check-circle"></i> Activer Abonnement
            </button>
            @endif
            @if($user->activeSubscription())
            <button type="button" class="btn-cuni secondary" onclick="showExtendModal()">
                <i class="bi bi-calendar-plus"></i> Prolonger
            </button>
            <button type="button" class="btn-cuni danger" onclick="showDeactivateModal()">
                <i class="bi bi-x-circle"></i> Désactiver
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Subscriptions History -->
<div class="cuni-card mb-6">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-credit-card"></i> Historique des Abonnements
        </h3>
        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
            {{ $subscriptions->total() }} abonnement(s)
        </span>
    </div>
    <div class="card-body">
        @if($subscriptions->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--surface-border);">
                        <th style="padding: 12px; text-align: left;">Plan</th>
                        <th style="padding: 12px; text-align: left;">Prix</th>
                        <th style="padding: 12px; text-align: left;">Début</th>
                        <th style="padding: 12px; text-align: left;">Fin</th>
                        <th style="padding: 12px; text-align: left;">Statut</th>
                        <th style="padding: 12px; text-align: left;">Paiement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $sub)
                    <tr style="border-bottom: 1px solid var(--surface-border);">
                        <td style="padding: 12px;">
                            {{ $sub->plan->name ?? 'N/A' }}
                        </td>
                        <td style="padding: 12px; font-weight: 600;">
                            {{ number_format($sub->price, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="padding: 12px;">
                            {{ $sub->start_date->format('d/m/Y') }}
                        </td>
                        <td style="padding: 12px;">
                            {{ $sub->end_date->format('d/m/Y') }}
                        </td>
                        <td style="padding: 12px;">
                            @if ($sub->status === 'active')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">Actif</span>
                            @elseif($sub->status === 'expired')
                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-500);">Expiré</span>
                            @elseif($sub->status === 'cancelled')
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red);">Annulé</span>
                            @else
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--accent-orange);">En attente</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            {{ strtoupper($sub->payment_method ?? 'N/A') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($subscriptions->hasPages())
        <div style="margin-top: 24px;">
            {{ $subscriptions->links('pagination.bootstrap-5-sm') }}
        </div>
        @endif
        @else
        <div style="text-align: center; padding: 40px; color: var(--text-tertiary);">
            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
            <p>Aucun abonnement trouvé</p>
        </div>
        @endif
    </div>
</div>

<!-- Payment History -->
<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-clock-history"></i> Historique des Paiements
        </h3>
        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">
            {{ $paymentHistory->total() }} transaction(s)
        </span>
    </div>
    <div class="card-body">
        @if($paymentHistory->count() > 0)
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--surface-border);">
                        <th style="padding: 12px; text-align: left;">Date</th>
                        <th style="padding: 12px; text-align: left;">Montant</th>
                        <th style="padding: 12px; text-align: left;">Méthode</th>
                        <th style="padding: 12px; text-align: left;">Statut</th>
                        <th style="padding: 12px; text-align: left;">Référence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentHistory as $payment)
                    <tr style="border-bottom: 1px solid var(--surface-border);">
                        <td style="padding: 12px; font-size: 13px;">
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 12px; font-size: 13px; font-weight: 600;">
                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                        </td>
                        <td style="padding: 12px; font-size: 13px;">
                            <span style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                {{ strtoupper($payment->payment_method) }}
                            </span>
                        </td>
                        <td style="padding: 12px; font-size: 13px;">
                            @if ($payment->status === 'completed')
                                <span style="color: var(--accent-green);">
                                    <i class="bi bi-check-circle"></i> Payé
                                </span>
                            @elseif($payment->status === 'pending')
                                <span style="color: var(--accent-orange);">
                                    <i class="bi bi-clock"></i> En attente
                                </span>
                            @elseif($payment->status === 'failed')
                                <span style="color: var(--accent-red);">
                                    <i class="bi bi-x-circle"></i> Échoué
                                </span>
                            @else
                                <span style="color: var(--gray-500);">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px; font-size: 12px; color: var(--text-tertiary);">
                            {{ $payment->transaction_id }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($paymentHistory->hasPages())
        <div style="margin-top: 24px;">
            {{ $paymentHistory->links('pagination.bootstrap-5-sm') }}
        </div>
        @endif
        @else
        <div style="text-align: center; padding: 40px; color: var(--text-tertiary);">
            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
            <p>Aucun paiement trouvé</p>
        </div>
        @endif
    </div>
</div>

<!-- Activate Subscription Modal -->
<div id="activateModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); border-radius: var(--radius-lg); max-width: 500px; width: 90%; padding: 32px;">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">
            Activer un Abonnement
        </h3>
        <form action="{{ route('admin.subscriptions.activate') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Utilisateur</label>
                <input type="text" class="form-control" value="{{ $user->name }}" readonly style="background: var(--surface-alt);">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Plan</label>
                <select name="plan_id" class="form-select" required>
                    @foreach (\App\Models\SubscriptionPlan::where('is_active', true)->get() as $plan)
                        <option value="{{ $plan->id }}">
                            {{ $plan->name }} - {{ number_format($plan->price, 0, ',', ' ') }} FCFA
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Durée (mois)</label>
                <input type="number" name="duration_months" class="form-control" value="1" min="1" max="24">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                <button type="button" class="btn-cuni secondary" onclick="document.getElementById('activateModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i> Activer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Extend Subscription Modal -->
<div id="extendModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); border-radius: var(--radius-lg); max-width: 500px; width: 90%; padding: 32px;">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">
            Prolonger l'Abonnement
        </h3>
        <form action="{{ route('admin.subscriptions.extend') }}" method="POST">
            @csrf
            <input type="hidden" name="subscription_id" value="{{ $user->activeSubscription()?->id }}">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Utilisateur</label>
                <input type="text" class="form-control" value="{{ $user->name }}" readonly style="background: var(--surface-alt);">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Mois à ajouter</label>
                <input type="number" name="months" class="form-control" value="1" min="1" max="24">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                <button type="button" class="btn-cuni secondary" onclick="document.getElementById('extendModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-calendar-plus"></i> Prolonger
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Deactivate Subscription Modal -->
<div id="deactivateModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); border-radius: var(--radius-lg); max-width: 500px; width: 90%; padding: 32px;">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px; color: var(--accent-red);">
            <i class="bi bi-exclamation-triangle"></i> Désactiver l'Abonnement
        </h3>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            Êtes-vous sûr de vouloir désactiver l'abonnement de cet utilisateur ? Cette action est irréversible.
        </p>
        <form action="{{ route('admin.subscriptions.deactivate') }}" method="POST">
            @csrf
            <input type="hidden" name="subscription_id" value="{{ $user->activeSubscription()?->id }}">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Raison (optionnel)</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Raison de la désactivation..."></textarea>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                <button type="button" class="btn-cuni secondary" onclick="document.getElementById('deactivateModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn-cuni danger">
                    <i class="bi bi-trash"></i> Désactiver
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showActivateModal() {
    document.getElementById('activateModal').style.display = 'flex';
}

function showExtendModal() {
    document.getElementById('extendModal').style.display = 'flex';
}

function showDeactivateModal() {
    document.getElementById('deactivateModal').style.display = 'flex';
}

// Close modals on outside click
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection