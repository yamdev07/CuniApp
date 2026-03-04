@extends('layouts.cuniapp')
@section('title', 'Nouvelle Naissance - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-plus-circle"></i> Nouvelle Naissance</h2>
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
        <h3 class="card-title"><i class="bi bi-egg-fill"></i> Informations de la Naissance</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('naissances.store') }}" method="POST" id="naissanceForm">
            @csrf
            
            <div class="settings-grid">
                <!-- Section: Mise Bas -->
                <div class="form-section">
                    <h4 class="section-subtitle"><i class="bi bi-calendar-check"></i> Mise Bas</h4>
                    <div class="form-group">
                        <label class="form-label">Mise Bas *</label>
                        <select name="mise_bas_id" class="form-select" required id="miseBasSelect">
                            <option value="">-- Sélectionner une mise bas --</option>
                            @foreach($misesBas as $mb)
                            <option value="{{ $mb->id }}" 
                                {{ (old('mise_bas_id') == $mb->id || (isset($miseBas) && $miseBas->id == $mb->id)) ? 'selected' : '' }}>
                                {{ $mb->femelle->nom }} ({{ $mb->femelle->code }}) - {{ $mb->date_mise_bas->format('d/m/Y') }}
                            </option>
                            @endforeach
                        </select>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> La date de naissance sera automatiquement celle de la mise bas
                        </small>
                    </div>
                    
                    @if(isset($miseBas))
                    <div class="alert-box info" style="margin-top: 16px;">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Mise bas sélectionnée:</strong> {{ $miseBas->femelle->nom }}
                            <br>
                            <small>Date: {{ $miseBas->date_mise_bas->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endif
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
                        <input type="number" step="0.01" name="poids_moyen_naissance" class="form-control" 
                            value="{{ old('poids_moyen_naissance') }}" min="0" max="200">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de sevrage prévue</label>
                        <input type="date" name="date_sevrage_prevue" class="form-control" 
                            value="{{ old('date_sevrage_prevue') }}">
                        <small style="color: var(--text-tertiary); font-size: 12px;">
                            <i class="bi bi-info-circle"></i> Recommandé: 6 semaines après la naissance
                        </small>
                    </div>
                </div>
            </div>

            <!-- ✅ Lapereaux Section -->
            <div class="form-section" style="margin-top: 24px; border: 2px solid var(--primary-subtle);">
                <h4 class="section-subtitle"><i class="bi bi-collection"></i> Lapereaux (Codes Auto-Générés)</h4>
                <div class="alert-box warning" style="margin-bottom: 16px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Important:</strong> Le sexe ne peut être vérifié qu'après 10 jours. 
                        Vous pourrez modifier cette information plus tard.
                    </div>
                </div>
                
                <div id="rabbitsContainer"></div>
                
                <button type="button" class="btn-cuni secondary" onclick="addRabbitRow()" style="margin-top: 12px;">
                    <i class="bi bi-plus-lg"></i> Ajouter un lapereau
                </button>
                
                <div style="margin-top: 16px; font-weight: 600; color: var(--primary);">
                    Total Lapereaux: <span id="totalLapereauxDisplay">0</span>
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
            <label class="form-label" style="font-size: 12px;">Nom (Recommandé)</label>
            <input type="text" name="rabbits[${rabbitCount}][nom]" class="form-control" 
                value="${data.nom || ''}" placeholder="Ex: Toto">
            <small style="color: var(--text-tertiary); font-size: 10px;">Pour identification facile</small>
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Sexe</label>
            <select name="rabbits[${rabbitCount}][sex]" class="form-select rabbit-sex">
                <option value="">-- À vérifier (10 jours) --</option>
                <option value="male" ${data.sex === 'male' ? 'selected' : ''}>Mâle</option>
                <option value="female" ${data.sex === 'female' ? 'selected' : ''}>Femelle</option>
            </select>
            <small style="color: var(--accent-orange); font-size: 10px;">⚠️ Optionnel maintenant</small>
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">État *</label>
            <select name="rabbits[${rabbitCount}][etat]" class="form-select rabbit-etat" required>
                <option value="vivant" ${data.etat === 'vivant' ? 'selected' : ''}>Vivant</option>
                <option value="mort" ${data.etat === 'mort' ? 'selected' : ''}>Mort-né/Décédé</option>
                <option value="vendu" ${data.etat === 'vendu' ? 'selected' : ''}>Vendu</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Code</label>
            <input type="text" class="form-control" value="Auto-généré" readonly 
                style="background: var(--gray-100); color: var(--text-tertiary);">
            <small style="color: var(--primary); font-size: 10px;">✨ Généré automatiquement</small>
        </div>
        <button type="button" class="btn-cuni sm danger" onclick="removeRabbitRow(this)" style="margin-bottom: 0;">
            <i class="bi bi-trash"></i>
        </button>
    `;
    
    container.appendChild(row);
    updateTotalLapereaux();
}

function removeRabbitRow(btn) {
    btn.closest('.rabbit-row').remove();
    updateTotalLapereaux();
}

function updateTotalLapereaux() {
    const count = document.querySelectorAll('.rabbit-row').length;
    document.getElementById('totalLapereauxDisplay').textContent = count;
}

// Auto-calculate sevrage date based on mise_bas
document.getElementById('miseBasSelect')?.addEventListener('change', function() {
    const sevrageInput = document.querySelector('input[name="date_sevrage_prevue"]');
    const selectedOption = this.options[this.selectedIndex];
    if (this.value && sevrageInput && !sevrageInput.value) {
        // Parse date from option text (simplified - in production use data attributes)
        const birthDate = new Date(); // Would get from API in production
        const sevrageDate = new Date(birthDate);
        sevrageDate.setDate(sevrageDate.getDate() + 42); // 6 weeks
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