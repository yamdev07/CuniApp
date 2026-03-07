{{-- resources/views/femelles/show.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Détails Femelle - CuniApp Élevage')

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
    </style>
@endpush

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

    <div class="detail-grid">
        {{-- Left column --}}
        <div class="detail-main">
            {{-- Informations Principales --}}
            <div class="cuni-card">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-info-circle"></i> Informations Principales</h3>
                </div>
                <div class="card-body">
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Code</label>
                            <p class="fw-semibold" style="font-family: 'JetBrains Mono', monospace; font-size: 15px;">
                                {{ $femelle->code }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nom</label>
                            <p class="fw-semibold" style="font-size: 15px;">{{ $femelle->nom }}</p>
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
                            <p>{{ $femelle->date_naissance ? $femelle->date_naissance->format('d/m/Y') : 'Non définie' }}
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Âge</label>
                            <p>
                                @if ($femelle->date_naissance)
                                    {{ $femelle->date_naissance->diffInYears(now()) }} ans,
                                    {{ $femelle->date_naissance->diffInMonths(now()) % 12 }} mois
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
                                        'Active' => 'rgba(16,185,129,0.1); color:#10B981',
                                        'Gestante' => 'rgba(236,72,153,0.1); color:#EC4899',
                                        'Allaitante' => 'rgba(139,92,246,0.1); color:#8B5CF6',
                                        'Vide' => 'rgba(59,130,246,0.1); color:#3B82F6',
                                    ];
                                @endphp
                                <span class="badge"
                                    style="background: {{ $etatColors[$femelle->etat] ?? $etatColors['Active'] }}">
                                    {{ $femelle->etat }}
                                </span>
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Enregistrée le</label>
                            <p style="color: var(--text-secondary); font-size: 13px;">
                                {{ $femelle->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historique de Reproduction --}}
            <div class="cuni-card" style="margin-top: 24px;">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-heart"></i> Historique de Reproduction</h3>
                </div>
                <div class="card-body">
                    @php
                        $sailliesCount = $femelle->saillies()->count();
                        $naissancesCount = $femelle->naissances()->count();
                        // FIX: Load the collection first so the accessor works, or sum via relationship
                        $totalLapereaux = $femelle->naissances->sum('nb_vivant');
                    @endphp
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px;">
                        <div
                            style="text-align: center; padding: 20px 16px; background: var(--surface-alt); border-radius: var(--radius-lg); border: 1px solid var(--surface-border);">
                            <div style="font-size: 28px; font-weight: 700; color: var(--accent-purple);">
                                {{ $sailliesCount }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Saillies</div>
                        </div>
                        <div
                            style="text-align: center; padding: 20px 16px; background: var(--surface-alt); border-radius: var(--radius-lg); border: 1px solid var(--surface-border);">
                            <div style="font-size: 28px; font-weight: 700; color: var(--accent-green);">
                                {{ $naissancesCount }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Portées</div>
                        </div>
                        <div
                            style="text-align: center; padding: 20px 16px; background: var(--surface-alt); border-radius: var(--radius-lg); border: 1px solid var(--surface-border);">
                            <div style="font-size: 28px; font-weight: 700; color: var(--accent-orange);">
                                {{ $totalLapereaux }}</div>
                            <div style="font-size: 12px; color: var(--text-tertiary); margin-top: 4px;">Lapereaux nés</div>
                        </div>
                    </div>

                    {{-- Recent saillies --}}
                    @php
                        $recentSaillies = $femelle
                            ->saillies()
                            ->with('male')
                            ->orderByDesc('date_saillie')
                            ->limit(5)
                            ->get();
                    @endphp
                    @if ($recentSaillies->count() > 0)
                        <p style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 12px;">
                            Saillies récentes</p>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @foreach ($recentSaillies as $saillie)
                                <a href="{{ route('saillies.show', $saillie->id) }}"
                                    style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: var(--surface-alt); border-radius: var(--radius); border: 1px solid var(--surface-border); text-decoration: none; color: inherit; transition: all 0.2s;"
                                    onmouseover="this.style.borderColor='var(--primary)'"
                                    onmouseout="this.style.borderColor='var(--surface-border)'">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="bi bi-heart-fill"
                                            style="color: var(--accent-purple); font-size: 13px;"></i>
                                        <span style="font-size: 13px; font-weight: 500;">avec
                                            {{ $saillie->male->nom ?? '-' }}</span>
                                    </div>
                                    <span
                                        style="font-size: 12px; color: var(--text-tertiary);">{{ \Carbon\Carbon::parse($saillie->date_saillie)->format('d/m/Y') }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>{{-- /detail-main --}}

        {{-- Right sidebar --}}
        <div class="detail-sidebar">
            {{-- État rapide --}}
            <div class="cuni-card" style="margin-bottom: 20px;">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-activity"></i> État actuel</h3>
                </div>
                <div class="card-body" style="text-align: center; padding: 28px 24px;">
                    <div
                        style="width: 64px; height: 64px; border-radius: 50%; background: rgba(236,72,153,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="bi bi-arrow-down-right-square" style="font-size: 28px; color: var(--accent-pink);"></i>
                    </div>
                    <span class="badge"
                        style="background: {{ $etatColors[$femelle->etat] ?? $etatColors['Active'] }}; font-size: 14px; padding: 6px 18px;">
                        {{ $femelle->etat }}
                    </span>
                    @if ($femelle->date_naissance)
                        <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 12px;">
                            <i class="bi bi-calendar3"></i> Née le {{ $femelle->date_naissance->format('d/m/Y') }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Actions rapides --}}
            <div class="cuni-card" style="margin-bottom: 20px;">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-lightning"></i> Actions Rapides</h3>
                </div>
                <div class="card-body" style="display: flex; flex-direction: column; gap: 10px;">
                    <form action="{{ route('femelles.toggleEtat', $femelle) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-cuni secondary" style="width: 100%;">
                            <i class="bi bi-arrow-repeat"></i> Changer l'état
                        </button>
                    </form>
                    <a href="{{ route('saillies.create') }}?femelle_id={{ $femelle->id }}" class="btn-cuni primary"
                        style="width: 100%;">
                        <i class="bi bi-plus-lg"></i> Nouvelle saillie
                    </a>
                    <a href="{{ route('naissances.index') }}?femelle_id={{ $femelle->id }}" class="btn-cuni secondary"
                        style="width: 100%;">
                        <i class="bi bi-egg-fill"></i> Voir les naissances
                    </a>
                    <a href="{{ route('mises-bas.index') }}?femelle_id={{ $femelle->id }}" class="btn-cuni secondary"
                        style="width: 100%;">
                        <i class="bi bi-egg"></i> Voir les mises bas
                    </a>
                    <a href="{{ route('femelles.edit', $femelle) }}" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-pencil"></i> Modifier les infos
                    </a>
                </div>
            </div>

            {{-- Métadonnées --}}
            <div class="cuni-card" style="margin-bottom: 20px;">
                <div class="card-header-custom">
                    <h3 class="card-title"><i class="bi bi-clock-history"></i> Métadonnées</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--surface-border);">
                            <span style="color: var(--text-secondary);">Créée le</span>
                            <span style="font-weight: 600;">{{ $femelle->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid var(--surface-border);">
                            <span style="color: var(--text-secondary);">Modifiée le</span>
                            <span style="font-weight: 600;">{{ $femelle->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0;">
                            <span style="color: var(--text-secondary);">Heure</span>
                            <span style="font-weight: 600;">{{ $femelle->updated_at->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Zone danger --}}
            <div class="cuni-card" style="border-left: 4px solid var(--accent-red);">
                <div class="card-body">
                    <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">
                        <i class="bi bi-exclamation-triangle" style="color: var(--accent-red);"></i>
                        <strong>Attention:</strong> La suppression est irréversible et supprime toutes les données
                        associées.
                    </p>
                    <form action="{{ route('femelles.destroy', $femelle) }}" method="POST"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette femelle ? Cette action ne peut pas être annulée.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-cuni danger" style="width: 100%;">
                            <i class="bi bi-trash"></i> Supprimer cette femelle
                        </button>
                    </form>
                </div>
            </div>
        </div>{{-- /detail-sidebar --}}
    </div>
@endsection
