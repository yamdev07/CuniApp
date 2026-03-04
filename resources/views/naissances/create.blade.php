{{-- resources/views/naissances/create.blade.php --}}
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
                                @if($mb->nb_vivant || $mb->nb_mort_ne)
                                    ({{ $mb->nb_vivant }} vivants + {{ $mb->nb_mort_ne }} morts-nés)
                                @endif
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
                            <strong>Mise bas sélectionnée:</strong> {{ $miseBas->femelle->nom }}<br>
                            <small>Date: {{ $miseBas->date_mise_bas->format('d/m/Y') }}</small><br>
                            @if($miseBas->nb_vivant || $miseBas->nb_mort_ne)
                            <small>Maximum: <strong>{{ $miseBas->nb_vivant + $miseBas->nb_mort_ne }} lapereaux</strong> 
                            ({{ $miseBas->nb_vivant }} vivants + {{ $miseBas->nb_mort_ne }} morts-nés)</small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Section: Santé Générale de la Portée -->
                <div class="form-section">
                    <h4 class="section-subtitle"><i class="bi bi-heart-pulse"></i> Santé & Suivi (Portée)</h4>
                    <div class="form-group">
                        <label class="form-label">État de santé général *</label>
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
                        <small style="color: var(--text-tertiary); font-size: 12px;">
                            <i class="bi bi-info-circle"></i> Moyenne de la portée (optionnel)
                        </small>
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

            <!-- ✅ Lapereaux Section with Individual Fields -->
            <div class="form-section" style="margin-top: 24px; border: 2px solid var(--primary-subtle);">
                <h4 class="section-subtitle">
                    <i class="bi bi-collection"></i> Lapereaux (Champs Individuels)
                </h4>
                
                <div class="alert-box warning" style="margin-bottom: 16px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Important:</strong> 
                        <ul style="margin: 8px 0 0 16px; padding: 0;">
                            <li>Le sexe ne peut être vérifié qu'après 10 jours</li>
                            <li>Le nombre de lapereaux ne doit pas dépasser celui de la mise bas</li>
                            <li>Chaque lapereau a son propre poids et état de santé</li>
                        </ul>
                    </div>
                </div>

                <!-- ✅ Max Allowed Display -->
                <div id="maxAllowedDisplay" style="display: none; margin-bottom: 16px; padding: 12px; background: var(--primary-subtle); border-radius: var(--radius);">
                    <strong>Maximum autorisé:</strong> <span id="maxAllowedValue">0</span> lapereaux<br>
                    <small>Actuellement: <span id="currentCount">0</span> lapereaux</small>
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
let maxAllowed = 0;

function addRabbitRow(data = {}) {
    // ✅ Check max allowed
    if (maxAllowed > 0) {
        const currentCount = document.querySelectorAll('.rabbit-row').length;
        if (currentCount >= maxAllowed) {
            showToast(`Maximum de ${maxAllowed} lapereaux atteint pour cette mise bas`, 'warning');
            return;
        }
    }

    rabbitCount++;
    const container = document.getElementById('rabbitsContainer');
    const row = document.createElement('div');
    row.className = 'rabbit-row';
    row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 10px; padding: 10px; background: var(--surface-alt); border-radius: var(--radius); align-items: end;';
    
    row.innerHTML = `
        <div>
            <label class="form-label" style="font-size: 12px;">Code *</label>
            <input type="text" name="rabbits[${rabbitCount}][code]" class="form-control rabbit-code" 
                value="${data.code || 'Auto-généré'}" 
                ${!data.code ? 'readonly style="background: var(--gray-100); color: var(--text-tertiary);"' : ''}
                placeholder="LAP-2026-0001"
                data-check-url="{{ route('lapins.check-code') }}">
            <small style="color: var(--primary); font-size: 10px;">✨ Modifiable si unique</small>
            <div class="code-validation" style="font-size: 10px; margin-top: 2px;"></div>
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Nom</label>
            <input type="text" name="rabbits[${rabbitCount}][nom]" class="form-control" 
                value="${data.nom || ''}" placeholder="Ex: Toto">
            <small style="color: var(--text-tertiary); font-size: 10px;">Pour identification</small>
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
            <label class="form-label" style="font-size: 12px;">Poids (g)</label>
            <input type="number" step="0.01" name="rabbits[${rabbitCount}][poids_naissance]" 
                class="form-control" value="${data.poids_naissance || ''}" 
                min="0" max="200" placeholder="50-80g">
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Santé</label>
            <select name="rabbits[${rabbitCount}][etat_sante]" class="form-select">
                <option value="Excellent" ${data.etat_sante === 'Excellent' ? 'selected' : ''}>Excellent</option>
                <option value="Bon" ${data.etat_sante === 'Bon' || !data.etat_sante ? 'selected' : ''}>Bon</option>
                <option value="Moyen" ${data.etat_sante === 'Moyen' ? 'selected' : ''}>Moyen</option>
                <option value="Faible" ${data.etat_sante === 'Faible' ? 'selected' : ''}>Faible</option>
            </select>
        </div>
        <button type="button" class="btn-cuni sm danger" onclick="removeRabbitRow(this)" style="margin-bottom: 0;">
            <i class="bi bi-trash"></i>
        </button>
    `;
    
    container.appendChild(row);
    updateTotalLapereaux();
    
    // ✅ Add code validation listener
    const codeInput = row.querySelector('.rabbit-code');
    if (codeInput && !codeInput.readOnly) {
        setupCodeValidation(codeInput);
    }
}

function setupCodeValidation(codeInput) {
    let validationTimeout;
    codeInput.addEventListener('input', function() {
        clearTimeout(validationTimeout);
        const validationDiv = this.parentElement.querySelector('.code-validation');
        validationDiv.innerHTML = '';
        
        if (this.value.length < 3 || this.value === 'Auto-généré') return;
        
        validationTimeout = setTimeout(() => {
            fetch(`{{ route('lapins.check-code') }}?code=${encodeURIComponent(this.value)}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.available) {
                        validationDiv.innerHTML = '<span style="color: var(--accent-red);">❌ Code existe déjà</span>';
                        this.style.borderColor = 'var(--accent-red)';
                    } else {
                        validationDiv.innerHTML = '<span style="color: var(--accent-green);">✅ Code disponible</span>';
                        this.style.borderColor = 'var(--accent-green)';
                    }
                });
        }, 500);
    });
}

function removeRabbitRow(btn) {
    btn.closest('.rabbit-row').remove();
    updateTotalLapereaux();
}

function updateTotalLapereaux() {
    const count = document.querySelectorAll('.rabbit-row').length;
    document.getElementById('totalLapereauxDisplay').textContent = count;
    document.getElementById('currentCount').textContent = count;
    
    // ✅ Update max allowed warning
    if (maxAllowed > 0) {
        const display = document.getElementById('maxAllowedDisplay');
        if (count >= maxAllowed) {
            display.style.background = 'rgba(239, 68, 68, 0.1)';
            display.style.borderColor = 'var(--accent-red)';
        } else {
            display.style.background = 'var(--primary-subtle)';
            display.style.borderColor = 'var(--primary-subtle)';
        }
    }
}

// ✅ Load max allowed from mise_bas selection
document.getElementById('miseBasSelect')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const text = selectedOption.text;
    
    // Parse max from option text
    const match = text.match(/\((\d+) vivants \+ (\d+) morts-nés\)/);
    if (match) {
        maxAllowed = parseInt(match[1]) + parseInt(match[2]);
        document.getElementById('maxAllowedValue').textContent = maxAllowed;
        document.getElementById('maxAllowedDisplay').style.display = 'block';
    } else {
        maxAllowed = 0;
        document.getElementById('maxAllowedDisplay').style.display = 'none';
    }
    
    updateTotalLapereaux();
});

// Add initial 3 rows
window.addEventListener('DOMContentLoaded', () => {
    addRabbitRow();
    addRabbitRow();
    addRabbitRow();
});

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `position: fixed; bottom: 100px; right: 30px; background: var(--surface); 
        border: 1px solid var(--surface-border); border-left: 4px solid ${type === 'success' ? 'var(--accent-green)' : 'var(--accent-orange)'}; 
        padding: 16px 24px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); z-index: 9999;`;
    toast.innerHTML = `<span style="color: var(--text-primary);">${message}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush
@endsection