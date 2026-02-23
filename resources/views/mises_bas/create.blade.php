@extends('layouts.cuniapp')

@section('title', 'Nouvelle Mise Bas - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-plus-circle"></i>
            Nouvelle Mise Bas
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('mises-bas.index') }}">Mises Bas</a>
            <span>/</span>
            <span>Nouveau</span>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert-cuni error">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>
        <strong>Erreurs de validation</strong>
        <ul style="margin: 8px 0 0 20px; padding: 0;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-egg"></i>
            Informations de la Mise Bas
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('mises-bas.store') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Femelle *</label>
                    <select name="femelle_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($femelles as $femelle)
                        <option value="{{ $femelle->id }}">{{ $femelle->nom }} ({{ $femelle->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de mise bas *</label>
                    <input type="date" name="date_mise_bas" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jeunes vivants *</label>
                    <input type="number" name="nb_vivant" class="form-control" required min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Morts-nés</label>
                    <input type="number" name="nb_mort_ne" class="form-control" value="0" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Date de sevrage</label>
                    <input type="date" name="date_sevrage" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Poids moyen au sevrage (kg)</label>
                    <input type="number" step="0.01" name="poids_moyen_sevrage" class="form-control">
                </div>
            </div>
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer
                </button>
                <a href="{{ route('mises-bas.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection