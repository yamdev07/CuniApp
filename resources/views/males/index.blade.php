@extends('layouts.cuniapp')

@section('title', 'Mâles - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-arrow-up-right-square"></i>
            Gestion des Mâles
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Mâles</span>
        </div>
    </div>
    <a href="{{ route('males.create') }}" class="btn-cuni primary">
        <i class="bi bi-plus-lg"></i>
        Ajouter un mâle
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
            Liste des Mâles
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
                    @forelse($males as $m)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-dark">{{ $m->code }}</td>
                        <td>{{ $m->nom }}</td>
                        <td>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ $m->race ?? '-' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $m->origine }}</td>
                        <td class="text-muted">{{ date('d/m/Y', strtotime($m->date_naissance)) }}</td>
                        <td>
                            <form action="{{ route('males.toggleEtat', $m->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="badge border-0" 
                                    style="background: {{ $m->etat === 'Active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(107, 114, 128, 0.1)' }}; 
                                           color: {{ $m->etat === 'Active' ? '#10B981' : '#6B7280' }};">
                                    {{ $m->etat }}
                                </button>
                            </form>
                        </td>
                        <td class="pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('males.edit', $m->id) }}" 
                                   class="btn-cuni sm secondary" 
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('males.destroy', $m->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-cuni sm danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce mâle ?')">
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
                            <p class="mt-2">Aucun mâle enregistré</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($males->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
            <div class="text-muted" style="font-size: 13px;">
                Affichage de <strong>{{ $males->firstItem() }}</strong> à <strong>{{ $males->lastItem() }}</strong> sur <strong>{{ $males->total() }}</strong> mâles
            </div>
            <nav>
                {{ $males->links('vendor.pagination.bootstrap-5-sm') }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection