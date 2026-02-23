@extends('layouts.cuniapp')

@section('title', 'Modifier Mâle - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-pencil-square"></i>
            Modifier le Mâle
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('males.index') }}">Mâles</a>
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
            <i class="bi bi-info-circle"></i>
            Informations du Mâle
        </h3>
        <a href="{{ route('males.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('males.update', $male->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Code *</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $male->code) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="nom" class="form-control" value="{{ old('nom', $male->nom) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Race</label>
                    <input type="text" name="race" class="form-control" value="{{ old('race', $male->race) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Origine *</label>
                    <select name="origine" class="form-select" required>
                        <option value="Interne" {{ old('origine', $male->origine) == 'Interne' ? 'selected' : '' }}>Interne</option>
                        <option value="Achat" {{ old('origine', $male->origine) == 'Achat' ? 'selected' : '' }}>Achat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $male->date_naissance) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">État *</label>
                    <select name="etat" class="form-select" required>
                        <option value="Active" {{ old('etat', $male->etat) == 'Active' ? 'selected' : '' }}>Actif</option>
                        <option value="Inactive" {{ old('etat', $male->etat) == 'Inactive' ? 'selected' : '' }}>Repos</option>
                        <option value="Malade" {{ old('etat', $male->etat) == 'Malade' ? 'selected' : '' }}>Malade</option>
                    </select>
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