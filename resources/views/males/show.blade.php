{{-- resources/views/males/show.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Détails Mâle - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-arrow-up-right-square"></i> Détails du Mâle
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('males.index') }}">Mâles</a>
            <span>/</span>
            <span>{{ $male->nom }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('males.edit', $male) }}" class="btn-cuni primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('males.index') }}" class="btn-cuni secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info - 2/3 width -->
    <div class="lg:col-span-2">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-info-circle"></i> Informations Principales</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Code</label>
                        <p class="fw-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $male->code }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <p class="fw-semibold">{{ $male->nom }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Race</label>
                        <p>{{ $male->race ?? 'Non spécifiée' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Origine</label>
                        <p>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ $male->origine }}
                            </span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de naissance</label>
                        <p>{{ $male->date_naissance ? $male->date_naissance->format('d/m/Y') : 'Non renseignée' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Âge</label>
                        <p>
                            @if($male->date_naissance)
                                {{ $male->date_naissance->diffInYears(now()) }} ans, {{ $male->date_naissance->diffInMonths(now()) % 12 }} mois
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">État</label>
                        <p>
                            <span class="badge status-{{ strtolower($male->etat) }}">
                                {{ $male->etat }}
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
                    $sailliesCount = \App\Models\Saillie::where('male_id', $male->id)->count();
                @endphp
                <div class="grid grid-cols-2 gap-4">
                    <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div style="font-size: 24px; font-weight: 700; color: var(--primary);">{{ $sailliesCount }}</div>
                        <div style="font-size: 12px; color: var(--text-tertiary);">Saillies</div>
                    </div>
                    <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div style="font-size: 24px; font-weight: 700; color: var(--accent-green);">
                            {{ \App\Models\Naissance::whereHas('miseBas.saillie', fn($q) => $q->where('male_id', $male->id))->count() }}
                        </div>
                        <div style="font-size: 12px; color: var(--text-tertiary);">Portées</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Actions & Metadata - 1/3 width -->
    <div>
        <!-- Quick Actions -->
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-lightning"></i> Actions Rapides</h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 12px;">
                <form action="{{ route('males.toggleEtat', $male) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-arrow-repeat"></i> Changer l'état
                    </button>
                </form>
                <a href="{{ route('saillies.create') }}?male_id={{ $male->id }}" class="btn-cuni primary" style="width: 100%;">
                    <i class="bi bi-heart"></i> Planifier une saillie
                </a>
            </div>
        </div>

        <!-- Metadata -->
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-info-circle"></i> Métadonnées</h3>
            </div>
            <div class="card-body">
                <div class="space-y-2" style="font-size: 13px;">
                    <div>
                        <span class="text-gray-500">Créé le:</span>
                        <span class="fw-semibold">{{ $male->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dernière mise à jour:</span>
                        <span class="fw-semibold">{{ $male->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Warning Card -->
        <div class="cuni-card" style="margin-top: 24px; border-left: 4px solid var(--accent-red);">
            <div class="card-body">
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                    <i class="bi bi-exclamation-triangle" style="color: var(--accent-red);"></i>
                    <strong>Attention:</strong> La suppression est irréversible.
                </p>
                <form action="{{ route('males.destroy', $male) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce mâle ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-cuni danger" style="width: 100%;">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection