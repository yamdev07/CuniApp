@extends('layouts.cuniapp')

@section('title', 'Nouvelle Mise Bas - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-plus-circle"></i>
            Nouvelle Mise Bas
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('mises-bas.index') }}">Mises Bas</a>
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
            <i class="bi bi-egg"></i>
            Informations de la Mise Bas
        </h3>
        <a href="{{ route('mises-bas.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>
    </div>
    
    <div class="card-body">
        <form action="{{ route('mises-bas.store') }}" method="POST">
            @csrf
            
            <div class="settings-grid">
                <!-- Section: Femelle -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-arrow-down-right-square"></i>
                        Femelle
                    </h4>
                    
                    <div class="form-group">
                        <label class="form-label">Femelle *</label>
                        <select name="femelle_id" class="form-select" required>
                            <option value="">-- Sélectionner une femelle --</option>
                            @foreach($femelles as $femelle)
                                <option value="{{ $femelle->id }}" {{ old('femelle_id') == $femelle->id ? 'selected' : '' }}>
                                    {{ $femelle->nom }} ({{ $femelle->code }})
                                    @if($femelle->etat === 'Gestante')
                                        <span style="color: var(--accent-orange);">● Gestante</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Privilégiez les femelles en état "Gestante"
                        </small>
                    </div>
                </div>

                <!-- Section: Date -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-calendar"></i>
                        Date
                    </h4>
                    
                    <div class="form-group">
                        <label class="form-label">Date de mise bas *</label>
                        <input type="date" 
                               name="date_mise_bas" 
                               class="form-control" 
                               value="{{ old('date_mise_bas', date('Y-m-d')) }}" 
                               required>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-clock"></i>
                            Date réelle de la naissance
                        </small>
                    </div>
                </div>

                <!-- Section: Portée -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-collection"></i>
                        Portée
                    </h4>
                    
                    <div class="form-group">
                        <label class="form-label">Jeunes vivants *</label>
                        <input type="number" 
                               name="nb_vivant" 
                               class="form-control" 
                               value="{{ old('nb_vivant', 1) }}" 
                               required 
                               min="1"
                               max="20">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Minimum 1 lapereau vivant
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Morts-nés</label>
                        <input type="number" 
                               name="nb_mort_ne" 
                               class="form-control" 
                               value="{{ old('nb_mort_ne', 0) }}" 
                               min="0"
                               max="20">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Optionnel - laissez 0 si aucun
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total de la portée</label>
                        <input type="text" 
                               id="total_portee" 
                               class="form-control" 
                               readonly 
                               style="background: var(--surface-alt); font-weight: 600;">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-calculator"></i>
                            Calculé automatiquement
                        </small>
                    </div>
                </div>

                <!-- Section: Sevrage -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-clock-history"></i>
                        Sevrage
                    </h4>
                    
                    <div class="form-group">
                        <label class="form-label">Date de sevrage (prévue)</label>
                        <input type="date" 
                               name="date_sevrage" 
                               id="date_sevrage"
                               class="form-control" 
                               value="{{ old('date_sevrage') }}">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Recommandé: 6 semaines après la naissance
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Poids moyen au sevrage (kg)</label>
                        <input type="number" 
                               step="0.01" 
                               name="poids_moyen_sevrage" 
                               class="form-control" 
                               value="{{ old('poids_moyen_sevrage') }}" 
                               min="0"
                               max="5"
                               placeholder="0.00">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i>
                            Poids moyen par lapereau
                        </small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer la mise bas
                </button>
                <a href="{{ route('mises-bas.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate total portée
    const nbVivantInput = document.querySelector('input[name="nb_vivant"]');
    const nbMortNeInput = document.querySelector('input[name="nb_mort_ne"]');
    const totalPorteeInput = document.getElementById('total_portee');
    
    function updateTotal() {
        const vivant = parseInt(nbVivantInput.value) || 0;
        const mortNe = parseInt(nbMortNeInput.value) || 0;
        totalPorteeInput.value = vivant + mortNe;
    }
    
    nbVivantInput.addEventListener('input', updateTotal);
    nbMortNeInput.addEventListener('input', updateTotal);
    updateTotal(); // Initial calculation
    
    // Auto-calculate sevrage date (6 weeks after birth)
    const dateMiseBasInput = document.querySelector('input[name="date_mise_bas"]');
    const dateSevrageInput = document.getElementById('date_sevrage');
    
    dateMiseBasInput.addEventListener('change', function() {
        if (this.value) {
            const birthDate = new Date(this.value);
            const sevrageDate = new Date(birthDate);
            sevrageDate.setDate(sevrageDate.getDate() + 42); // 6 weeks = 42 days
            dateSevrageInput.value = sevrageDate.toISOString().split('T')[0];
        }
    });
    
    // Trigger initial sevrage date calculation if birth date exists
    if (dateMiseBasInput.value) {
        dateMiseBasInput.dispatchEvent(new Event('change'));
    }
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

.form-group:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .form-section {
        padding: 16px;
    }
}
</style>
@endsection