@extends('layouts.cuniapp')

@section('title', 'Modifier Saillie - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-pencil-square"></i>
            Modifier la Saillie
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('saillies.index') }}">Saillies</a>
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
            <i class="bi bi-heart"></i>
            Informations de la Saillie
        </h3>
        <a href="{{ route('saillies.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('saillies.update', $saillie->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Femelle *</label>
                    <select name="femelle_id" class="form-select" required>
                        @foreach($femelles as $f)
                        <option value="{{ $f->id }}" {{ $saillie->femelle_id == $f->id ? 'selected' : '' }}>
                            {{ $f->nom }} ({{ $f->code }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mâle *</label>
                    <select name="male_id" class="form-select" required>
                        @foreach($males as $m)
                        <option value="{{ $m->id }}" {{ $saillie->male_id == $m->id ? 'selected' : '' }}>
                            {{ $m->nom }} ({{ $m->code }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de saillie *</label>
                    <input type="date" name="date_saillie" class="form-control" value="{{ old('date_saillie', $saillie->date_saillie) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Date de palpation</label>
                    <input type="date" name="date_palpage" class="form-control" value="{{ old('date_palpage', $saillie->date_palpage) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Résultat de palpation</label>
                    <select name="palpation_resultat" class="form-select">
                        <option value="">-- Non défini --</option>
                        <option value="+" {{ $saillie->palpation_resultat == '+' ? 'selected' : '' }}>Positif (+)</option>
                        <option value="-" {{ $saillie->palpation_resultat == '-' ? 'selected' : '' }}>Négatif (-)</option>
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