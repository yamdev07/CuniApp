@extends('layouts.cuniapp')
@section('title', 'Détails Naissance - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-egg-fill"></i> Détails de la Naissance
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('naissances.index') }}">Naissances</a>
            <span>/</span>
            <span>#{{ $naissance->id }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('naissances.edit', $naissance) }}" class="btn-cuni primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('naissances.index') }}" class="btn-cuni secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
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
                        <div class="flex items-center gap-2">
                            <span class="font-semibold">{{ $naissance->femelle->nom ?? 'N/A' }}</span>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ $naissance->femelle->code ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date & Heure</label>
                        <p>{{ $naissance->date_naissance->format('d/m/Y') }}
                            @if($naissance->heure_naissance)
                                à {{ $naissance->heure_naissance->format('H:i') }}
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lieu</label>
                        <p>{{ $naissance->lieu_naissance ?? 'Non spécifié' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">État de santé</label>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                            {{ $naissance->etat_sante }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-collection"></i> Détails de la Portée
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4" style="background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div class="text-3xl font-bold" style="color: var(--accent-green);">{{ $naissance->nb_vivant }}</div>
                        <div class="text-sm text-gray-500 mt-1">Vivants</div>
                    </div>
                    <div class="text-center p-4" style="background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div class="text-3xl font-bold" style="color: var(--accent-red);">{{ $naissance->nb_mort_ne }}</div>
                        <div class="text-sm text-gray-500 mt-1">Mort-nés</div>
                    </div>
                    <div class="text-center p-4" style="background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div class="text-3xl font-bold" style="color: var(--primary);">{{ $naissance->nb_total }}</div>
                        <div class="text-sm text-gray-500 mt-1">Total</div>
                    </div>
                    <div class="text-center p-4" style="background: var(--surface-alt); border-radius: var(--radius-lg);">
                        <div class="text-3xl font-bold" style="color: var(--accent-purple);">{{ $naissance->taux_survie }}%</div>
                        <div class="text-sm text-gray-500 mt-1">Taux de survie</div>
                    </div>
                </div>
                
                @if($naissance->poids_moyen_naissance)
                <div class="mt-4 p-4" style="background: var(--surface-alt); border-radius: var(--radius-lg);">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Poids moyen à la naissance</span>
                        <span class="font-bold">{{ $naissance->poids_moyen_naissance }} g</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-calendar-check"></i> Suivi
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-500">Sevrage prévu</label>
                        <p class="font-semibold">
                            {{ $naissance->date_sevrage_prevue ? $naissance->date_sevrage_prevue->format('d/m/Y') : 'Non défini' }}
                        </p>
                        @if($naissance->jours_avant_sevrage > 0)
                        <span class="text-xs" style="color: var(--accent-orange);">
                            Dans {{ $naissance->jours_avant_sevrage }} jours
                        </span>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Vaccination prévue</label>
                        <p class="font-semibold">
                            {{ $naissance->date_vaccination_prevue ? $naissance->date_vaccination_prevue->format('d/m/Y') : 'Non défini' }}
                        </p>
                    </div>
                    <hr style="border-color: var(--surface-border);">
                    <div>
                        <label class="text-sm text-gray-500">Créé par</label>
                        <p class="font-semibold">{{ $naissance->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Date d'enregistrement</label>
                        <p class="font-semibold">{{ $naissance->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($naissance->observations)
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-sticky"></i> Observations
                </h3>
            </div>
            <div class="card-body">
                <p class="text-gray-600">{{ $naissance->observations }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection