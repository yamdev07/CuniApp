{{-- resources/views/lapins/show.blade.php --}}
@extends('layouts.cuniapp')

@section('title', $lapin->nom . ' - Détails')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-{{ $lapin->type === 'male' ? 'arrow-up-right-square' : 'arrow-down-right-square' }}"></i>
            Détails du {{ $lapin->type === 'male' ? 'Mâle' : 'Femelle' }}
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('lapins.index') }}">Lapins</a>
            <span>/</span>
            <span>{{ $lapin->nom }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('lapins.edit', $lapin->id) }}" class="btn-cuni primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('lapins.index') }}" class="btn-cuni secondary">
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
                        <p class="fw-semibold" style="font-family: 'JetBrains Mono', monospace;">{{ $lapin->code }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <p class="fw-semibold">{{ $lapin->nom }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <p>
                            <span class="badge {{ $lapin->type === 'male' ? 'bg-primary' : 'bg-pink' }}">
                                {{ $lapin->type === 'male' ? '🐰 Mâle' : '🐰 Femelle' }}
                            </span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Race</label>
                        <p>{{ $lapin->race ?? 'Non spécifiée' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Origine</label>
                        <p>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ $lapin->origine ?? 'Non renseignée' }}
                            </span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de naissance</label>
                        <p>
                            {{ $lapin->date_naissance ? \Carbon\Carbon::parse($lapin->date_naissance)->format('d/m/Y') : 'Non renseignée' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Âge</label>
                        <p>
                            @if($lapin->date_naissance)
                                {{ \Carbon\Carbon::parse($lapin->date_naissance)->diffInYears(now()) }} ans, 
                                {{ \Carbon\Carbon::parse($lapin->date_naissance)->diffInMonths(now()) % 12 }} mois
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">État</label>
                        <p>
                            @php
                                $etatColors = [
                                    'Active' => 'rgba(16, 185, 129, 0.1); color: #10B981;',
                                    'Inactive' => 'rgba(100, 116, 139, 0.1); color: #64748B;',
                                ];
                            @endphp
                            <span class="badge" style="background: {{ $etatColors[$lapin->etat] ?? $etatColors['Active'] }}">
                                {{ $lapin->etat }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reproduction Stats (conditional based on type) -->
        @if(isset($model) && $model)
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-{{ $lapin->type === 'male' ? 'heart' : 'egg-fill' }}"></i>
                    {{ $lapin->type === 'male' ? 'Statistiques de Reproduction' : 'Historique de Reproduction' }}
                </h3>
            </div>
            <div class="card-body">
                @if($lapin->type === 'femelle')
                    @php
                        $sailliesCount = $model->saillies ? $model->saillies->count() : 0;
                        $misesBasCount = $model->misesBas ? $model->misesBas->count() : 0;
                        $lapereauxCount = \App\Models\Lapereau::whereHas('naissance.miseBas', fn($q) => $q->where('femelle_id', $model->id))->count();
                    @endphp
                    <div class="grid grid-cols-3 gap-4">
                        <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--accent-purple);">{{ $sailliesCount }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">Saillies</div>
                        </div>
                        <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--accent-green);">{{ $misesBasCount }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">Portées</div>
                        </div>
                        <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--primary);">{{ $lapereauxCount }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">Lapereaux</div>
                        </div>
                    </div>
                @else
                    @php
                        $sailliesCount = $model->saillies ? $model->saillies->count() : 0;
                        $porteesIssues = \App\Models\MiseBas::whereHas('saillie', fn($q) => $q->where('male_id', $model->id))->count();
                    @endphp
                    <div class="grid grid-cols-2 gap-4">
                        <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--primary);">{{ $sailliesCount }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">Saillies</div>
                        </div>
                        <div style="text-align: center; padding: 16px; background: var(--surface-alt); border-radius: var(--radius-lg);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--accent-green);">{{ $porteesIssues }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">Portées issues</div>
                        </div>
                    </div>
                    <p class="text-muted small mt-3" style="font-size: 12px; color: var(--text-tertiary);">
                        <i class="bi bi-info-circle"></i> 
                        Les statistiques sont basées sur les saillies enregistrées avec ce mâle.
                    </p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar: Actions & Metadata -->
    <div>
        <!-- Quick Actions -->
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-lightning"></i> Actions Rapides</h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 12px;">
                @if($lapin->type === 'male')
                    <a href="{{ route('saillies.create') }}?male_id={{ $lapin->id }}" class="btn-cuni primary" style="width: 100%;">
                        <i class="bi bi-heart"></i> Planifier une saillie
                    </a>
                @else
                    <a href="{{ route('saillies.create') }}?femelle_id={{ $lapin->id }}" class="btn-cuni primary" style="width: 100%;">
                        <i class="bi bi-heart"></i> Planifier une saillie
                    </a>
                    <a href="{{ route('mises-bas.create') }}?femelle_id={{ $lapin->id }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-egg"></i> Enregistrer mise bas
                    </a>
                @endif
                <form action="{{ route($lapin->type === 'male' ? 'males.toggleEtat' : 'femelles.toggleEtat', $lapin->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-arrow-repeat"></i> Changer l'état
                    </button>
                </form>
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
                        <span class="fw-semibold">{{ $lapin->created_at?->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dernière mise à jour:</span>
                        <span class="fw-semibold">{{ $lapin->updated_at?->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">ID:</span>
                        <span class="fw-semibold">{{ $lapin->id }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Warning -->
        <div class="cuni-card" style="margin-top: 24px; border-left: 4px solid var(--accent-red);">
            <div class="card-body">
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                    <i class="bi bi-exclamation-triangle" style="color: var(--accent-red);"></i>
                    <strong>Attention:</strong> La suppression est irréversible et affectera les données liées.
                </p>
                <form action="{{ route('lapins.destroy', $lapin->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce lapin ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-cuni danger" style="width: 100%;">
                        <i class="bi bi-trash"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection