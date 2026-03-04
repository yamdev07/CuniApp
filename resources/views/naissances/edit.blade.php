@extends('layouts.cuniapp')
@section('title', 'Modifier Naissance - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title"><i class="bi bi-pencil-square"></i> Modifier la Naissance</h2>
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
        <h3 class="card-title"><i class="bi bi-egg-fill"></i> Informations de la Naissance</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('naissances.update', $naissance) }}" method="POST" id="naissanceEditForm">
            @csrf
            @method('PUT')
            
            @if (!$naissance->sex_verified)
            <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); 
                border-radius: var(--radius-lg); padding: 16px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 24px; color: var(--accent-orange);"></i>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--accent-orange);">
                            Vérification du sexe en attente
                        </div>
                        <div style="font-size: 13px; color: var(--text-secondary);">
                            Cette portée a {{ $naissance->jours_depuis_naissance }} jours.
                            @if($canVerifySex)
                                <span style="color: var(--accent-green); font-weight: 600;">
                                    ✅ Vous pouvez maintenant vérifier le sexe des lapereaux
                                </span>
                            @else
                                <span style="color: var(--accent-orange); font-weight: 600;">
                                    ⏳ Attendez encore {{ $daysUntilVerification }} jours
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($naissance->reminder_count > 0)
                    <div style="background: var(--accent-orange); color: white; padding: 4px 12px; 
                        border-radius: 20px; font-size: 12px; font-weight: 600;">
                        {{ $naissance->reminder_count }} rappel(s)
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Existing rabbits with sex verification -->
            <div class="form-section" style="border: 2px solid var(--primary-subtle);">
                <h4 class="section-subtitle"><i class="bi bi-collection"></i> Lapereaux ({{ $naissance->lapereaux->count() }})</h4>
                
                <div id="rabbitsContainer"></div>
                
                <button type="button" class="btn-cuni secondary" onclick="addRabbitRow()" style="margin-top: 12px;">
                    <i class="bi bi-plus-lg"></i> Ajouter un lapereau
                </button>
            </div>

            <!-- Verification Checkbox (only if 10+ days) -->
            @if($canVerifySex)
            <div class="form-section" style="background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2); margin-top: 24px;">
                <h4 class="section-subtitle">
                    <i class="bi bi-shield-check" style="color: var(--accent-green);"></i> 
                    Confirmation de Vérification
                </h4>
                <div class="form-group">
                    <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="sex_verified" value="1" 
                            {{ old('sex_verified', $naissance->sex_verified) ? 'checked' : '' }} 
                            style="width: 20px; height: 20px; accent-color: var(--accent-green);">
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">
                                Sexe et date vérifiés
                            </div>
                            <div style="font-size: 13px; color: var(--text-tertiary);">
                                Je confirme que le sexe des {{ $naissance->total_lapereaux }} lapereaux a été vérifié
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            @else
            <div class="alert-box warning" style="margin-top: 24px;">
                <i class="bi bi-lock"></i>
                <div>
                    <strong>Vérification verrouillée:</strong> 
                    La vérification du sexe sera disponible dans {{ $daysUntilVerification }} jours.
                </div>
            </div>
            @endif

            <div style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i> Enregistrer les modifications
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
const existingRabbits = @json($naissance->lapereaux ?? []);
const canVerifySex = {{ $canVerifySex ? 'true' : 'false' }};

function addRabbitRow(data = {}) {
    rabbitCount++;
    const container = document.getElementById('rabbitsContainer');
    const row = document.createElement('div');
    row.className = 'rabbit-row';
    row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 10px; padding: 10px; background: var(--surface-alt); border-radius: var(--radius); align-items: end;';
    
    // Sex field: required if can verify, optional otherwise
    const sexRequired = canVerifySex ? 'required' : '';
    const sexDisabled = !canVerifySex && !data.sex ? 'disabled' : '';
    
    row.innerHTML = `
        <div>
            <label class="form-label" style="font-size: 12px;">Nom</label>
            <input type="text" name="rabbits[${rabbitCount}][nom]" class="form-control" 
                value="${data.nom || ''}" placeholder="Ex: Toto">
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Sexe ${canVerifySex ? '*' : ''}</label>
            <select name="rabbits[${rabbitCount}][sex]" class="form-select rabbit-sex" ${sexRequired} ${sexDisabled}>
                <option value="">${canVerifySex ? '-- Sélectionner --' : '-- À vérifier --'}</option>
                <option value="male" ${data.sex === 'male' ? 'selected' : ''}>Mâle</option>
                <option value="female" ${data.sex === 'female' ? 'selected' : ''}>Femelle</option>
            </select>
            ${!canVerifySex ? '<small style="color: var(--accent-orange); font-size: 10px;">⏳ Attendez 10 jours</small>' : ''}
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">Code</label>
            <input type="text" class="form-control" value="${data.code || 'Auto'}" readonly 
                style="background: var(--gray-100);">
            ${data.id ? `<input type="hidden" name="rabbits[${rabbitCount}][id]" value="${data.id}">` : ''}
        </div>
        <div>
            <label class="form-label" style="font-size: 12px;">État *</label>
            <select name="rabbits[${rabbitCount}][etat]" class="form-select rabbit-etat" required>
                <option value="vivant" ${data.etat === 'vivant' ? 'selected' : ''}>Vivant</option>
                <option value="mort" ${data.etat === 'mort' ? 'selected' : ''}>Mort</option>
                <option value="vendu" ${data.etat === 'vendu' ? 'selected' : ''}>Vendu</option>
            </select>
        </div>
        <button type="button" class="btn-cuni sm danger" onclick="removeRabbitRow(this)" style="margin-bottom: 0;">
            <i class="bi bi-trash"></i>
        </button>
    `;
    
    container.appendChild(row);
}

function removeRabbitRow(btn) {
    btn.closest('.rabbit-row').remove();
}

// Initialize with existing rabbits
window.addEventListener('DOMContentLoaded', () => {
    if (existingRabbits.length > 0) {
        existingRabbits.forEach(rabbit => {
            addRabbitRow({
                id: rabbit.id,
                nom: rabbit.nom,
                code: rabbit.code,
                sex: rabbit.sex,
                etat: rabbit.etat
            });
        });
    } else {
        addRabbitRow();
        addRabbitRow();
        addRabbitRow();
    }
});
</script>
@endpush
@endsection