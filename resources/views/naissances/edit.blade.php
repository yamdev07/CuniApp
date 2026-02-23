@extends('layouts.cuniapp')

@section('title', 'Modifier Naissance - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-pencil-square"></i>
            Modifier la Naissance
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('naissances.index') }}">Naissances</a>
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
            <i class="bi bi-star"></i>
            Informations de la Naissance
        </h3>
        <a href="{{ route('naissances.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('naissances.update', $naissance->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Nom du lapin *</label>
                    <input type="text" name="nom_lapin" class="form-control" value="{{ old('nom_lapin', $naissance->nom_lapin) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Sexe *</label>
                    <select name="sexe" class="form-select" required>
                        <option value="M" {{ $naissance->sexe == 'M' ? 'selected' : '' }}>Mâle</option>
                        <option value="F" {{ $naissance->sexe == 'F' ? 'selected' : '' }}>Femelle</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de naissance *</label>
                    <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $naissance->date_naissance) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Poids (kg) *</label>
                    <input type="number" step="0.01" name="poids" class="form-control" value="{{ old('poids', $naissance->poids) }}" required>
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