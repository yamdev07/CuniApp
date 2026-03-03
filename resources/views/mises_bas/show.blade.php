@extends('layouts.cuniapp')
@section('title', 'Détails Mise Bas - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-egg"></i> Détails de la Mise Bas
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('mises-bas.index') }}">Mises Bas</a>
            <span>/</span>
            <span>#{{ $miseBas->id }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('mises-bas.edit', $miseBas) }}" class="btn-cuni primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('mises-bas.index') }}" class="btn-cuni secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title"><i class="bi bi-info-circle"></i> Informations</h3>
    </div>
    <div class="card-body">
        <div class="settings-grid">
            <div class="form-group">
                <label class="form-label">Femelle</label>
                <p class="fw-semibold">{{ $miseBas->femelle->nom ?? 'N/A' }} 
                    <small class="text-muted">({{ $miseBas->femelle->code ?? '-' }})</small>
                </p>
            </div>
            <div class="form-group">
                <label class="form-label">Date de mise bas</label>
                <p>{{ $miseBas->date_mise_bas->format('d/m/Y') }}</p>
            </div>
            <div class="form-group">
                <label class="form-label">Jeunes vivants</label>
                <p><span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">{{ $miseBas->nb_vivant }}</span></p>
            </div>
            <div class="form-group">
                <label class="form-label">Morts-nés</label>
                <p>{{ $miseBas->nb_mort_ne ?? 0 }}</p>
            </div>
            <div class="form-group">
                <label class="form-label">Total de la portée</label>
                <p class="fw-bold">{{ ($miseBas->nb_vivant ?? 0) + ($miseBas->nb_mort_ne ?? 0) }}</p>
            </div>
            <div class="form-group">
                <label class="form-label">Date de sevrage</label>
                <p>{{ $miseBas->date_sevrage ? $miseBas->date_sevrage->format('d/m/Y') : '-' }}</p>
            </div>
            <div class="form-group">
                <label class="form-label">Poids moyen au sevrage</label>
                <p>{{ $miseBas->poids_moyen_sevrage ? $miseBas->poids_moyen_sevrage . ' kg' : '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection