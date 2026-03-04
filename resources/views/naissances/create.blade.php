@extends('layouts.cuniapp')
@section('title', 'Nouvelle Naissance - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-plus-circle"></i> Nouvelle Naissance</h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a> <span>/</span>
            <a href="{{ route('naissances.index') }}">Naissances</a> <span>/</span> <span>Nouveau</span>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert-cuni error">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div><strong>Erreurs de validation</strong>
        <ul style="margin: 8px 0 0 20px; padding: 0;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title"><i class="bi bi-egg-fill"></i> Informations de la Naissance</h3>
        <a href="{{ route('naissances.index') }}" class="btn-cuni sm secondary"><i class="bi bi-arrow-left"></i> Retour</a>
    </div>
    <div class="card-body">
        <form action="{{ route('naissances.store') }}" method="POST" id="naissanceForm">
            @csrf
            <div class="settings-grid">
                <!-- Section: Femelle -->
                <div class="form-section">
                    <h4 class="section-subtitle"><i class="bi bi-arrow-down-right-square"></i> Femelle</h4>
                    <div class="form-group">
                        <label class="form-label">Femelle *</label>
                        <select name="femelle_id" class="form-select" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach ($femelles as $femelle)
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
                            @foreach ($saillies as $s)
                                <option value="{{ $s->id }}" {{ old('saillie_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->femelle->nom ?? 'N/A' }} × {{ $s->male->nom ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Section: Date & Lieu -->
                <div class="form-section">
                    <h4 class="section-subtitle"><i class="bi bi-calendar"></i> Date & Lieu</h4>
                    <div class="form-group">
                        <label class="form-label">Date de naissance *</label>
                        <input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', date('Y-m-d')) }}" required id="dateNaissance">
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

                <!-- Section: Santé -->
                <div class="form-section">
                    <h4 class="section-subtitle"><i class="bi bi-heart-pulse"></i> Santé & Suivi</h4>
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
                        <label class="form-label">Poids moyen à la naissance (g)</label>
                        <input type="number" step="0.01" name="poids_moyen_naissance" class="form-control" value="{{ old('poids_moyen_naissance') }}" min="0" max="200">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de sevrage prévue</label>
                        <input type="date" name="date_sevrage_prevue" class="form-control" value="{{ old('date_sevrage_prevue') }}">
                    </div>
                </div>
            </div>

            <!-- ✅ NEW: Individual Rabbits Section -->
            <div class="form-section" style="margin-top: 24px; border: 2px solid var(--primary-subtle);">
                <h4 class="section-subtitle"><i class="bi bi-collection"></i> Lapereaux (Individuel)</h4>
                <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">
                    Ajoutez chaque lapereau individuellement. Le nombre de vivants sera calculé automatiquement.
                </p>
                
                <div id="rabbitsContainer">
                    <!-- Dynamic Rows will be added here -->
                </div>

                <button type="button" class="btn-cuni secondary" onclick="addRabbitRow()" style="margin-top: 12px;">
                    <i class="bi bi-plus-lg"></i> Ajouter un lapereau
                </button>
                
                <div style="margin-top: 16px; font-weight: 600; color: var(--primary);">
                    Total Vivants: <span id="totalVivantsDisplay">0</span>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <button type="submit" class="btn-cuni primary"><i class="bi bi-check-circle"></i> Enregistrer</button>
                <a href="{{ route('naissances.index') }}" class="btn-cuni secondary"><i class="bi bi-x-circle"></i> Annuler</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let rabbitCount = 0;

function addRabbitRow(data = {}) {
    rabbitCount++;
    const container = document.getElementById('rabbitsContainer');
    const row = document.createElement('div');
    row.className = 'rabbit-row';
    row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 10px; padding: 10px; background: var(--surface-alt); border-radius: var(--radius); align-items: end;';
    
    row.innerHTML = `
        <div>
            <label class="form-label" style="font-size: 12px;">Sexe *</label>
            <select name="rabbits[${rabbitCount}][sex]" class="form-select rabbit-sex" required onchange="updateTotalVivants()">
                <option value="male" ${data.sex === 'male' ? 'selected' : ''}>Mâle</option>
                <option value="female" ${data.sex === 'female' ? 'selected' : ''}>Femelle</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Nom (Optionnel)</label>
            <input type="text" name="rabbits[${rabbitCount}][nom]" class="form-control" value="${data.nom || ''}" placeholder="Ex: Toto">
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Code (Optionnel)</label>
            <input type="text" name="rabbits[${rabbitCount}][code]" class="form-control" value="${data.code || ''}" placeholder="Auto">
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">État *</label>
            <select name="rabbits[${rabbitCount}][etat]" class="form-select rabbit-etat" required onchange="updateTotalVivants()">
                <option value="vivant" ${data.etat === 'vivant' ? 'selected' : ''}>Vivant</option>
                <option value="mort" ${data.etat === 'mort' ? 'selected' : ''}>Mort-né/Décédé</option>
                <option value="vendu" ${data.etat === 'vendu' ? 'selected' : ''}>Vendu</option>
            </select>
        </div>
        <button type="button" class="btn-cuni sm danger" onclick="removeRabbitRow(this)" style="margin-bottom: 0;">
            <i class="bi bi-trash"></i>
        </button>
    `;
    container.appendChild(row);
    updateTotalVivants();
}

function removeRabbitRow(btn) {
    btn.closest('.rabbit-row').remove();
    updateTotalVivants();
}

function updateTotalVivants() {
    let count = 0;
    document.querySelectorAll('.rabbit-etat').forEach(select => {
        if (select.value === 'vivant') count++;
    });
    document.getElementById('totalVivantsDisplay').textContent = count;
}

// Auto-calculate sevrage date
document.getElementById('dateNaissance').addEventListener('change', function() {
    const sevrageInput = document.querySelector('input[name="date_sevrage_prevue"]');
    if (this.value && !sevrageInput.value) {
        const birthDate = new Date(this.value);
        const sevrageDate = new Date(birthDate);
        sevrageDate.setDate(sevrageDate.getDate() + 42);
        sevrageInput.value = sevrageDate.toISOString().split('T')[0];
    }
});

// Add initial 3 rows
window.addEventListener('DOMContentLoaded', () => {
    addRabbitRow();
    addRabbitRow();
    addRabbitRow();
});
</script>
@endpush
@endsection