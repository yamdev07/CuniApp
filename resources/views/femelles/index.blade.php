@extends('layouts.cuniapp')

@section('title', 'Femelles - CuniApp Élevage')

@section('content')
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
            <td>
                <form action="{{ route('femelles.toggleEtat', $femelle->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="badge border-0 status-{{ strtolower($femelle->etat) }}">
                        {{ $femelle->etat }}
                    </button>
                </form>
            </td>
            <td class="pe-4">
                <div class="action-buttons">
                    <a href="{{ route('femelles.edit', $femelle->id) }}" class="btn-cuni sm secondary" title="Modifier">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('femelles.destroy', $femelle->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-cuni sm danger" title="Supprimer" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce mâle ?')">
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
                    <p>Aucune femelle enregistré pour le moment</p>
                    <a href="{{ route('femelles.create') }}" class="btn-cuni primary">
                        <i class="bi bi-plus-lg"></i> Ajouter une femelle
                    </a>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>

        @if($femelles->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
            <div class="text-muted" style="font-size: 13px;">
                Affichage de <strong>{{ $femelles->firstItem() }}</strong> à <strong>{{ $femelles->lastItem() }}</strong> sur <strong>{{ $femelles->total() }}</strong> femelles
            </div>
            <nav>
                {{ $femelles->links('vendor.pagination.bootstrap-5-sm') }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection