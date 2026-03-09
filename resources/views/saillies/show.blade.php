{{-- resources/views/saillies/show.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Détails Saillie - CuniApp Élevage')

@push('styles')
<style>
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 24px;
    width: 100%;
    align-items: start;
}
.detail-main {
    min-width: 0;
}
.detail-sidebar {
    min-width: 0;
}
@media (max-width: 1024px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
.stat-box {
    text-align: center;
    padding: 20px 16px;
    background: var(--surface-alt);
    border-radius: var(--radius-lg);
    border: 1px solid var(--surface-border);
}
.stat-box .value {
    font-size: 28px;
    font-weight: 700;
    line-height: 1.2;
}
.stat-box .label {
    font-size: 12px;
    color: var(--text-tertiary);
    margin-top: 4px;
}
</style>
@endpush

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

<div class="detail-grid">
    {{-- Left column: main content --}}
    <div class="detail-main">
        {{-- Informations Principales --}}
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-info-circle"></i> Informations Principales
                </h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Femelle</label>
                        <p>
                            @if ($saillie->femelle)
                                <a href="{{ route('femelles.show', $saillie->femelle->id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $saillie->femelle->nom }} ({{ $saillie->femelle->code }})
                                </a>
                            @else
                                <span style="color: var(--text-tertiary);">Non définie</span>
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mâle</label>
                        <p>
                            @if ($saillie->male)
                                <a href="{{ route('males.show', $saillie->male->id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $saillie->male->nom }} ({{ $saillie->male->code }})
                                </a>
                            @else
                                <span style="color: var(--text-tertiary);">Non défini</span>
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de saillie</label>
                        <p class="fw-semibold" style="font-size: 15px;">
                            {{ \Carbon\Carbon::parse($saillie->date_saillie)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de mise bas théorique</label>
                        <p class="fw-semibold" style="color: var(--accent-purple);">
                            {{ $saillie->date_mise_bas_theorique?->format('d/m/Y') ?? 'Non calculée' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de palpation</label>
                        <p>
                            {{ $saillie->date_palpage ? \Carbon\Carbon::parse($saillie->date_palpage)->format('d/m/Y') : 'Non réalisée' }}
                        </p>
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
                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7D95;">
                                    <i class="bi bi-clock"></i> En attente
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Enregistrée le</label>
                        <p style="color: var(--text-secondary); font-size: 13px;">
                            {{ $saillie->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Palpation Form (if not done) --}}
        @if(!$saillie->palpation_resultat)
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-stethoscope"></i> Enregistrer la Palpation
                </h3>
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

        {{-- Historique / Timeline --}}
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-clock-history"></i> Timeline de Reproduction
                </h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--accent-pink); margin-top: 5px; flex-shrink: 0;"></div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 13px; color: var(--text-primary);">Saillie réalisée</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                {{ $saillie->date_saillie->format('d/m/Y') }}
                            </div>
                            @if($saillie->femelle && $saillie->male)
                                <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                                    {{ $saillie->femelle->nom }} × {{ $saillie->male->nom }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($saillie->date_palpage)
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $saillie->palpation_resultat === '+' ? 'var(--accent-green)' : 'var(--accent-red)' }}; margin-top: 5px; flex-shrink: 0;"></div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 13px; color: var(--text-primary);">
                                Palpation • {{ $saillie->palpation_resultat === '+' ? 'Positif' : 'Négatif' }}
                            </div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                {{ $saillie->date_palpage->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($saillie->date_mise_bas_theorique)
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--accent-purple); margin-top: 5px; flex-shrink: 0;"></div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 13px; color: var(--text-primary);">Mise bas prévue</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                {{ $saillie->date_mise_bas_theorique->format('d/m/Y') }}
                            </div>
                            @php
                                $daysUntil = \Carbon\Carbon::parse($saillie->date_mise_bas_theorique)->diffInDays(now(), false);
                            @endphp
                            @if($daysUntil > 0)
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; font-size: 10px; margin-top: 4px; display: inline-block;">
                                    Dans {{ $daysUntil }} jours
                                </span>
                            @elseif($daysUntil === 0)
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981; font-size: 10px; margin-top: 4px; display: inline-block;">
                                    Aujourd'hui
                                </span>
                            @else
                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7D95; font-size: 10px; margin-top: 4px; display: inline-block;">
                                    Il y a {{ abs($daysUntil) }} jours
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($saillie->misesBas && $saillie->misesBas->count() > 0)
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--accent-green); margin-top: 5px; flex-shrink: 0;"></div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 13px; color: var(--text-primary);">
                                Mises Bas enregistrées ({{ $saillie->misesBas->count() }})
                            </div>
                            @foreach($saillie->misesBas as $miseBas)
                                <a href="{{ route('mises-bas.show', $miseBas) }}" style="display: block; font-size: 12px; color: var(--primary); text-decoration: none; margin-top: 4px; padding: 4px 8px; background: var(--primary-subtle); border-radius: 4px; width: fit-content;">
                                    <i class="bi bi-egg"></i> {{ $miseBas->date_mise_bas->format('d/m/Y') }} - {{ $miseBas->nb_vivant }} vivants
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Right sidebar --}}
    <div class="detail-sidebar">
        {{-- État rapide --}}
        <div class="cuni-card" style="margin-bottom: 20px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-activity"></i> État actuel
                </h3>
            </div>
            <div class="card-body" style="text-align: center; padding: 28px 24px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(236, 72, 153, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="bi bi-heart-fill" style="font-size: 28px; color: var(--accent-pink);"></i>
                </div>
                <span class="badge" style="background: {{ $saillie->palpation_resultat === '+' ? 'rgba(16, 185, 129, 0.1); color: #10B981' : ($saillie->palpation_resultat === '-' ? 'rgba(239, 68, 68, 0.1); color: #EF4444' : 'rgba(107, 114, 128, 0.1); color: #6B7D95') }}; font-size: 14px; padding: 6px 18px;">
                    {{ $saillie->palpation_resultat === '+' ? 'Gestante' : ($saillie->palpation_resultat === '-' ? 'Non Gestante' : 'En attente') }}
                </span>
                @if($saillie->date_saillie)
                    <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 12px;">
                        <i class="bi bi-calendar3"></i> Saillie le {{ $saillie->date_saillie->format('d/m/Y') }}
                    </p>
                @endif
                @if($saillie->date_mise_bas_theorique)
                    <p style="font-size: 12px; color: var(--text-secondary); margin-top: 6px;">
                        <i class="bi bi-egg"></i> Mise bas prévue le {{ $saillie->date_mise_bas_theorique->format('d/m/Y') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="cuni-card" style="margin-bottom: 20px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-lightning"></i> Actions Rapides
                </h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 10px;">
                @if($saillie->palpation_resultat === '+')
                    <a href="{{ route('mises-bas.create') }}?saillie_id={{ $saillie->id }}&femelle_id={{ $saillie->femelle_id }}" class="btn-cuni primary" style="width: 100%;">
                        <i class="bi bi-egg"></i> Enregistrer la mise bas
                    </a>
                @endif
                @if($saillie->femelle)
                    <a href="{{ route('femelles.show', $saillie->femelle->id) }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-arrow-down-right-square"></i> Voir la femelle
                    </a>
                    <a href="{{ route('saillies.create') }}?femelle_id={{ $saillie->femelle_id }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-heart"></i> Nouvelle saillie (même femelle)
                    </a>
                @endif
                @if($saillie->male)
                    <a href="{{ route('males.show', $saillie->male->id) }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-arrow-up-right-square"></i> Voir le mâle
                    </a>
                @endif
                <a href="{{ route('saillies.edit', $saillie) }}" class="btn-cuni secondary" style="width: 100%;">
                    <i class="bi bi-pencil"></i> Modifier les infos
                </a>
                @if(!$saillie->palpation_resultat)
                    <a href="{{ route('saillies.palpation.update', $saillie) }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-stethoscope"></i> Enregistrer palpation
                    </a>
                @endif
            </div>
        </div>

        {{-- Statistiques --}}
        <div class="cuni-card" style="margin-bottom: 20px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-graph-up"></i> Statistiques
                </h3>
            </div>
            <div class="card-body">
                @php
                    $daysSinceSaillie = $saillie->date_saillie->diffInDays(now());
                    $daysUntilMiseBas = $saillie->date_mise_bas_theorique ? $saillie->date_mise_bas_theorique->diffInDays(now(), false) : null;
                    $gestationProgress = $daysUntilMiseBas !== null && $daysUntilMiseBas < 31 ? max(0, min(100, ((31 - $daysUntilMiseBas) / 31) * 100)) : 0;
                @endphp
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-secondary); margin-bottom: 6px;">
                            <span>Jours depuis saillie</span>
                            <span style="font-weight: 600;">{{ $daysSinceSaillie }} jours</span>
                        </div>
                    </div>
                    @if($daysUntilMiseBas !== null)
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-secondary); margin-bottom: 6px;">
                            <span>Jours avant mise bas</span>
                            <span style="font-weight: 600;">{{ $daysUntilMiseBas > 0 ? $daysUntilMiseBas : 0 }} jours</span>
                        </div>
                        <div style="height: 6px; background: var(--surface-border); border-radius: 3px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $gestationProgress }}%; background: var(--accent-purple); border-radius: 3px; transition: width 1s ease;"></div>
                        </div>
                        <div style="font-size: 10px; color: var(--text-tertiary); margin-top: 4px; text-align: right;">
                            {{ round($gestationProgress) }}% de gestation
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Métadonnées --}}
        <div class="cuni-card" style="margin-bottom: 20px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-clock-history"></i> Métadonnées
                </h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--surface-border);">
                        <span style="color: var(--text-secondary);">Créée le</span>
                        <span style="font-weight: 600;">{{ $saillie->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--surface-border);">
                        <span style="color: var(--text-secondary);">Modifiée le</span>
                        <span style="font-weight: 600;">{{ $saillie->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                        <span style="color: var(--text-secondary);">Heure</span>
                        <span style="font-weight: 600;">{{ $saillie->updated_at->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Zone danger --}}
        <div class="cuni-card" style="border-left: 4px solid var(--accent-red);">
            <div class="card-body">
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                    <i class="bi bi-exclamation-triangle" style="color: var(--accent-red);"></i>
                    <strong>Attention:</strong> La suppression est irréversible et peut affecter les mises bas associées.
                </p>
                <form action="{{ route('saillies.destroy', $saillie) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette saillie ? Cette action ne peut pas être annulée.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-cuni danger" style="width: 100%;">
                        <i class="bi bi-trash"></i> Supprimer cette saillie
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection