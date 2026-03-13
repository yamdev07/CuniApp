@extends('layouts.cuniapp')

@section('title', 'Mises Bas - CuniApp Élevage')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-egg"></i>
                Gestion des Mises Bas
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Mises Bas</span>
            </div>
        </div>
        <a href="{{ route('mises-bas.create') }}" class="btn-cuni primary">
            <i class="bi bi-plus-lg"></i>
            Ajouter une mise bas
        </a>
    </div>

    {{-- ✅ BARRE DE RECHERCHE AVANCÉE --}}
    <div class="cuni-card" style="margin-bottom: 20px;">
        <div class="card-body" style="padding: 16px;">
            <form method="GET" action="{{ route('mises-bas.index') }}">
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
                    
                    {{-- Boutons d'action --}}
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-cuni primary" style="flex: 1; padding: 10px;">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        @if(request()->anyFilled(['search', 'date_from', 'date_to']))
                            <a href="{{ route('mises-bas.index') }}" 
                               class="btn-cuni secondary" 
                               style="padding: 10px 16px;"
                               title="Réinitialiser les filtres">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                {{-- Résumé des filtres actifs --}}
                @if(request()->anyFilled(['search', 'date_from', 'date_to']))
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
                            <a href="{{ route('mises-bas.index') }}" style="color: var(--accent-red); margin-left: 8px; text-decoration: none;">
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
                <i class="bi bi-list-ul"></i>
                Liste des Mises Bas
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold small">Femelle</th>
                            <th class="text-uppercase text-muted fw-semibold small">Date Mise Bas</th>
                            <th class="text-uppercase text-muted fw-semibold small">Jeunes Vivants</th>
                            <th class="text-uppercase text-muted fw-semibold small">Morts-nés</th>
                            <th class="text-uppercase text-muted fw-semibold small">Total</th>
                            <th class="text-uppercase text-muted fw-semibold small">Sevrage</th>
                            <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($misesBas as $miseBas)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $miseBas->femelle->nom ?? 'N/A' }}
                                    <small class="text-muted">({{ $miseBas->femelle->code ?? '-' }})</small>
                                </td>
                                <td class="text-muted">
                                    {{ date('d/m/Y', strtotime($miseBas->date_mise_bas)) }}
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                        {{ $miseBas->nb_vivant }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    {{ $miseBas->nb_mort_ne ?? 0 }}
                                </td>
                                <td class="fw-semibold">
                                    {{ ($miseBas->nb_vivant ?? 0) + ($miseBas->nb_mort_ne ?? 0) }}
                                </td>
                                <td class="text-muted">
                                    {{ $miseBas->date_sevrage ? date('d/m/Y', strtotime($miseBas->date_sevrage)) : '-' }}
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons">
                                        <a href="{{ route('mises-bas.show', $miseBas->id) }}" class="btn-cuni sm secondary"
                                            title="Détails"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('mises-bas.edit', $miseBas->id) }}" class="btn-cuni sm secondary"
                                            title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('mises-bas.destroy', $miseBas->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-cuni sm danger" title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette mise bas ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="table-empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Aucune mise bas enregistrée pour le moment</p>
                                        <a href="{{ route('mises-bas.create') }}" class="btn-cuni primary">
                                            <i class="bi bi-plus-lg"></i> Ajouter une mise bas
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($misesBas->hasPages())
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
                    <div class="text-muted" style="font-size: 13px;">
                        Affichage de <strong>{{ $misesBas->firstItem() }}</strong> à
                        <strong>{{ $misesBas->lastItem() }}</strong> sur <strong>{{ $misesBas->total() }}</strong> mises
                        bas
                    </div>
                    @if ($misesBas->hasPages())
                        {{ $misesBas->links('pagination.bootstrap-5-sm') }}
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