@extends('layouts.cuniapp')

@section('title', 'Modifier Mise Bas - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-pencil-square"></i>
            Modifier la Mise Bas
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('mises-bas.index') }}">Mises Bas</a>
            <span>/</span>
            <span>Modification</span>
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
        <a href="{{ route('mises-bas.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('mises-bas.update', $miseBas->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Femelle *</label>
                    <select name="femelle_id" class="form-select" required>
                        @foreach($femelles as $femelle)
                        <option value="{{ $femelle->id }}" {{ $miseBas->femelle_id == $femelle->id ? 'selected' : '' }}>
                            {{ $femelle->nom }} ({{ $femelle->code }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de mise bas *</label>
                    <input type="date" name="date_mise_bas" class="form-control" value="{{ old('date_mise_bas', $miseBas->date_mise_bas) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jeunes vivants *</label>
                    <input type="number" name="nb_vivant" class="form-control" value="{{ old('nb_vivant', $miseBas->nb_vivant) }}" required min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Morts-nés</label>
                    <input type="number" name="nb_mort_ne" class="form-control" value="{{ old('nb_mort_ne', $miseBas->nb_mort_ne) }}" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Date de sevrage</label>
                    <input type="date" name="date_sevrage" class="form-control" value="{{ old('date_sevrage', $miseBas->date_sevrage) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Poids moyen au sevrage (kg)</label>
                    <input type="number" step="0.01" name="poids_moyen_sevrage" class="form-control" value="{{ old('poids_moyen_sevrage', $miseBas->poids_moyen_sevrage) }}">
                </div>
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection