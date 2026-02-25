@extends('layouts.cuniapp')
@section('title', 'Nouveau Mâle - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-plus-circle"></i> Nouveau Mâle
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('males.index') }}">Mâles</a>
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
            <i class="bi bi-info-circle"></i> Informations du Mâle
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('males.store') }}" method="POST" id="maleForm">
            @csrf
            <div class="settings-grid">
                <div class="form-group">
                    <label class="form-label">Code *</label>
                    <div class="code-generator">
                        <input 
                            type="text" 
                            name="code" 
                            id="codeInput"
                            class="form-control @error('code') is-invalid @enderror" 
                            value="{{ old('code', $suggestedCode) }}" 
                            required
                            aria-describedby="codeHelp"
                            data-check-url="{{ route('males.check-code') }}"
                        >
                        <small id="codeHelp" class="form-text">
                            ✨ Code généré automatiquement (<span id="nextCode">{{ $suggestedCode }}</span>). Modifiable si unique.
                        </small>
                        <div id="codeValidation" class="invalid-feedback mt-1" style="display: none;"></div>
                        @error('code')
                            <div class="invalid-feedback d-block mt-1">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                           value="{{ old('nom') }}" required placeholder="Ex: Max">
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Race</label>
                    <input type="text" name="race" class="form-control @error('race') is-invalid @enderror" 
                           value="{{ old('race') }}" placeholder="Ex: Californien, Blanc de Vienne">
                    @error('race')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Origine *</label>
                    <select name="origine" class="form-select @error('origine') is-invalid @enderror" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Interne" {{ old('origine') == 'Interne' ? 'selected' : '' }}>Interne</option>
                        <option value="Achat" {{ old('origine') == 'Achat' ? 'selected' : '' }}>Achat</option>
                    </select>
                    @error('origine')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" 
                           value="{{ old('date_naissance') }}">
                    @error('date_naissance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">État *</label>
                    <select name="etat" class="form-select @error('etat') is-invalid @enderror" required>
                        <option value="Active" {{ old('etat') == 'Active' ? 'selected' : '' }}>Actif</option>
                        <option value="Inactive" {{ old('etat') == 'Inactive' ? 'selected' : '' }}>Repos</option>
                        <option value="Malade" {{ old('etat') == 'Malade' ? 'selected' : '' }}>Malade</option>
                    </select>
                    @error('etat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <button type="submit" class="btn-cuni primary" id="submitBtn">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
                <a href="{{ route('males.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.code-generator { position: relative; }
.code-generator .form-control {
    padding-right: 40px;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 600;
    letter-spacing: 1px;
    font-size: 1.05rem;
}
.code-generator .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-subtle);
}
.code-generator .form-text {
    font-size: 0.85rem;
    color: var(--accent-cyan);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
    font-weight: 500;
}
.code-generator .form-text #nextCode {
    background: var(--primary-subtle);
    color: var(--primary);
    padding: 1px 6px;
    border-radius: 4px;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.invalid-feedback {
    color: var(--accent-red);
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 4px;
}
.form-control.is-invalid, .form-select.is-invalid {
    border-color: var(--accent-red);
    padding-right: 32px;
}
.form-control.is-valid {
    border-color: var(--accent-green);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    padding-right: 32px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('codeInput');
    const codeValidation = document.getElementById('codeValidation');
    const submitBtn = document.getElementById('submitBtn');
    const checkUrl = codeInput.dataset.checkUrl;
    let validationTimeout;
    
    // Real-time uniqueness check
    codeInput.addEventListener('input', function() {
        clearTimeout(validationTimeout);
        codeValidation.style.display = 'none';
        codeInput.classList.remove('is-valid', 'is-invalid');
        
        if (this.value.length < 3) return;
        
        validationTimeout = setTimeout(() => {
            fetch(`${checkUrl}?code=${encodeURIComponent(this.value)}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.available) {
                        codeValidation.innerHTML = `<i class="bi bi-x-circle-fill"></i> Ce code existe déjà !`;
                        codeValidation.style.display = 'block';
                        codeInput.classList.add('is-invalid');
                        codeInput.classList.remove('is-valid');
                        submitBtn.disabled = true;
                    } else {
                        codeValidation.innerHTML = `<i class="bi bi-check-circle-fill"></i> Code disponible`;
                        codeValidation.style.display = 'block';
                        codeValidation.style.color = 'var(--accent-green)';
                        codeInput.classList.add('is-valid');
                        codeInput.classList.remove('is-invalid');
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Validation error:', error);
                    codeInput.classList.remove('is-valid', 'is-invalid');
                    submitBtn.disabled = false;
                });
        }, 500);
    });
    
    // Re-enable button on page load if valid
    if (!codeInput.classList.contains('is-invalid')) {
        submitBtn.disabled = false;
    }
    
    // Form submission safety check
    document.getElementById('maleForm').addEventListener('submit', function(e) {
        if (codeInput.classList.contains('is-invalid')) {
            e.preventDefault();
            codeValidation.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endpush