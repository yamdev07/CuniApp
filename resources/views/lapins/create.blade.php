@extends('layouts.cuniapp')
@section('title', 'Nouveau Lapin - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-plus-circle"></i> Nouveau Lapin
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('lapins.index') }}">Lapins</a>
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
            <i class="bi bi-info-circle"></i> Informations du Lapin
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('lapins.store') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select name="type" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="male">Mâle</option>
                        <option value="femelle">Femelle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="nom" class="form-control" required placeholder="Nom du lapin">
                </div>
                <div class="form-group">
                    <label class="form-label">Race *</label>
                    <input type="text" name="race" class="form-control" required placeholder="Ex: Californien">
                </div>
                <div class="form-group">
                    <label class="form-label">Origine *</label>
                    <select name="origine" class="form-select" required>
                        <option value="Interne">Interne</option>
                        <option value="Achat">Achat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de naissance *</label>
                    <input type="date" name="date_naissance" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">État *</label>
                    <select name="etat" class="form-select" required>
                        <option value="active">Actif</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
            </div>
            
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
                <a href="{{ route('lapins.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection