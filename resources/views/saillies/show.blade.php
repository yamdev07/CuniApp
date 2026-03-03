{{-- resources/views/saillies/show.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Détails Saillie - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-heart"></i> Détails de la Saillie #{{ $saillie->id }}
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('saillies.index') }}">Saillies</a>
            <span>/</span>
            <span>#{{ $saillie->id }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('saillies.edit', $saillie) }}" class="btn-cuni primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('saillies.index') }}" class="btn-cuni secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-info-circle"></i> Informations de la Saillie</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Femelle</label>
                        <p class="fw-semibold">
                            {{ $saillie->femelle->nom ?? 'N/A' }}
                            <small class="text-muted">({{ $saillie->femelle->code ?? '-' }})</small>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mâle</label>
                        <p class="fw-semibold">
                            {{ $saillie->male->nom ?? 'N/A' }}
                            <small class="text-muted">({{ $saillie->male->code ?? '-' }})</small>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de saillie</label>
                        <p>{{ $saillie->date_saillie->format('d/m/Y') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de mise bas théorique</label>
                        <p class="fw-semibold" style="color: var(--accent-purple);">
                            {{ $saillie->date_mise_bas_theorique?->format('d/m/Y') ?? 'Non calculée' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de palpation</label>
                        <p>{{ $saillie->date_palpage ? $saillie->date_palpage->format('d/m/Y') : 'Non réalisée' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Résultat de palpation</label>
                        <p>
                            @if($saillie->palpation_resultat === '+')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                    <i class="bi bi-check-circle"></i> Positif (Gestante)
                                </span>
                            @elseif($saillie->palpation_resultat === '-')
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                    <i class="bi bi-x-circle"></i> Négatif
                                </span>
                            @else
                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7280;">
                                    <i class="bi bi-clock"></i> En attente
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Palpation Form (if not done) -->
        @if(!$saillie->palpation_resultat)
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-stethoscope"></i> Enregistrer la Palpation</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('saillies.palpation.update', $saillie) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Date de palpation *</label>
                            <input type="date" name="date_palpage" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Résultat *</label>
                            <select name="palpation_resultat" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <option value="+">Positif (+) - Gestante</option>
                                <option value="-">Négatif (-)</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-cuni primary" style="margin-top: 16px;">
                        <i class="bi bi-check-circle"></i> Enregistrer le résultat
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar: Timeline & Actions -->
    <div>
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-clock-history"></i> Timeline</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--accent-pink); margin-top: 5px;"></div>
                        <div>
                            <div style="font-weight: 600; font-size: 13px;">Saillie réalisée</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">{{ $saillie->date_saillie->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    @if($saillie->date_palpage)
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $saillie->palpation_resultat === '+' ? 'var(--accent-green)' : 'var(--accent-red)' }}; margin-top: 5px;"></div>
                        <div>
                            <div style="font-weight: 600; font-size: 13px;">Palpation</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                {{ $saillie->date_palpage->format('d/m/Y') }} • 
                                {{ $saillie->palpation_resultat === '+' ? 'Positif' : 'Négatif' }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($saillie->date_mise_bas_theorique)
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--accent-purple); margin-top: 5px;"></div>
                        <div>
                            <div style="font-weight: 600; font-size: 13px;">Mise bas prévue</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">{{ $saillie->date_mise_bas_theorique->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-lightning"></i> Actions</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @if($saillie->palpation_resultat === '+')
                    <a href="{{ route('mises-bas.create') }}?saillie_id={{ $saillie->id }}&femelle_id={{ $saillie->femelle_id }}" class="btn-cuni primary" style="width: 100%;">
                        <i class="bi bi-egg"></i> Enregistrer la mise bas
                    </a>
                    @endif
                    <a href="{{ route('femelles.show', $saillie->femelle) }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-arrow-down-right-square"></i> Voir la femelle
                    </a>
                    <a href="{{ route('males.show', $saillie->male) }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-arrow-up-right-square"></i> Voir le mâle
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection