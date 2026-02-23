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
                    @forelse($femelles as $f)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-dark">{{ $f->code }}</td>
                        <td>{{ $f->nom }}</td>
                        <td>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ $f->race ?? '-' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $f->origine }}</td>
                        <td class="text-muted">{{ date('d/m/Y', strtotime($f->date_naissance)) }}</td>
                        <td>
                            <form action="{{ route('femelles.toggleEtat', $f->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="badge border-0" 
                                    style="background: {{ $f->etat === 'Active' ? 'rgba(16, 185, 129, 0.1)' : ($f->etat === 'Gestante' ? 'rgba(245, 158, 11, 0.1)' : ($f->etat === 'Allaitante' ? 'rgba(59, 130, 246, 0.1)' : 'rgba(107, 114, 128, 0.1)')) }}; 
                                           color: {{ $f->etat === 'Active' ? '#10B981' : ($f->etat === 'Gestante' ? '#F59E0B' : ($f->etat === 'Allaitante' ? '#3B82F6' : '#6B7280')) }};">
                                    {{ $f->etat }}
                                </button>
                            </form>
                        </td>
                        <td class="pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('femelles.edit', $f->id) }}" 
                                   class="btn-cuni sm secondary" 
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('femelles.destroy', $f->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-cuni sm danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette femelle ?')">
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
                            <p class="mt-2">Aucune femelle enregistrée</p>
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