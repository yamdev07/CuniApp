@extends('layouts.cuniapp')

@section('title', 'Naissances - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-star"></i>
            Gestion des Naissances
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Naissances</span>
        </div>
    </div>
    <a href="{{ route('naissances.create') }}" class="btn-cuni primary">
        <i class="bi bi-plus-lg"></i>
        Ajouter une naissance
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
            Liste des Naissances
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-muted fw-semibold small">ID</th>
                        <th class="text-uppercase text-muted fw-semibold small">Nom</th>
                        <th class="text-uppercase text-muted fw-semibold small">Sexe</th>
                        <th class="text-uppercase text-muted fw-semibold small">Date de naissance</th>
                        <th class="text-uppercase text-muted fw-semibold small">Poids (kg)</th>
                        <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($naissances as $naissance)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-dark">#{{ $naissance->id }}</td>
                        <td>{{ $naissance->nom_lapin }}</td>
                        <td>
                            @if($naissance->sexe === 'M')
                                <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">Mâle</span>
                            @else
                                <span class="badge" style="background: rgba(236, 72, 153, 0.1); color: #EC4899;">Femelle</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ date('d/m/Y', strtotime($naissance->date_naissance)) }}</td>
                        <td class="text-muted">{{ $naissance->poids }}</td>
                        <td class="pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('naissances.show', $naissance->id) }}" 
                                   class="btn-cuni sm secondary" 
                                   title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('naissances.edit', $naissance->id) }}" 
                                   class="btn-cuni sm secondary" 
                                   title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('naissances.destroy', $naissance->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-cuni sm danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Voulez-vous vraiment supprimer cette naissance ?')">
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
                            <p class="mt-2">Aucune naissance enregistrée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection