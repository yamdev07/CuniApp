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

        <div class="header-actions">
            <a href="{{ route('admin.subscriptions.archives') }}" class="btn-cuni secondary">
                <i class="bi bi-archive"></i> Voir les Archives
            </a>
        </div>
    </div>

    {{-- Stats Grid - Compact & Modern --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(59, 130, 246, 0.1);">
                        <i class="bi bi-people text-blue-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Utilisateurs Total</p>
                        <p class="stats-value-small">{{ number_format($stats['total_users']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(16, 185, 129, 0.1);">
                        <i class="bi bi-check-circle text-green-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Abonnements Actifs</p>
                        <p class="stats-value-small text-green-600">{{ number_format($stats['active_subscriptions']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(245, 158, 11, 0.1);">
                        <i class="bi bi-clock text-amber-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Expire Bientôt</p>
                        <p class="stats-value-small text-amber-600">{{ $stats['expiring_soon'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card stats-card-compact">
            <div class="card-body p-3">
                <div class="flex items-center gap-3">
                    <div class="stats-icon-small" style="background: rgba(139, 92, 246, 0.1);">
                        <i class="bi bi-currency-euro text-purple-500"></i>
                    </div>
                    <div>
                        <p class="stats-label-small">Revenus (Mois)</p>
                        <p class="stats-value-small">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stats-card-compact { transition: transform 0.2s ease; border: 1px solid var(--surface-border); }
        .stats-card-compact:hover { transform: translateY(-2px); }
        .stats-icon-small { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
        .stats-label-small { font-size: 0.72rem; color: var(--text-tertiary); margin: 0; text-transform: uppercase; letter-spacing: 0.025em; font-weight: 600; }
        .stats-value-small { font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--text-primary); line-height: 1.2; }
    </style>

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
                    <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                        class="form-control" style="width: 250px;">
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
                            @foreach ($users as $user)
                                <tr style="border-bottom: 1px solid var(--surface-border);">
                                    <td style="padding: 12px; font-weight: 600;">{{ $user->name }}</td>
                                    <td style="padding: 12px;">{{ $user->email }}</td>
                                    <td style="padding: 12px;">
                                        {{ $user->effective_plan_name }}
                                    </td>
                                    <td style="padding: 12px;">
                                        @if ($user->hasActiveSubscription())
                                            <span class="badge"
                                                style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">Actif</span>
                                        @else
                                            <span class="badge"
                                                style="background: rgba(107, 114, 128, 0.1); color: var(--gray-500);">Inactif</span>
                                        @endif
                                    </td>

                                    <td style="padding: 12px;">
                                        @if ($user->subscription_ends_at)
                                            {{ \Carbon\Carbon::parse($user->subscription_ends_at)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- <td style="padding: 12px;">
                                        <a href="{{ route('admin.subscriptions.show', $user->id) }}"
                                            class="btn-cuni sm secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($user->hasActiveSubscription())
                                            <form
                                                action="{{ route('admin.subscriptions.archive', $user->activeSubscriptionRelation->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Voulez-vous vraiment archiver cet abonnement ? Il sera retiré de la liste principale.');">
                                                @csrf
                                                <button type="submit" class="btn-cuni sm light" title="Archiver"
                                                    style="color: var(--accent-orange);">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if (!$user->hasActiveSubscription())
                                            <button type="button" class="btn-cuni sm primary"
                                                onclick="showActivateModal({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        @endif
                                    </td> --}}


                                    <td style="padding: 12px;">
                                        <div
                                            style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">

                                            {{-- BOUTON VOIR (Toujours visible) --}}
                                            <a href="{{ route('admin.subscriptions.show', $user->id) }}"
                                                class="btn-cuni sm secondary" title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @php
                                                // On cherche le dernier abonnement NON archivé
                                                $subscriptionToManage = $user->subscriptions->first();
                                            @endphp


                                            @if ($subscriptionToManage)
                                                <div style="display: flex; gap: 4px;">
                                                    {{-- CAS 1 : Un abonnement existe (Actif OU Expiré) -> BOUTON ARCHIVER --}}
                                                    <form
                                                        action="{{ route('admin.subscriptions.archive', $subscriptionToManage->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Archiver cet abonnement ?');">
                                                        @csrf
                                                        <button type="submit" class="btn-cuni sm light"
                                                            title="Archiver"
                                                            style="color: var(--accent-orange); border-color: var(--accent-orange); background: rgba(245, 158, 11, 0.05);">
                                                            <i class="bi bi-archive"></i>
                                                        </button>
                                                    </form>

                                                    {{-- ✅ NOUVEAU : Archiver TOUT si plusieurs subs --}}
                                                    @if($user->subscriptions->count() > 1)
                                                        <form
                                                            action="{{ route('admin.subscriptions.archive-all', $user->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Archiver TOUS les abonnements de cet utilisateur ? ({{ $user->subscriptions->count() }} trouvés)');">
                                                            @csrf
                                                            <button type="submit" class="btn-cuni sm light"
                                                                title="Archiver Tout"
                                                                style="color: #dc2626; border-color: #dc2626; background: rgba(220, 38, 38, 0.05);">
                                                                <i class="bi bi-archive-fill"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                {{-- CAS 2 : Aucun abonnement -> BOUTON ACTIVER --}}
                                                <button type="button" class="btn-cuni sm primary"
                                                    onclick="showActivateModal({{ $user->id }}, '{{ $user->name }}')"
                                                    title="Créer un abonnement">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ✅ PAGINATION STYLISÉE CuniApp --}}
                @if ($users->hasPages())
                    <div
                        class="flex flex-col md:flex-row justify-between items-center mt-8 gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">

                        {{-- Résumé --}}
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Affichage de <strong>{{ $users->firstItem() }}</strong> à
                            <strong>{{ $users->lastItem() }}</strong> sur
                            <strong>{{ $users->total() }}</strong> utilisateurs
                        </div>

                        <div class="flex gap-2 flex-wrap justify-center">

                            {{-- Bouton Précédent --}}
                            @if ($users->onFirstPage())
                                <span class="btn-cuni secondary sm opacity-50 cursor-not-allowed"
                                    style="pointer-events: none;">
                                    <i class="bi bi-chevron-left"></i> Précédent
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="btn-cuni secondary sm">
                                    <i class="bi bi-chevron-left"></i> Précédent
                                </a>
                            @endif

                            {{-- Numéros de pages (Logique intelligente) --}}
                            @php
                                $start = max($users->currentPage() - 2, 1);
                                $end = min($users->currentPage() + 2, $users->lastPage());
                            @endphp

                            {{-- Page 1 --}}
                            @if ($start > 1)
                                <a href="{{ $users->url(1) }}" class="btn-cuni sm"
                                    style="min-width: 40px; justify-content: center;">1</a>
                                @if ($start > 2)
                                    <span class="text-gray-400 px-2 flex items-center">...</span>
                                @endif
                            @endif

                            {{-- Pages intermédiaires --}}
                            @for ($i = $start; $i <= $end; $i++)
                                <a href="{{ $users->url($i) }}"
                                    class="btn-cuni sm {{ $i == $users->currentPage() ? 'primary' : 'secondary' }}"
                                    style="min-width: 40px; justify-content: center;">
                                    {{ $i }}
                                </a>
                            @endfor

                            {{-- Dernière Page --}}
                            @if ($end < $users->lastPage())
                                @if ($end < $users->lastPage() - 1)
                                    <span class="text-gray-400 px-2 flex items-center">...</span>
                                @endif
                                <a href="{{ $users->url($users->lastPage()) }}" class="btn-cuni sm"
                                    style="min-width: 40px; justify-content: center;">
                                    {{ $users->lastPage() }}
                                </a>
                            @endif

                            {{-- Bouton Suivant --}}
                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="btn-cuni secondary sm">
                                    Suivant <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <span class="btn-cuni secondary sm opacity-50 cursor-not-allowed"
                                    style="pointer-events: none;">
                                    Suivant <i class="bi bi-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Activate Subscription Modal --}}
        <div id="activateModal"
            style="
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
            <div
                style="
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
                        <label
                            style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Utilisateur</label>
                        <input type="text" id="activateUserName" class="form-control" readonly
                            style="background: var(--surface-alt);">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Plan</label>
                        <select name="plan_id" class="form-select" required>
                            @foreach (\App\Models\SubscriptionPlan::where('is_active', true)->where('price', '>', 0)->get() as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} -
                                    {{ number_format($plan->price, 0, ',', ' ') }} FCFA</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Durée
                            (mois)</label>
                        <input type="number" name="duration_months" class="form-control" value="1" min="1"
                            max="24">
                    </div>
                    <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                        <button type="button" class="btn-cuni secondary"
                            onclick="document.getElementById('activateModal').style.display='none'">
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
