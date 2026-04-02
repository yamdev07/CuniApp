{{-- resources/views/saillies/index.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Saillies - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-heart"></i> Gestion des Saillies
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Saillies</span>
        </div>
    </div>
    <a href="{{ route('saillies.create') }}" class="btn-cuni primary">
        <i class="bi bi-plus-lg"></i> Ajouter une saillie
    </a>
</div>

{{-- ✅ BARRE DE RECHERCHE AVANCÉE --}}
<div class="cuni-card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px;">
        <form method="GET" action="{{ route('saillies.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
                
                {{-- Recherche texte --}}
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                        <i class="bi bi-search" style="margin-right: 4px;"></i> Recherche
                    </label>
                    <input type="text" 
                           name="search" 
                           value="{{ request()->get('search') }}"
                           class="form-control"
                           placeholder="Nom ou code femelle/mâle..."
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
                
                {{-- Filtre résultat palpation --}}
                <div>
                    <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                        <i class="bi bi-filter-circle" style="margin-right: 4px;"></i> Résultat
                    </label>
                    <select name="resultat" class="form-select" style="border-radius: var(--radius-lg);">
                        <option value="all" {{ request('resultat') === 'all' || !request()->has('resultat') ? 'selected' : '' }}>Tous</option>
                        <option value="+" {{ request('resultat') === '+' ? 'selected' : '' }}>Positif</option>
                        <option value="-" {{ request('resultat') === '-' ? 'selected' : '' }}>Négatif</option>
                        <option value="attente" {{ request('resultat') === 'attente' ? 'selected' : '' }}>En attente</option>
                    </select>
                </div>
                
                {{-- Boutons d'action --}}
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-cuni primary" style="flex: 1; padding: 10px;">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                    @if(request()->anyFilled(['search', 'date_from', 'date_to', 'resultat']))
                        <a href="{{ route('saillies.index') }}" 
                           class="btn-cuni secondary" 
                           style="padding: 10px 16px;"
                           title="Réinitialiser les filtres">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            {{-- Résumé des filtres actifs --}}
            @if(request()->anyFilled(['search', 'date_from', 'date_to', 'resultat']))
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
                        @if(request('resultat') === '+')
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                Positif
                            </span>
                        @elseif(request('resultat') === '-')
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                Négatif
                            </span>
                        @elseif(request('resultat') === 'attente')
                            <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                En attente
                            </span>
                        @endif
                        <a href="{{ route('saillies.index') }}" style="color: var(--accent-red); margin-left: 8px; text-decoration: none;">
                            <i class="bi bi-x-circle"></i> Tout effacer
                        </a>
                    </small>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-list-ul"></i> Liste des Saillies
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-muted fw-semibold small">Femelle</th>
                        <th class="text-uppercase text-muted fw-semibold small">Mâle</th>
                        <th class="text-uppercase text-muted fw-semibold small">Date Saillie</th>
                        <th class="text-uppercase text-muted fw-semibold small">Date Palpation</th>
                        <th class="text-uppercase text-muted fw-semibold small">Résultat</th>
                        <th class="text-uppercase text-muted fw-semibold small">Mise Bas Théorique</th>
                        <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($saillies) > 0)
                        @foreach($saillies as $s)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-dark">
                            <a href="{{ route('femelles.show', $s->femelle_id) }}" style="color: var(--primary); text-decoration: none;">
                                {{ $s->femelle->nom ?? '-' }}
                            </a>
                            <small class="text-muted">({{ $s->femelle->code ?? '-' }})</small>
                        </td>
                        <td>
                            <a href="{{ route('males.show', $s->male_id) }}" style="color: var(--primary); text-decoration: none;">
                                {{ $s->male->nom ?? '-' }}
                            </a>
                            <small class="text-muted">({{ $s->male->code ?? '-' }})</small>
                        </td>
                        <td class="text-muted">
                            {{ $s->date_saillie ? \Carbon\Carbon::parse($s->date_saillie)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-muted">
                            {{ $s->date_palpage ? \Carbon\Carbon::parse($s->date_palpage)->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            @if ($s->palpation_resultat === '+')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                    <i class="bi bi-check-circle"></i> Positif
                                </span>
                            @elseif($s->palpation_resultat === '-')
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                    <i class="bi bi-x-circle"></i> Négatif
                                </span>
                            @else
                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7D95;">
                                    <i class="bi bi-clock"></i> En attente
                                </span>
                            @endif
                        </td>
                        <td class="text-muted">
                            {{ $s->date_mise_bas_theorique ? \Carbon\Carbon::parse($s->date_mise_bas_theorique)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="pe-4">
                            <div class="action-buttons">
                                <a href="{{ route('saillies.show', $s) }}" class="btn-cuni sm secondary" title="Détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('saillies.edit', $s) }}" class="btn-cuni sm secondary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('saillies.destroy', $s) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-cuni sm danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette saillie ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Aucune saillie trouvée avec ces filtres</p>
                            <a href="{{ route('saillies.index') }}" class="btn-cuni secondary mt-2" style="margin-right: 8px;">
                                <i class="bi bi-x-circle"></i> Effacer les filtres
                            </a>
                            <a href="{{ route('saillies.create') }}" class="btn-cuni primary mt-2">
                                <i class="bi bi-plus-lg"></i> Enregistrer une saillie
                            </a>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if ($saillies->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
            <div class="text-muted" style="font-size: 13px;">
                Affichage de <strong>{{ $saillies->firstItem() }}</strong> à <strong>{{ $saillies->lastItem() }}</strong> sur <strong>{{ $saillies->total() }}</strong> saillies
            </div>
            @if ($saillies->hasPages())
                {{ $saillies->links('pagination.bootstrap-5-sm') }}
            @endif
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
</style>
@endpush
@endsection