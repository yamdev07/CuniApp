{{-- resources/views/expenses/create.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Nouvelle Dépense - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title"><i class="bi bi-plus-circle"></i> Nouvelle Dépense</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('expenses.index') }}">Dépenses</a>
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
            <h3 class="card-title"><i class="bi bi-receipt"></i> Informations de la Dépense</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Catégorie *</label>
                        <select name="category" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Alimentation" {{ old('category') === 'Alimentation' ? 'selected' : '' }}>
                                Alimentation</option>
                            <option value="Vétérinaire" {{ old('category') === 'Vétérinaire' ? 'selected' : '' }}>
                                Vétérinaire</option>
                            <option value="Équipement" {{ old('category') === 'Équipement' ? 'selected' : '' }}>Équipement
                            </option>
                            <option value="Transport" {{ old('category') === 'Transport' ? 'selected' : '' }}>Transport
                            </option>
                            <option value="Autre" {{ old('category') === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant (FCFA) *</label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" required
                            min="0" step="0.01" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de la dépense *</label>
                        <input type="date" name="expense_date" class="form-control"
                            value="{{ old('expense_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Détails de la dépense...">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-cuni primary">
                        <i class="bi bi-check-circle"></i> Enregistrer
                    </button>
                    <a href="{{ route('expenses.index') }}" class="btn-cuni secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
