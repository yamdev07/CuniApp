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
            Liste des Mises Bas
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-muted fw-semibold small">Femelle</th>
                        <th class="text-uppercase text-muted fw-semibold small">Date</th>
                        <th class="text-uppercase text-muted fw-semibold small">Jeunes Vivants</th>
                        <th class="text-uppercase text-muted fw-semibold small">Morts-nés</th>
                        <th class="text-uppercase text-muted fw-semibold small">Sevrage</th>
                        <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($misesBas as $mb)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-dark">{{ $mb->femelle->nom ?? '-' }}</td>
                        <td class="text-muted">{{ date('d/m/Y', strtotime($mb->date_mise_bas)) }}</td>
                        <td>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                {{ $mb->nb_vivant }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $mb->nb_mort_ne }}</td>
                        <td class="text-muted">{{ $mb->date_sevrage ? date('d/m/Y', strtotime($mb->date_sevrage)) : '-' }}</td>
                        <td class="pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('mises-bas.edit', $mb->id) }}" 
                                   class="btn-cuni sm secondary" 
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('mises-bas.destroy', $mb->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-cuni sm danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette mise bas ?')">
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
                            <p class="mt-2">Aucune mise bas enregistrée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($misesBas->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
            <div class="text-muted" style="font-size: 13px;">
                Affichage de <strong>{{ $misesBas->firstItem() }}</strong> à <strong>{{ $misesBas->lastItem() }}</strong> sur <strong>{{ $misesBas->total() }}</strong> mises bas
            </div>
            <nav>
                {{ $misesBas->links('vendor.pagination.bootstrap-5-sm') }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection