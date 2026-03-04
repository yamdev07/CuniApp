@extends('layouts.cuniapp')
@section('title', 'Détails Naissance - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-egg-fill"></i> Détails de la Naissance #{{ $naissance->id }}</h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a> <span>/</span>
            <a href="{{ route('naissances.index') }}">Naissances</a> <span>/</span> <span>#{{ $naissance->id }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('naissances.edit', $naissance) }}" class="btn-cuni primary"><i class="bi bi-pencil"></i> Modifier</a>
        <a href="{{ route('naissances.index') }}" class="btn-cuni secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-info-circle"></i> Informations Principales</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Femelle</label>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold">{{ $naissance->femelle->nom ?? 'N/A' }}</span>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">{{ $naissance->femelle->code ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date & Heure</label>
                        <p>{{ $naissance->date_naissance->format('d/m/Y') }} @if($naissance->heure_naissance) à {{ $naissance->heure_naissance->format('H:i') }} @endif</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">État de santé</label>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">{{ $naissance->etat_sante }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ✅ UPDATED: List of Individual Rabbits -->
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-collection"></i> Liste des Lapereaux ({{ $naissance->lapereaux->count() }})</h3>
            </div>
            <div class="card-body">
                @if($naissance->lapereaux->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Sexe</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($naissance->lapereaux as $lapereau)
                            <tr>
                                <td class="fw-semibold">{{ $lapereau->code }}</td>
                                <td>{{ $lapereau->nom ?? '-' }}</td>
                                <td>
                                    @if($lapereau->sex === 'male')
                                        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">Mâle</span>
                                    @else
                                        <span class="badge" style="background: rgba(236, 72, 153, 0.1); color: #EC4899;">Femelle</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lapereau->etat === 'vivant')
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">Vivant</span>
                                    @elseif($lapereau->etat === 'vendu')
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">Vendu</span>
                                    @else
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">Mort</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('lapins.edit', $lapereau->id) }}" class="btn-cuni sm secondary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">Aucun lapereau enregistré pour cette naissance.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-calendar-check"></i> Suivi</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-500">Total Vivants</label>
                        <p class="font-semibold" style="font-size: 1.2rem; color: var(--accent-green);">{{ $naissance->nb_vivant }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Sevrage prévu</label>
                        <p class="font-semibold">{{ $naissance->date_sevrage_prevue ? $naissance->date_sevrage_prevue->format('d/m/Y') : 'Non défini' }}</p>
                    </div>
                    <hr style="border-color: var(--surface-border);">
                    <div>
                        <label class="text-sm text-gray-500">Créé par</label>
                        <p class="font-semibold">{{ $naissance->user->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($naissance->observations)
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-sticky"></i> Observations</h3>
            </div>
            <div class="card-body">
                <p class="text-gray-600">{{ $naissance->observations }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection