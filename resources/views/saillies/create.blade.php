@extends('layouts.cuniapp')

@section('title', 'Nouvelle Saillie - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-plus-circle"></i>
            Nouvelle Saillie
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('saillies.index') }}">Saillies</a>
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
            <i class="bi bi-heart"></i>
            Informations de la Saillie
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('saillies.store') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Femelle *</label>
                    <select name="femelle_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($femelles as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }} ({{ $f->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mâle *</label>
                    <select name="male_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($males as $m)
                        <option value="{{ $m->id }}">{{ $m->nom }} ({{ $m->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de saillie *</label>
                    <input type="date" name="date_saillie" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de palpation</label>
                    <input type="date" name="date_palpage" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Résultat de palpation</label>
                    <select name="palpation_resultat" class="form-select">
                        <option value="">-- Non défini --</option>
                        <option value="+">Positif (+)</option>
                        <option value="-">Négatif (-)</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer
                </button>
                <a href="{{ route('saillies.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection