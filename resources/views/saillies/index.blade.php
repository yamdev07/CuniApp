@extends('layouts.cuniapp')

@section('title', 'Saillies - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-heart"></i>
            Gestion des Saillies
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Saillies</span>
        </div>
    </div>
    <a href="{{ route('saillies.create') }}" class="btn-cuni primary">
        <i class="bi bi-plus-lg"></i>
        Ajouter une saillie
    </a>
</div>

@if(session('success'))
<div class="alert-cuni success">
    <i class="bi bi-check-circle-fill"></i>
    <div>{{ session('success') }}</div>
</div>
@endif

<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-list-ul"></i>
            Liste des Saillies
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
                        <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($saillies as $s)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-dark">{{ $s->femelle->nom ?? '-' }}</td>
                        <td>{{ $s->male->nom ?? '-' }}</td>
                        <td class="text-muted">{{ date('d/m/Y', strtotime($s->date_saillie)) }}</td>
                        <td class="text-muted">{{ $s->date_palpage ? date('d/m/Y', strtotime($s->date_palpage)) : '-' }}</td>
                        <td>
                            @if($s->palpation_resultat === '+')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">Positif</span>
                            @elseif($s->palpation_resultat === '-')
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">Négatif</span>
                            @else
                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7280;">En attente</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('saillies.edit', $s) }}" 
                                   class="btn-cuni sm secondary" 
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('saillies.destroy', $s) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-cuni sm danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette saillie ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Aucune saillie enregistrée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($saillies->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
            <div class="text-muted" style="font-size: 13px;">
                Affichage de <strong>{{ $saillies->firstItem() }}</strong> à <strong>{{ $saillies->lastItem() }}</strong> sur <strong>{{ $saillies->total() }}</strong> saillies
            </div>
            <nav>
                {{ $saillies->links('vendor.pagination.bootstrap-5-sm') }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection