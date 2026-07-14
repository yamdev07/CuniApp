@extends('layouts.cuniapp')

@section('title', __('Gestion des Entreprises') . ' - Super Admin')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-building" style="color: var(--accent-orange);"></i>
            {{ __('Gestion des Entreprises') }}
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ __('Tableau de bord') }}</a>
            <span>/</span>
            <a href="{{ route('super.admin.dashboard') }}">{{ __('Super Admin') }}</a>
            <span>/</span>
            <span>{{ __('Entreprises') }}</span>
        </div>
    </div>
</div>

@if (session('success'))
<div class="alert-cuni success">
    <i class="bi bi-check-circle-fill"></i>
    <div>{{ session('success') }}</div>
</div>
@endif

{{-- Search & Filters --}}
<div class="cuni-card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('super.admin.firms') }}">
            <input type="hidden" name="sort" value="{{ request('sort', 'revenue') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
                <div>
                    <label class="form-label">{{ __('Recherche') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __("Nom d'entreprise...") }}">
                </div>
                <div>
                    <label class="form-label">{{ __('Statut') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('Tous') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Actif') }}</option>
                        <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>{{ __('Banni') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('Activité') }}</label>
                    <select name="activity" class="form-select">
                        <option value="">{{ __('Toutes') }}</option>
                        <option value="online" {{ request('activity') === 'online' ? 'selected' : '' }}>{{ __('En ligne') }}</option>
                        <option value="recent" {{ request('activity') === 'recent' ? 'selected' : '' }}>{{ __('Actif (24h)') }}</option>
                        <option value="inactive" {{ request('activity') === 'inactive' ? 'selected' : '' }}>{{ __('Inactif') }}</option>
                    </select>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-cuni primary" style="flex: 1;">
                        <i class="bi bi-search"></i> {{ __('Filtrer') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Firms Table --}}
<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-list-ul"></i> {{ __('Liste des Entreprises') }}
        </h3>
        <div style="display: flex; align-items: center; gap: 8px;">
            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                {{ $firms->total() }} {{ __('entreprise(s)') }}
            </span>
            @php $currentSort = request('sort', 'revenue'); @endphp
            <a href="{{ route('super.admin.firms', array_merge(request()->query(), ['sort' => 'revenue'])) }}"
               class="btn-cuni sm {{ $currentSort === 'revenue' ? 'primary' : 'secondary' }}">
                <i class="bi bi-currency-exchange"></i> {{ __('Revenus') }}
            </a>
            <a href="{{ route('super.admin.firms', array_merge(request()->query(), ['sort' => 'activity'])) }}"
               class="btn-cuni sm {{ $currentSort === 'activity' ? 'primary' : 'secondary' }}">
                <i class="bi bi-clock-history"></i> {{ __('Activité') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Entreprise') }}</th>
                        <th>{{ __('Administrateur') }}</th>
                        <th>{{ __('Abonnement') }}</th>
                        <th>{{ __('Revenus') }}</th>
                        <th>{{ __('Activité') }}</th>
                        <th>{{ __('Statut') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($firms as $firm)
                    @php
                        $admin = $firm->users->first();
                        $isOnline = $admin && $admin->isOnline();
                        $lastSeen = $admin?->last_seen_at;
                    @endphp
                    <tr data-firm-id="{{ $firm->id }}">
                        <td>
                            <strong>{{ $firm->name }}</strong><br>
                            <small class="text-muted">{{ $firm->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>{{ $firm->owner->name ?? 'N/A' }}</td>
                        <td>
                            @if($firm->activeSubscription)
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                {{ $firm->activeSubscription->plan->name ?? 'N/A' }}
                            </span>
                            @else
                            <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7280;">
                                {{ __('Aucun') }}
                            </span>
                            @endif
                        </td>
                        <td class="fw-bold" style="color: var(--primary);">
                            {{ number_format($firm->total_revenue ?? 0, 0, ',', ' ') }} FCFA
                        </td>
                        <td class="activity-cell">
                            @if($isOnline)
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; background: rgba(16, 185, 129, 0.1); color: #10B981; font-size: 12px; font-weight: 600;">
                                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #10B981; animation: pulse-dot 2s infinite;"></span>
                                    {{ __('En ligne') }}
                                </span>
                            @elseif($lastSeen)
                                <span style="font-size: 12px; color: var(--text-tertiary);">
                                    <i class="bi bi-clock"></i> {{ $lastSeen->diffForHumans() }}
                                </span>
                            @else
                                <span style="font-size: 12px; color: var(--text-tertiary);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($firm->status === 'active')
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">{{ __('Actif') }}</span>
                            @else
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">{{ __('Banni') }}</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('super.admin.firms.show', $firm->id) }}" class="btn-cuni sm secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($firm->status === 'active')
                                <form action="{{ route('super.admin.firms.ban', $firm->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-cuni sm danger" onclick="return confirm('{{ __('Bannir cette entreprise ?') }}')">
                                        <i class="bi bi-ban"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('super.admin.firms.activate', $firm->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-cuni sm primary">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5;"></i>
                            <p class="mt-4">{{ __('Aucune entreprise trouvée') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($firms->hasPages())
        <div style="margin-top: 24px;">
            {{ $firms->links('pagination.bootstrap-5-sm') }}
        </div>
        @endif
    </div>
</div>

<style>
@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
</style>

<script>
(function() {
    const activityUrl = '{{ route("super.admin.firms.activity") }}';
    const firms = @json($firms->pluck('id'));
    let currentSort = '{{ request("sort", "revenue") }}';

    function updateActivityCell(firmId, data) {
        const row = document.querySelector(`tr[data-firm-id="${firmId}"]`);
        if (!row) return;
        const cell = row.querySelector('.activity-cell');
        if (!cell) return;

        if (data.is_online) {
            cell.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; background: rgba(16, 185, 129, 0.1); color: #10B981; font-size: 12px; font-weight: 600;"><span style="width: 7px; height: 7px; border-radius: 50%; background: #10B981; animation: pulse-dot 2s infinite;"></span> En ligne</span>';
        } else if (data.last_seen_at) {
            const diff = timeSince(new Date(data.last_seen_at));
            cell.innerHTML = '<span style="font-size: 12px; color: var(--text-tertiary);"><i class="bi bi-clock"></i> ' + diff + '</span>';
        } else {
            cell.innerHTML = '<span style="font-size: 12px; color: var(--text-tertiary);">—</span>';
        }
    }

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

    function pollActivity() {
        fetch(activityUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                data.forEach(item => updateActivityCell(item.id, item));
            })
            .catch(() => {});
    }

    setInterval(pollActivity, 30000);
})();
</script>
@endsection
