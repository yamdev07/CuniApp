@extends('layouts.cuniapp')

@section('title', __('Détails de l\'Entreprise') . ' - ' . $firm->name)

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-building"></i> {{ $firm->name }}
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('super.admin.dashboard') }}">{{ __('Super Admin') }}</a>
                <span>/</span>
                <a href="{{ route('super.admin.firms') }}">{{ __('Entreprises') }}</a>
                <span>/</span>
                <span>{{ $firm->name }}</span>
            </div>
        </div>
        <div class="header-actions">
            @if ($firm->status === 'active')
                @php $ownerOnline = $firm->owner && $firm->owner->isOnline(); @endphp
                @if($ownerOnline)
                    <span class="btn-cuni sm" style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); cursor: default;">
                        <i class="bi bi-circle-fill" style="font-size: 8px; color: #10B981; animation: pulse-dot 2s infinite;"></i>
                        {{ __('Administrateur en ligne') }}
                    </span>
                @endif
                <form action="{{ route('super.admin.firms.ban', $firm->id) }}" method="POST" style="display: inline; margin-left: 8px;">
                    @csrf
                    <button type="submit" class="btn-cuni sm danger" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir bannir cette entreprise ?') }}')">
                        <i class="bi bi-slash-circle"></i> {{ __('Bannir l\'Entreprise') }}
                    </button>
                </form>
            @else
                <form action="{{ route('super.admin.firms.activate', $firm->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-cuni sm success">
                        <i class="bi bi-check-circle"></i> {{ __('Réactiver l\'Entreprise') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Firm Info & Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="cuni-card md:col-span-1">
            <div class="card-header-custom">
                <h3 class="card-title">{{ __('Informations') }}</h3>
            </div>
            <div class="card-body p-4">
                <ul style="list-style: none; padding: 0; margin: 0; line-height: 2;">
                    <li><strong>{{ __('Propriétaire') }}:</strong> {{ $firm->owner->name ?? 'N/A' }}</li>
                    <li><strong>{{ __('Email') }}:</strong> {{ $firm->owner->email ?? 'N/A' }}</li>
                    <li><strong>{{ __('Téléphone') }}:</strong> {{ $firm->phone ?? 'N/A' }}</li>
                    <li><strong>{{ __('Localisation') }}:</strong> {{ $firm->location ?? 'N/A' }}</li>
                    <li><strong>{{ __('Statut') }}:</strong>
                        @if($firm->status === 'active')
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">{{ __('Actif') }}</span>
                        @else
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red);">{{ __('Banni') }}</span>
                        @endif
                    </li>
                    <li><strong>{{ __('Créé le') }}:</strong> {{ $firm->created_at->format('d/m/Y') }}</li>
                    <li><strong>{{ __('Abonnement Actuel') }}:</strong> {{ $firm->activeSubscription->plan->name ?? __('Aucun') }}</li>
                </ul>
            </div>
        </div>

        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="cuni-card stats-card-compact">
                <div class="card-body p-3">
                    <div class="flex items-center gap-3">
                        <div class="stats-icon-small" style="background: rgba(16, 185, 129, 0.1);">
                            <i class="bi bi-currency-euro text-green-500"></i>
                        </div>
                        <div>
                            <p class="stats-label-small">{{ __('Revenus Générés') }}</p>
                            <p class="stats-value-small">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <small class="text-xs">FCFA</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cuni-card stats-card-compact">
                <div class="card-body p-3">
                    <div class="flex items-center gap-3">
                        <div class="stats-icon-small" style="background: rgba(139, 92, 246, 0.1);">
                            <i class="bi bi-cart text-purple-500"></i>
                        </div>
                        <div>
                            <p class="stats-label-small">{{ __('Ventes Totales') }}</p>
                            <p class="stats-value-small">{{ number_format($stats['total_sales']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cuni-card stats-card-compact">
                <div class="card-body p-3">
                    <div class="flex items-center gap-3">
                        <div class="stats-icon-small" style="background: rgba(245, 158, 11, 0.1);">
                            <i class="bi bi-collection text-amber-500"></i>
                        </div>
                        <div>
                            <p class="stats-label-small">{{ __('Cheptel (Mâles / Femelles)') }}</p>
                            <p class="stats-value-small">{{ $stats['total_males'] }} / {{ $stats['total_femelles'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cuni-card stats-card-compact">
                <div class="card-body p-3">
                    <div class="flex items-center gap-3">
                        <div class="stats-icon-small" style="background: rgba(16, 185, 129, 0.1);">
                            <i class="bi bi-people text-green-500"></i>
                        </div>
                        <div>
                            <p class="stats-label-small">{{ __('Utilisateurs en ligne') }}</p>
                            <p class="stats-value-small"><span class="online-count-display">{{ $stats['online_count'] }}</span> / {{ $stats['user_count'] }}</p>
                        </div>
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
        @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .online-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
        .online-dot.green { background: #10B981; animation: pulse-dot 2s infinite; }
        .online-dot.gray { background: #9CA3AF; }
    </style>

    {{-- Users Section --}}
    <div class="cuni-card mt-6">
        <div class="card-header-custom" style="flex-wrap: wrap; gap: 12px;">
            <h3 class="card-title"><i class="bi bi-people"></i> {{ __('Utilisateurs de l\'entreprise') }}</h3>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 12px; font-weight: 600;">
                    <span class="online-dot green"></span>
                    {{ __('En ligne') }}: <span class="online-count-display">{{ $stats['online_count'] }}</span>
                </span>
                <span style="font-size: 12px; color: var(--text-tertiary);">{{ $stats['user_count'] }} {{ __('utilisateur(s) au total') }}</span>
            </div>
        </div>

        {{-- User Filters --}}
        <div style="padding: 16px 24px 0;">
            <form method="GET" action="{{ route('super.admin.firms.show', $firm->id) }}">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px;">
                    <div>
                        <input type="text" name="user_search" value="{{ request('user_search') }}" class="form-control" placeholder="{{ __('Rechercher un utilisateur...') }}" style="font-size: 13px;">
                    </div>
                    <div>
                        <select name="user_role" class="form-select" style="font-size: 13px;">
                            <option value="">{{ __('Tous les rôles') }}</option>
                            <option value="firm_admin" {{ request('user_role') === 'firm_admin' ? 'selected' : '' }}>{{ __('Administrateur') }}</option>
                            <option value="employee" {{ request('user_role') === 'employee' ? 'selected' : '' }}>{{ __('Employé') }}</option>
                        </select>
                    </div>
                    <div>
                        <select name="user_status" class="form-select" style="font-size: 13px;">
                            <option value="">{{ __('Tous les statuts') }}</option>
                            <option value="active" {{ request('user_status') === 'active' ? 'selected' : '' }}>{{ __('Actif') }}</option>
                            <option value="inactive" {{ request('user_status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactif') }}</option>
                        </select>
                    </div>
                    <div>
                        <select name="user_activity" class="form-select" style="font-size: 13px;">
                            <option value="">{{ __('Toute activité') }}</option>
                            <option value="online" {{ request('user_activity') === 'online' ? 'selected' : '' }}>{{ __('En ligne maintenant') }}</option>
                            <option value="recent" {{ request('user_activity') === 'recent' ? 'selected' : '' }}>{{ __('Actif (24h)') }}</option>
                            <option value="inactive" {{ request('user_activity') === 'inactive' ? 'selected' : '' }}>{{ __('Inactif (24h+)') }}</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-cuni primary" style="width: 100%; font-size: 13px;">
                            <i class="bi bi-funnel"></i> {{ __('Filtrer') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--surface-border);">
                            <th style="padding: 12px; text-align: left;">{{ __('Nom') }}</th>
                            <th style="padding: 12px; text-align: left;">{{ __('Email') }}</th>
                            <th style="padding: 12px; text-align: left;">{{ __('Rôle') }}</th>
                            <th style="padding: 12px; text-align: left;">{{ __('Statut') }}</th>
                            <th style="padding: 12px; text-align: left;">{{ __('Activité') }}</th>
                            <th style="padding: 12px; text-align: left;">{{ __('Dernière connexion') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            @php $isUserOnline = $u->isOnline(); @endphp
                            <tr data-user-id="{{ $u->id }}" style="border-bottom: 1px solid var(--surface-border);">
                                <td style="padding: 12px; font-weight: 600;">
                                    <span style="display: flex; align-items: center; gap: 8px;">
                                        <span class="online-dot {{ $isUserOnline ? 'green' : 'gray' }}"></span>
                                        {{ $u->name }}
                                    </span>
                                </td>
                                <td style="padding: 12px; color: var(--text-tertiary);">{{ $u->email }}</td>
                                <td style="padding: 12px;">
                                    @if($u->role === 'firm_admin')
                                        <span class="badge" style="background: rgba(139, 92, 246, 0.1); color: var(--purple-600);">{{ __('Admin') }}</span>
                                    @else
                                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--gray-600);">{{ __('Employé') }}</span>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    @if($u->status === 'active')
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">{{ __('Actif') }}</span>
                                    @else
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red);">{{ __('Inactif') }}</span>
                                    @endif
                                </td>
                                <td class="activity-cell" style="padding: 12px;">
                                    @if($isUserOnline)
                                        <span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 12px; font-weight: 600;">
                                            <span class="online-dot green"></span>
                                            {{ __('En ligne') }}
                                        </span>
                                    @elseif($u->last_seen_at)
                                        <span style="font-size: 12px; color: var(--text-tertiary);">
                                            <i class="bi bi-clock"></i> {{ $u->last_seen_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span style="font-size: 12px; color: var(--text-tertiary);">—</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; font-size: 13px; color: var(--text-secondary);">
                                    @if($u->last_seen_at)
                                        {{ $u->last_seen_at->format('d/m/Y H:i') }}
                                    @else
                                        <span style="color: var(--text-tertiary);">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color: var(--text-tertiary);">
                                    <i class="bi bi-person-x" style="font-size: 32px; opacity: 0.4; display: block; margin-bottom: 8px;"></i>
                                    {{ __('Aucun utilisateur trouvé.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const usersActivityUrl = '{{ route("super.admin.firms.users.activity", $firm->id) }}';

        function timeSince(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            if (seconds < 60) return 'à l\'instant';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return 'il y a ' + minutes + ' minute' + (minutes > 1 ? 's' : '');
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return 'il y a ' + hours + ' heure' + (hours > 1 ? 's' : '');
            const days = Math.floor(hours / 24);
            return 'il y a ' + days + ' jour' + (days > 1 ? 's' : '');
        }

        function updateUserCells(data) {
            data.forEach(function(user) {
                const row = document.querySelector('tr[data-user-id="' + user.id + '"]');
                if (!row) return;

                const dot = row.querySelector('.online-dot');
                if (dot) {
                    dot.className = 'online-dot ' + (user.is_online ? 'green' : 'gray');
                }

                const activityCell = row.querySelector('.activity-cell');
                if (activityCell) {
                    if (user.is_online) {
                        activityCell.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 12px; font-weight: 600;"><span class="online-dot green"></span> En ligne</span>';
                    } else if (user.last_seen_at) {
                        const diff = timeSince(new Date(user.last_seen_at));
                        activityCell.innerHTML = '<span style="font-size: 12px; color: var(--text-tertiary);"><i class="bi bi-clock"></i> ' + diff + '</span>';
                    } else {
                        activityCell.innerHTML = '<span style="font-size: 12px; color: var(--text-tertiary);">—</span>';
                    }
                }
            });

            const onlineCount = data.filter(u => u.is_online).length;
            const countEl = document.querySelector('.online-count-display');
            if (countEl) countEl.textContent = onlineCount;
        }

        function pollUsers() {
            fetch(usersActivityUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(updateUserCells)
                .catch(function() {});
        }

        setInterval(pollUsers, 30000);
    })();
    </script>
@endsection
