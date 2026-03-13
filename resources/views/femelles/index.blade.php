{{-- resources/views/femelles/index.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Femelles - CuniApp Élevage')
@section('content')

    @if (session('success'))
        <div class="alert-cuni success" style="margin-bottom: 24px;">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert-cuni warning" style="margin-bottom: 24px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('warning') }}</div>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-arrow-down-right-square"></i>
                Gestion des Femelles
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Femelles</span>
            </div>
        </div>
        <a href="{{ route('femelles.create') }}" class="btn-cuni primary">
            <i class="bi bi-plus-lg"></i>
            Ajouter une femelle
        </a>
    </div>

    {{-- ✅ BARRE DE RECHERCHE AVANCÉE STYLISÉE CuniApp --}}
    <div class="cuni-card" style="margin-bottom: 20px;">
        <div class="card-body" style="padding: 16px;">
            <form method="GET" action="{{ route('femelles.index') }}">
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
                               placeholder="Nom, code, race..."
                               style="border-radius: var(--radius-lg); padding-left: 36px; position: relative;">
                    </div>
                    
                    {{-- Filtre par état --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-filter" style="margin-right: 4px;"></i> État
                        </label>
                        <select name="etat" class="form-select" style="border-radius: var(--radius-lg);">
                            <option value="">Tous les états</option>
                            <option value="Active" {{ request('etat') === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Gestante" {{ request('etat') === 'Gestante' ? 'selected' : '' }}>Gestante</option>
                            <option value="Allaitante" {{ request('etat') === 'Allaitante' ? 'selected' : '' }}>Allaitante</option>
                            <option value="Vide" {{ request('etat') === 'Vide' ? 'selected' : '' }}>Vide</option>
                            <option value="vendu" {{ request('etat') === 'vendu' ? 'selected' : '' }}>Vendu</option>
                        </select>
                    </div>
                    
                    {{-- Filtre par origine --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-geo-alt" style="margin-right: 4px;"></i> Origine
                        </label>
                        <select name="origine" class="form-select" style="border-radius: var(--radius-lg);">
                            <option value="">Toutes</option>
                            <option value="Interne" {{ request('origine') === 'Interne' ? 'selected' : '' }}>Interne</option>
                            <option value="Achat" {{ request('origine') === 'Achat' ? 'selected' : '' }}>Achat</option>
                        </select>
                    </div>
                    
                    {{-- Boutons d'action --}}
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-cuni primary" style="flex: 1; padding: 10px;">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        @if(request()->anyFilled(['search', 'etat', 'origine']))
                            <a href="{{ route('femelles.index') }}" 
                               class="btn-cuni secondary" 
                               style="padding: 10px 16px;"
                               title="Réinitialiser les filtres">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                {{-- Résumé des filtres actifs --}}
                @if(request()->anyFilled(['search', 'etat', 'origine']))
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--surface-border);">
                        <small style="color: var(--text-tertiary);">
                            Filtres actifs :
                            @if(request('search'))
                                <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    🔍 "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('etat'))
                                <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    📊 {{ request('etat') }}
                                </span>
                            @endif
                            @if(request('origine'))
                                <span class="badge" style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    📍 {{ request('origine') }}
                                </span>
                            @endif
                            <a href="{{ route('femelles.index') }}" style="color: var(--accent-red); margin-left: 8px; text-decoration: none;">
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
                Liste des Femelles
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold small">Code</th>
                            <th class="text-uppercase text-muted fw-semibold small">Nom</th>
                            <th class="text-uppercase text-muted fw-semibold small">Race</th>
                            <th class="text-uppercase text-muted fw-semibold small">Origine</th>
                            <th class="text-uppercase text-muted fw-semibold small">Naissance</th>
                            <th class="text-uppercase text-muted fw-semibold small">État</th>
                            <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($femelles as $femelle)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 fw-semibold text-dark">{{ $femelle->code }}</td>
                                <td>{{ $femelle->nom }}</td>
                                <td>
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                        {{ $femelle->race ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $femelle->origine }}</td>
                                <td class="text-muted">{{ date('d/m/Y', strtotime($femelle->date_naissance)) }}</td>
                                
                                {{-- ✅ Badge d'état stylisé --}}
                                <td>
                                    <form action="{{ route('femelles.toggleEtat', $femelle->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="badge border-0" 
                                                style="
                                                    {{ $femelle->etat === 'vendu' 
                                                        ? 'background: rgba(245, 158, 11, 0.15); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3);' 
                                                        : ($femelle->etat === 'Active' 
                                                            ? 'background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3);' 
                                                            : ($femelle->etat === 'Gestante' || $femelle->etat === 'Allaitante'
                                                                ? 'background: rgba(139, 92, 246, 0.15); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.3);' 
                                                                : 'background: rgba(107, 114, 128, 0.15); color: #6B7280; border: 1px solid rgba(107, 114, 128, 0.3);'
                                                            )
                                                        )
                                                    }}
                                                    font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; cursor: pointer;
                                                ">
                                            @if($femelle->etat === 'vendu')
                                                <i class="bi bi-check-circle-fill" style="margin-right: 4px;"></i> Vendu
                                            @elseif($femelle->etat === 'Active')
                                                <i class="bi bi-check-circle" style="margin-right: 4px;"></i> Active
                                            @elseif(in_array($femelle->etat, ['Gestante', 'Allaitante']))
                                                <i class="bi bi-egg-fill" style="margin-right: 4px;"></i> {{ $femelle->etat }}
                                            @else
                                                <i class="bi bi-pause-circle" style="margin-right: 4px;"></i> {{ $femelle->etat }}
                                            @endif
                                        </button>
                                    </form>
                                </td>

                                <td class="pe-4">
                                    <div class="action-buttons">
                                         <a href="{{ route('femelles.show', $femelle->id) }}" class="btn-cuni sm secondary"
                                            title="Détails"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('femelles.edit', $femelle->id) }}" class="btn-cuni sm secondary"
                                            title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('femelles.destroy', $femelle->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-cuni sm danger" title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette femelle ?')">
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
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-tertiary);"></i>
                                        <p class="mt-3 text-gray-600 dark:text-gray-400">
                                            @if(request('search') || request('etat') || request('origine'))
                                                Aucune femelle ne correspond à vos critères de recherche
                                            @else
                                                Aucune femelle enregistrée pour le moment
                                            @endif
                                        </p>
                                        <a href="{{ route('femelles.create') }}" class="btn-cuni primary mt-3">
                                            <i class="bi bi-plus-lg"></i> Ajouter une femelle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($femelles->hasPages())
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
                    <div class="text-muted" style="font-size: 13px;">
                        Affichage de <strong>{{ $femelles->firstItem() }}</strong> à
                        <strong>{{ $femelles->lastItem() }}</strong> sur <strong>{{ $femelles->total() }}</strong> femelles
                    </div>
                    @if ($femelles->hasPages())
                        {{ $femelles->links('pagination.bootstrap-5-sm') }}
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
        
        /* Effet hover sur les boutons */
        .btn-cuni.secondary:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        
        /* Badge animé */
        .badge {
            transition: all 0.2s ease;
        }
        .badge:hover {
            transform: scale(1.05);
        }
        
        /* Responsive : empiler sur mobile */
        @media (max-width: 768px) {
            form[method="GET"] > div:first-child {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    @endpush

@endsection