@extends('layouts.cuniapp')
@section('title', 'Modifier Lapin - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-pencil-square"></i> Modifier le Lapin
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('lapins.index') }}">Tous les Lapins</a>
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
                <i class="bi bi-info-circle"></i> Informations du Lapin
            </h3>
            <a href="{{ route('lapins.index') }}" class="btn-cuni sm secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('lapins.update', $lapin->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-select" required disabled>
                            <option value="male" {{ $lapin->type === 'male' ? 'selected' : '' }}>Mâle</option>
                            <option value="femelle" {{ $lapin->type === 'femelle' ? 'selected' : '' }}>Femelle</option>
                        </select>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Le type ne peut pas être modifié
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nom" class="form-control" value="{{ old('nom', $lapin->nom) }}"
                            required placeholder="Nom du lapin">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Race *</label>
                        <input type="text" name="race" class="form-control" value="{{ old('race', $lapin->race) }}"
                            required placeholder="Ex: Californien">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Origine *</label>
                        <select name="origine" class="form-select" required>
                            <option value="Interne" {{ old('origine', $lapin->origine) === 'Interne' ? 'selected' : '' }}>
                                Interne</option>
                            <option value="Achat" {{ old('origine', $lapin->origine) === 'Achat' ? 'selected' : '' }}>
                                Achat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de naissance *</label>
                        <input type="date" name="date_naissance" class="form-control"
                            value="{{ old('date_naissance', $lapin->date_naissance) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">État *</label>
                        <select name="etat" class="form-select" required>
                            <option value="active" {{ old('etat', $lapin->etat) === 'active' ? 'selected' : '' }}>Actif
                            </option>
                            <option value="inactive" {{ old('etat', $lapin->etat) === 'inactive' ? 'selected' : '' }}>
                                Inactif</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-check-circle"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('lapins.index') }}" class="btn-cuni secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
