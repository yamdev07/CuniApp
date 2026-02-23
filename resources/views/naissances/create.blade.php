@extends('layouts.cuniapp')

@section('title', 'Nouvelle Naissance - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-plus-circle"></i>
            Nouvelle Naissance
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('naissances.index') }}">Naissances</a>
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
            <i class="bi bi-star"></i>
            Informations de la Naissance
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('naissances.store') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Nom du lapin *</label>
                    <input type="text" name="nom_lapin" class="form-control" required placeholder="Nom du lapereau">
                </div>
                <div class="form-group">
                    <label class="form-label">Sexe *</label>
                    <select name="sexe" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="M">Mâle</option>
                        <option value="F">Femelle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de naissance *</label>
                    <input type="date" name="date_naissance" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Poids (kg) *</label>
                    <input type="number" step="0.01" name="poids" class="form-control" required placeholder="0.00">
                </div>
            </div>
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer
                </button>
                <a href="{{ route('naissances.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection