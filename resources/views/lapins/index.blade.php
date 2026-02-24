@extends('layouts.cuniapp')

@section('title', 'Lapins - CuniApp Élevage')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-arrow-down-right-square"></i>
                Gestion des Lapins
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Femelles et Mâles</span>
            </div>
        </div>
        <a href="{{ route('lapins.create') }}" class="btn-cuni primary">
            <i class="bi bi-plus-lg"></i>
            Ajouter un lapin
        </a>
    </div>


    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-list-ul"></i>
                Liste des lapins
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
                                <td class="text-muted">{{ date('d/m/Y', strtotime($femelle->date_naissance)) }}
                                </td>
                                <td>
                                    <form action="{{ route('femelles.toggleEtat', $femelle->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="badge border-0 status-{{ strtolower($femelle->etat) }}">
                                            {{ $femelle->etat }}
                                        </button>
                                    </form>
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons">
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
                                        <i class="bi bi-inbox"></i>
                                        <p>Aucune femelle enregistré pour le moment</p>
                                        <a href="{{ route('femelles.create') }}" class="btn-cuni primary">
                                            <i class="bi bi-plus-lg"></i> Ajouter une femelle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse


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
                                        @csrf @method('PATCH')
                                        <button type="submit" class="badge border-0 status-{{ strtolower($m->etat) }}">
                                            {{ $m->etat }}
                                        </button>
                                    </form>
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons">
                                        <a href="{{ route('males.edit', $m->id) }}" class="btn-cuni sm secondary"
                                            title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('males.destroy', $m->id) }}" method="POST"
                                            style="display:inline;">
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
                                        <p>Aucun mâle enregistré pour le moment</p>
                                        <a href="{{ route('males.create') }}" class="btn-cuni primary">
                                            <i class="bi bi-plus-lg"></i> Ajouter un mâle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse



                    </tbody>
                </table>
            </div>

            @if ($femelles->hasPages())
                <div class="pagination-wrapper mb-3">
                    <div class="text-muted small">
                        Femelles : <strong>{{ $femelles->firstItem() }}-{{ $femelles->lastItem() }}</strong> sur
                        {{ $femelles->total() }}
                    </div>
                    {{ $femelles->appends(['males_page' => request('males_page')])->links('vendor.pagination.bootstrap-5-sm') }}
                </div>
            @endif

            <hr class="my-3 opacity-25">

            {{-- Pagination pour les Mâles --}}
            @if ($males->hasPages())
                <div class="pagination-wrapper">
                    <div class="text-muted small">
                        Mâles : <strong>{{ $males->firstItem() }}-{{ $males->lastItem() }}</strong> sur
                        {{ $males->total() }}
                    </div>
                    {{ $males->appends(['femelles_page' => request('femelles_page')])->links('vendor.pagination.bootstrap-5-sm') }}
                </div>
            @endif
        </div>
    </div>
@endsection
