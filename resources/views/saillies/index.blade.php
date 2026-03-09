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
                    @forelse($saillies as $s)
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
                                {{-- ✅ EYE ICON - View Details --}}
                                <a href="{{ route('saillies.show', $s) }}" class="btn-cuni sm secondary" title="Détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('saillies.edit', $s) }}" class="btn-cuni sm secondary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                {{-- Delete --}}
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
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Aucune saillie enregistrée</p>
                            <a href="{{ route('saillies.create') }}" class="btn-cuni primary mt-3">
                                <i class="bi bi-plus-lg"></i> Enregistrer une première saillie
                            </a>
                        </td>
                    </tr>
                    @endforelse
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
@endsection