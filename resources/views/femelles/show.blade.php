{{-- resources/views/femelles/show.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Détails Femelle - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-arrow-down-right-square"></i> Détails de la Femelle #{{ $femelle->code }}
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('femelles.index') }}">Femelles</a>
            <span>/</span>
            <span>#{{ $femelle->code }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('femelles.edit', $femelle) }}" class="btn-cuni primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('femelles.index') }}" class="btn-cuni secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
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
                        <label class="form-label">Code</label>
                        <p class="fw-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $femelle->code }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <p class="fw-semibold">{{ $femelle->nom }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Race</label>
                        <p>{{ $femelle->race ?? 'Non spécifiée' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Origine</label>
                        <p>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ $femelle->origine }}
                            </span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de naissance</label>
                        <p>{{ $femelle->date_naissance ? $femelle->date_naissance->format('d/m/Y') : 'Non définie' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">État</label>
                        <p>
                            @php
                                $etatColors = [
                                    'Active' => 'rgba(16, 185, 129, 0.1); color: #10B981;',
                                    'Gestante' => 'rgba(236, 72, 153, 0.1); color: #EC4899;',
                                    'Allaitante' => 'rgba(139, 92, 246, 0.1); color: #8B5CF6;',
                                    'Vide' => 'rgba(59, 130, 246, 0.1); color: #3B82F6;',
                                ];
                            @endphp
                            <span class="badge" style="background: {{ $etatColors[$femelle->etat] ?? $etatColors['Active'] }}">
                                {{ $femelle->etat }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reproduction History -->
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-heart"></i> Historique de Reproduction</h3>
            </div>
            <div class="card-body">
                @php
                    $sailliesCount = $femelle->saillies()->count();
                    $naissancesCount = $femelle->naissances()->count();
                @endphp
                <div class="grid grid-cols-2 gap-4">
                    <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div style="font-size: 24px; font-weight: 700; color: var(--primary);">{{ $sailliesCount }}</div>
                        <div style="font-size: 12px; color: var(--text-tertiary);">Saillies</div>
                    </div>
                    <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div style="font-size: 24px; font-weight: 700; color: var(--accent-green);">{{ $naissancesCount }}</div>
                        <div style="font-size: 12px; color: var(--text-tertiary);">Portées</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Metadata & Actions -->
    <div>
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-info-circle"></i> Métadonnées</h3>
            </div>
            <div class="card-body">
                <div class="space-y-2" style="font-size: 13px;">
                    <div>
                        <span class="text-gray-500">Enregistrée le:</span>
                        <span class="fw-semibold">{{ $femelle->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dernière mise à jour:</span>
                        <span class="fw-semibold">{{ $femelle->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-lightning"></i> Actions</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <form action="{{ route('femelles.toggleEtat', $femelle) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-cuni secondary" style="width: 100%;">
                            <i class="bi bi-arrow-repeat"></i> Changer l'état
                        </button>
                    </form>
                    <a href="{{ route('saillies.create') }}?femelle_id={{ $femelle->id }}" class="btn-cuni primary" style="width: 100%;">
                        <i class="bi bi-plus-lg"></i> Nouvelle saillie
                    </a>
                    <a href="{{ route('naissances.index') }}?femelle_id={{ $femelle->id }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-egg-fill"></i> Voir les naissances
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection