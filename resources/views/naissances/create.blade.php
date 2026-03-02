@extends('layouts.cuniapp')
@section('title', 'Nouvelle Naissance - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-plus-circle"></i> Nouvelle Naissance
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
            <i class="bi bi-egg-fill"></i> Informations de la Naissance
        </h3>
        <a href="{{ route('naissances.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('naissances.store') }}" method="POST">
            @csrf
            <div class="settings-grid">
                <!-- Section: Femelle -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-arrow-down-right-square"></i> Femelle
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Femelle *</label>
                        <select name="femelle_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($femelles as $femelle)
                            <option value="{{ $femelle->id }}" {{ old('femelle_id') == $femelle->id ? 'selected' : '' }}>
                                {{ $femelle->nom }} ({{ $femelle->code }}) - {{ $femelle->etat }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Saillie (optionnel)</label>
                        <select name="saillie_id" class="form-select">
                            <option value="">-- Aucune --</option>
                            @foreach($saillies as $s)
                            <option value="{{ $s->id }}" {{ old('saillie_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->femelle->nom }} × {{ $s->male->nom }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Section: Date & Lieu -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-calendar"></i> Date & Lieu
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Date de naissance *</label>
                        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure (optionnel)</label>
                        <input type="time" name="heure_naissance" class="form-control" value="{{ old('heure_naissance') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lieu (Box/Cage)</label>
                        <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance') }}" placeholder="Ex: Box A-12">
                    </div>
                </div>

                <!-- Section: Portée -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-collection"></i> Détails de la Portée
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Nombre de vivants *</label>
                        <input type="number" name="nb_vivant" class="form-control" value="{{ old('nb_vivant', 1) }}" required min="0" max="20" id="nbVivant">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre de mort-nés</label>
                        <input type="number" name="nb_mort_ne" class="form-control" value="{{ old('nb_mort_ne', 0) }}" min="0" max="20" id="nbMortNe">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total de la portée</label>
                        <input type="text" id="totalPortee" class="form-control" readonly style="background: var(--surface-alt); font-weight: 600;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Poids moyen à la naissance (g)</label>
                        <input type="number" step="0.01" name="poids_moyen_naissance" class="form-control" value="{{ old('poids_moyen_naissance') }}" min="0" max="200">
                    </div>
                </div>

                <!-- Section: Santé & Suivi -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-heart-pulse"></i> Santé & Suivi
                    </h4>
                    <div class="form-group">
                        <label class="form-label">État de santé *</label>
                        <select name="etat_sante" class="form-select" required>
                            <option value="Excellent" {{ old('etat_sante') == 'Excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="Bon" {{ old('etat_sante') == 'Bon' || !old('etat_sante') ? 'selected' : '' }}>Bon</option>
                            <option value="Moyen" {{ old('etat_sante') == 'Moyen' ? 'selected' : '' }}>Moyen</option>
                            <option value="Faible" {{ old('etat_sante') == 'Faible' ? 'selected' : '' }}>Faible</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de sevrage prévue</label>
                        <input type="date" name="date_sevrage_prevue" class="form-control" value="{{ old('date_sevrage_prevue') }}">
                        <small style="color: var(--text-tertiary); font-size: 12px;">
                            <i class="bi bi-info-circle"></i> Auto-calculé: 6 semaines après naissance
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de vaccination prévue</label>
                        <input type="date" name="date_vaccination_prevue" class="form-control" value="{{ old('date_vaccination_prevue') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Observations</label>
                        <textarea name="observations" class="form-control" rows="3" placeholder="Notes sur la portée...">{{ old('observations') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
                <a href="{{ route('naissances.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nbVivant = document.getElementById('nbVivant');
    const nbMortNe = document.getElementById('nbMortNe');
    const totalPortee = document.getElementById('totalPortee');
    const dateNaissance = document.querySelector('input[name="date_naissance"]');
    const dateSevrage = document.querySelector('input[name="date_sevrage_prevue"]');

    function updateTotal() {
        const vivant = parseInt(nbVivant.value) || 0;
        const mortNe = parseInt(nbMortNe.value) || 0;
        totalPortee.value = vivant + mortNe;
    }

    nbVivant.addEventListener('input', updateTotal);
    nbMortNe.addEventListener('input', updateTotal);
    updateTotal();

    // Auto-calculate sevrage date
    dateNaissance.addEventListener('change', function() {
        if (this.value && !dateSevrage.value) {
            const birthDate = new Date(this.value);
            const sevrageDate = new Date(birthDate);
            sevrageDate.setDate(sevrageDate.getDate() + 42);
            dateSevrage.value = sevrageDate.toISOString().split('T')[0];
        }
    });
});
</script>

<style>
.form-section {
    background: var(--surface-alt);
    border-radius: var(--radius-lg);
    padding: 20px;
    border: 1px solid var(--surface-border);
}
.section-subtitle {
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-subtitle i {
    color: var(--primary);
}
</style>
@endsection