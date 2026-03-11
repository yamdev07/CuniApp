{{-- resources/views/admin/subscriptions/index.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Gestion des Abonnements - Admin')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-shield-lock"></i> Gestion des Abonnements
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Admin</span>
            <span>/</span>
            <span>Abonnements</span>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="cuni-card">
        <div class="card-body p-4">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 13px; color: var(--text-secondary);">Utilisateurs Total</p>
                    <p style="font-size: 28px; font-weight: 700; color: var(--text-primary);">{{ $stats['total_users'] }}</p>
                </div>
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(59, 130, 246, 0.1); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-people" style="font-size: 24px; color: #3B82F6;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cuni-card">
        <div class="card-body p-4">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 13px; color: var(--text-secondary);">Abonnements Actifs</p>
                    <p style="font-size: 28px; font-weight: 700; color: var(--accent-green);">{{ $stats['active_subscriptions'] }}</p>
                </div>
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-check-circle" style="font-size: 24px; color: var(--accent-green);"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cuni-card">
        <div class="card-body p-4">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 13px; color: var(--text-secondary);">Expire Bientôt</p>
                    <p style="font-size: 28px; font-weight: 700; color: var(--accent-orange);">{{ $stats['expiring_soon'] }}</p>
                </div>
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(245, 158, 11, 0.1); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-clock" style="font-size: 24px; color: var(--accent-orange);"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cuni-card">
        <div class="card-body p-4">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 13px; color: var(--text-secondary);">Revenus ce Mois</p>
                    <p style="font-size: 28px; font-weight: 700; color: var(--primary);">{{ number_format($stats['revenue_this_month'], 0, ',', ' ') }} FCFA</p>
                </div>
                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(139, 92, 246, 0.1); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-currency-euro" style="font-size: 24px; color: var(--accent-purple);"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Users Table --}}
<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-list-ul"></i> Utilisateurs et Abonnements
        </h3>
        <form method="GET" style="display: flex; gap: 12px;">
            <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirés</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulés</option>
            </select>
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" class="form-control" style="width: 250px;">
            <button type="submit" class="btn-cuni primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="card-body">
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--surface-border);">
                        <th style="padding: 12px; text-align: left;">Utilisateur</th>
                        <th style="padding: 12px; text-align: left;">Email</th>
                        <th style="padding: 12px; text-align: left;">Abonnement</th>
                        <th style="padding: 12px; text-align: left;">Statut</th>
                        <th style="padding: 12px; text-align: left;">Expiration</th>
                        <th style="padding: 12px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-bottom: 1px solid var(--surface-border);">
                        <td style="padding: 12px; font-weight: 600;">{{ $user->name }}</td>
                        <td style="padding: 12px;">{{ $user->email }}</td>
                        <td style="padding: 12px;">
                            @if($user->activeSubscription)
                            {{ $user->activeSubscription->plan->name }}
                            @else
                            <span style="color: var(--text-tertiary);">Aucun</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            @if($user->hasActiveSubscription())
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">Actif</span>
                            @else
                            <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-500);">Inactif</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            {{ $user->subscription_ends_at?->format('d/m/Y') ?? '-' }}
                        </td>
                        <td style="padding: 12px;">
                            <a href="{{ route('admin.subscriptions.show', $user->id) }}" class="btn-cuni sm secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(!$user->hasActiveSubscription())
                            <button type="button" class="btn-cuni sm primary" onclick="showActivateModal({{ $user->id }}, '{{ $user->name }}')">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div style="margin-top: 24px;">
            {{ $users->links('pagination.bootstrap-5-sm') }}
        </div>
        @endif
    </div>
</div>

{{-- Activate Subscription Modal --}}
<div id="activateModal" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    z-index: 1000;
    align-items: center;
    justify-content: center;
">
    <div style="
        background: var(--surface);
        border-radius: var(--radius-lg);
        max-width: 500px;
        width: 90%;
        padding: 32px;
    ">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">
            Activer un Abonnement
        </h3>
        <form action="{{ route('admin.subscriptions.activate') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" id="activateUserId">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Utilisateur</label>
                <input type="text" id="activateUserName" class="form-control" readonly style="background: var(--surface-alt);">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Plan</label>
                <select name="plan_id" class="form-select" required>
                    @foreach(\App\Models\SubscriptionPlan::where('is_active', true)->get() as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} - {{ number_format($plan->price, 0, ',', ' ') }} FCFA</option>
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

@push('scripts')
<script>
function showActivateModal(userId, userName) {
    document.getElementById('activateUserId').value = userId;
    document.getElementById('activateUserName').value = userName;
    document.getElementById('activateModal').style.display = 'flex';
}
</script>
@endpush
@endsection