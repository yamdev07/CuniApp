{{-- resources/views/super-admin/firms/index.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Gestion des Entreprises - Super Admin')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-building" style="color: var(--accent-orange);"></i>
            Gestion des Entreprises
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('super.admin.dashboard') }}">Super Admin</a>
            <span>/</span>
            <span>Entreprises</span>
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
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                <div>
                    <label class="form-label">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nom d'entreprise...">
                </div>
                <div>
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banni</option>
                    </select>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-cuni primary" style="flex: 1;">
                        <i class="bi bi-search"></i> Filtrer
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
            <i class="bi bi-list-ul"></i> Liste des Entreprises
        </h3>
        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
            {{ $firms->total() }} entreprise(s)
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Administrateur</th>
                        <th>Abonnement</th>
                        <th>Revenus</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($firms as $firm)
                    <tr>
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
                                Aucun
                            </span>
                            @endif
                        </td>
                        <td class="fw-bold" style="color: var(--primary);">
                            {{ number_format($firm->total_revenue ?? 0, 0, ',', ' ') }} FCFA
                        </td>
                        <td>
                            @if($firm->status === 'active')
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">Actif</span>
                            @else
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">Banni</span>
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
                                    <button type="submit" class="btn-cuni sm danger" onclick="return confirm('Bannir cette entreprise ?')">
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
                            <p class="mt-4">Aucune entreprise trouvée</p>
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
@endsection