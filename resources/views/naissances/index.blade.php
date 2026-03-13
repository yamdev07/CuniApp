{{-- resources/views/naissances/index.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Naissances - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-egg-fill"></i> Gestion des Naissances
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Naissances</span>
        </div>
    </div>
    <a href="{{ route('naissances.create') }}" class="btn-cuni primary">
        <i class="bi bi-plus-lg"></i> Nouvelle naissance
    </a>
</div>

{{-- ✅ BARRE DE RECHERCHE AVANCÉE --}}
<div class="cuni-card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('naissances.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
                
                {{-- Recherche texte --}}
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                        <i class="bi bi-search" style="margin-right: 4px;"></i> Recherche
                    </label>
                    <input type="text" 
                           name="search" 
                           value="{{ request()->get('search') }}"
                           class="form-control"
                           placeholder="Nom ou code femelle..."
                           style="border-radius: var(--radius-lg);">
                </div>
                
                {{-- Filtre date début --}}
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                        <i class="bi bi-calendar-event" style="margin-right: 4px;"></i> Du
                    </label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request()->get('date_from') }}"
                           class="form-control"
                           style="border-radius: var(--radius-lg);">
                </div>
                
                {{-- Filtre date fin --}}
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                        <i class="bi bi-calendar-check" style="margin-right: 4px;"></i> Au
                    </label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request()->get('date_to') }}"
                           class="form-control"
                           style="border-radius: var(--radius-lg);">
                </div>
                
                {{-- Filtre état de santé --}}
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                        <i class="bi bi-heart-pulse" style="margin-right: 4px;"></i> Santé
                    </label>
                    <select name="etat_sante" class="form-select" style="border-radius: var(--radius-lg);">
                        <option value="">Tous</option>
                        <option value="Excellent" {{ request('etat_sante') === 'Excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="Bon" {{ request('etat_sante') === 'Bon' ? 'selected' : '' }}>Bon</option>
                        <option value="Moyen" {{ request('etat_sante') === 'Moyen' ? 'selected' : '' }}>Moyen</option>
                        <option value="Faible" {{ request('etat_sante') === 'Faible' ? 'selected' : '' }}>Faible</option>
                    </select>
                </div>
                
                {{-- Filtre vérification sexe --}}
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                        <i class="bi bi-gender-ambiguous" style="margin-right: 4px;"></i> Vérification
                    </label>
                    <select name="sex_verified" class="form-select" style="border-radius: var(--radius-lg);">
                        <option value="">Tous</option>
                        <option value="verified" {{ request('sex_verified') === 'verified' ? 'selected' : '' }}>Vérifié</option>
                        <option value="pending" {{ request('sex_verified') === 'pending' ? 'selected' : '' }}>En attente</option>
                    </select>
                </div>
                
                {{-- Boutons d'action --}}
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-cuni primary" style="flex: 1; padding: 10px;">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                    @if(request()->anyFilled(['search', 'date_from', 'date_to', 'etat_sante', 'sex_verified']))
                        <a href="{{ route('naissances.index') }}" 
                           class="btn-cuni secondary" 
                           style="padding: 10px 16px;"
                           title="Réinitialiser les filtres">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            {{-- Résumé des filtres actifs --}}
            @if(request()->anyFilled(['search', 'date_from', 'date_to', 'etat_sante', 'sex_verified']))
                <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--surface-border);">
                    <small style="color: var(--text-tertiary);">
                        Filtres actifs :
                        @if(request('search'))
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('date_from'))
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                Du: {{ request('date_from') }}
                            </span>
                        @endif
                        @if(request('date_to'))
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                Au: {{ request('date_to') }}
                            </span>
                        @endif
                        @if(request('etat_sante'))
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                {{ request('etat_sante') }}
                            </span>
                        @endif
                        @if(request('sex_verified') === 'verified')
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                Vérifié
                            </span>
                        @elseif(request('sex_verified') === 'pending')
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                En attente
                            </span>
                        @endif
                        <a href="{{ route('naissances.index') }}" style="color: var(--accent-red); margin-left: 8px; text-decoration: none;">
                            <i class="bi bi-x-circle"></i> Tout effacer
                        </a>
                    </small>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- ✅ STATS GRID --}}
<div class="naissances-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; width: 100%;">
    <div class="cuni-card" style="margin-bottom: 0;">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total naissances</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(59, 130, 246, 0.1)">
                    <i class="bi bi-egg text-blue-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="cuni-card" style="margin-bottom: 0;">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Ce mois-ci</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['this_month'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(16, 185, 129, 0.1)">
                    <i class="bi bi-calendar-check text-green-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="cuni-card" style="margin-bottom: 0;">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Lapereaux vivants</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['nb_vivant_total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(139, 92, 246, 0.1)">
                    <i class="bi bi-heart-pulse text-purple-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="cuni-card" style="margin-bottom: 0;">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Taux de survie</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['taux_survie_moyen'], 1) }}%</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(245, 158, 11, 0.1)">
                    <i class="bi bi-graph-up text-amber-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="cuni-card" style="margin-bottom: 0;">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">À vérifier</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['pending_verification'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(239, 68, 68, 0.1)">
                    <i class="bi bi-exclamation-triangle text-red-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ MAIN TABLE CARD --}}
<div class="cuni-card" style="width: 100%; max-width: 100%;">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-list-ul"></i> Historique des naissances
        </h3>
    </div>
    <div class="card-body" style="width: 100%; overflow-x: auto;">
        <div class="table-responsive" style="width: 100%; min-width: 100%;">
            <table class="table table-hover align-middle mb-0" style="width: 100%; min-width: 1200px;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-muted fw-semibold small">Date</th>
                        <th class="text-uppercase text-muted fw-semibold small">Femelle</th>
                        <th class="text-uppercase text-muted fw-semibold small">Vivants</th>
                        <th class="text-uppercase text-muted fw-semibold small">Mort-nés</th>
                        <th class="text-uppercase text-muted fw-semibold small">Total</th>
                        <th class="text-uppercase text-muted fw-semibold small">Santé</th>
                        <th class="text-uppercase text-muted fw-semibold small">Vérification</th>
                        <th class="text-uppercase text-muted fw-semibold small">Sevrage</th>
                        <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($naissances as $naissance)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-dark">
                            @if ($naissance->miseBas?->date_mise_bas)
                                {{ \Carbon\Carbon::parse($naissance->miseBas->date_mise_bas)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                            @if ($naissance->heure_naissance)
                                <small class="text-muted">
                                    ({{ is_string($naissance->heure_naissance) ? $naissance->heure_naissance : \Carbon\Carbon::parse($naissance->heure_naissance)->format('H:i') }})
                                </small>
                            @endif
                        </td>
                        <td>
                            {{ $naissance->femelle->nom ?? 'N/A' }}
                            <small class="text-muted">({{ $naissance->femelle->code ?? '-' }})</small>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                {{ $naissance->nb_vivant }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $naissance->nb_mort_ne }}</td>
                        <td class="fw-semibold">{{ $naissance->nb_total }}</td>
                        <td>
                            @php
                            $healthColors = [
                                'Excellent' => 'rgba(16, 185, 129, 0.1); color: #10B981;',
                                'Bon' => 'rgba(59, 130, 246, 0.1); color: #3B82F6;',
                                'Moyen' => 'rgba(245, 158, 11, 0.1); color: #F59E0B;',
                                'Faible' => 'rgba(239, 68, 68, 0.1); color: #EF4444;',
                            ];
                            @endphp
                            <span class="badge" style="background: {{ $healthColors[$naissance->etat_sante] ?? $healthColors['Bon'] }}">
                                {{ $naissance->etat_sante }}
                            </span>
                        </td>
                        <td>
                            @if ($naissance->sex_verified)
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                    <i class="bi bi-check-circle"></i> Vérifié
                                </span>
                            @elseif($naissance->reminder_count > 0)
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                    <i class="bi bi-exclamation-triangle"></i> {{ $naissance->reminder_count }} rappel(s)
                                </span>
                            @else
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                                    <i class="bi bi-clock"></i> En attente
                                </span>
                            @endif
                        </td>
                        <td class="text-muted">
                            {{ $naissance->date_sevrage_prevue ? $naissance->date_sevrage_prevue->format('d/m/Y') : '-' }}
                            @if (($naissance->jours_avant_sevrage ?? 0) > 0 && ($naissance->jours_avant_sevrage ?? 0) <= 7)
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; font-size: 10px;">
                                    J-{{ $naissance->jours_avant_sevrage }}
                                </span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="action-buttons">
                                <a href="{{ route('naissances.show', $naissance) }}" class="btn-cuni sm secondary" title="Détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('naissances.edit', $naissance) }}" class="btn-cuni sm secondary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('naissances.destroy', $naissance) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-cuni sm danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="table-empty-state">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune naissance trouvée avec ces filtres</p>
                                <a href="{{ route('naissances.index') }}" class="btn-cuni secondary mt-2" style="margin-right: 8px;">
                                    <i class="bi bi-x-circle"></i> Effacer les filtres
                                </a>
                                <a href="{{ route('naissances.create') }}" class="btn-cuni primary mt-2">
                                    <i class="bi bi-plus-lg"></i> Première naissance
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($naissances->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border); flex-wrap: wrap; gap: 16px;">
            <div class="text-muted" style="font-size: 13px; white-space: nowrap;">
                Affichage de <strong>{{ $naissances->firstItem() }}</strong> à <strong>{{ $naissances->lastItem() }}</strong> sur <strong>{{ $naissances->total() }}</strong> naissances
            </div>
            {{ $naissances->links('pagination.bootstrap-5-sm') }}
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* Animation du champ de recherche au focus */
input[name="search"]:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px var(--primary-subtle) !important;
    transition: all 0.2s ease;
}

/* Effet hover sur les boutons de filtre */
.btn-cuni.secondary:hover {
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Badge de résultat animé */
.badge {
    transition: all 0.2s ease;
}
.badge:hover {
    transform: scale(1.05);
}

/* Responsive : empiler sur mobile */
@media (max-width: 768px) {
    form[method="GET"] > div {
        grid-template-columns: 1fr !important;
    }
}

/* ✅ FORCE DESKTOP LAYOUT */
@media (min-width: 768px) {
    .naissances-stats-grid {
        grid-template-columns: repeat(5, 1fr) !important;
    }
    .cuni-card {
        width: 100% !important;
        max-width: 100% !important;
    }
}

/* ✅ MOBILE LAYOUT */
@media (max-width: 767px) {
    .naissances-stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
    }
    .page-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 16px !important;
    }
}

/* ✅ VERY SMALL MOBILE */
@media (max-width: 480px) {
    .naissances-stats-grid {
        grid-template-columns: 1fr !important;
    }
    .text-2xl {
        font-size: 1.5rem !important;
    }
}
</style>
@endpush
@endsection