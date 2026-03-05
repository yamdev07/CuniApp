{{-- resources/views/sales/create.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Nouvelle Vente - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-cart-plus-fill"></i> Nouvelle Vente
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('sales.index') }}">Ventes</a>
            <span>/</span>
            <span>Nouvelle</span>
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
            <i class="bi bi-receipt"></i> Informations de la vente
        </h3>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
            @csrf
            
            {{-- Section: Acheteur --}}
            <div class="form-section">
                <h4 class="section-subtitle">
                    <i class="bi bi-person"></i> Informations acheteur
                </h4>
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Nom de l'acheteur *</label>
                        <input type="text" name="buyer_name" class="form-control" value="{{ old('buyer_name') }}" required placeholder="Nom complet">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact</label>
                        <input type="text" name="buyer_contact" class="form-control" value="{{ old('buyer_contact') }}" placeholder="Téléphone ou email">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <textarea name="buyer_address" class="form-control" rows="2" placeholder="Adresse complète">{{ old('buyer_address') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de vente *</label>
                        <input type="date" name="date_sale" class="form-control" value="{{ old('date_sale', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>

            {{-- Section: Prix --}}
            <div class="form-section" style="margin-top: 24px;">
                <h4 class="section-subtitle">
                    <i class="bi bi-currency-euro"></i> Prix
                </h4>
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Prix unitaire (FCFA) *</label>
                        <input type="number" step="0.01" name="unit_price" id="unitPrice" class="form-control" required min="0" value="{{ old('unit_price', 0) }}">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Prix par lapin
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant total estimé</label>
                        <input type="text" id="totalAmountDisplay" class="form-control" readonly style="background: var(--surface-alt); font-weight: 600;" value="0 FCFA">
                    </div>
                </div>
            </div>

            {{-- ✅ Section: Sélection des Lapins --}}
            <div class="form-section" style="margin-top: 24px; border: 2px solid var(--primary-subtle);">
                <h4 class="section-subtitle">
                    <i class="bi bi-collection"></i> Sélection des Lapins à Vendre
                </h4>
                <div class="alert-box info" style="margin-bottom: 16px;">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>
                        <strong>Important:</strong>
                        <ul style="margin: 8px 0 0 16px; padding: 0;">
                            <li>Sélectionnez les lapins spécifiques à vendre par catégorie</li>
                            <li>Utilisez la recherche pour filtrer les lapins</li>
                            <li>Le nombre total de lapins sélectionnés doit correspondre à la quantité</li>
                        </ul>
                    </div>
                </div>

                {{-- Quantity Input --}}
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Quantité totale de lapins à vendre *</label>
                    <input type="number" name="quantity" id="totalQuantity" class="form-control" required min="1" value="{{ old('quantity', 1) }}">
                    <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                        <i class="bi bi-info-circle"></i> Nombre total de lapins dans cette vente
                    </small>
                    <div id="quantityValidation" class="validation-message" style="display: none; margin-top: 8px;"></div>
                </div>

                {{-- Tabs for categories --}}
                <div class="tabs-container" style="margin-bottom: 20px;">
                    <button type="button" class="tab-btn active" data-tab="males-tab">
                        <i class="bi bi-arrow-up-right-square"></i> Mâles (<span id="malesCount">0</span>)
                    </button>
                    <button type="button" class="tab-btn" data-tab="females-tab">
                        <i class="bi bi-arrow-down-right-square"></i> Femelles (<span id="femalesCount">0</span>)
                    </button>
                    <button type="button" class="tab-btn" data-tab="lapereaux-tab">
                        <i class="bi bi-egg-fill"></i> Lapereaux (<span id="lapereauxCount">0</span>)
                    </button>
                </div>

                {{-- ✅ Mâles Tab --}}
                <div class="tab-content active" id="males-tab">
                    <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                        <input type="text" class="form-control" placeholder="Rechercher un mâle..." id="searchMales" style="flex: 1; min-width: 250px;" onkeyup="filterRabbits('males', this.value)">
                        <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('males')">
                            <i class="bi bi-check-square"></i> Tout sélectionner
                        </button>
                        <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('males', false)">
                            <i class="bi bi-square"></i> Tout déselectionner
                        </button>
                    </div>
                    <div class="rabbit-selection-grid" id="malesGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                        @foreach($males as $male)
                        <label class="rabbit-card" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--surface-alt); border: 1px solid var(--surface-border); border-radius: var(--radius); cursor: pointer;">
                            <input type="checkbox" name="selected_males[]" value="{{ $male->id }}" class="rabbit-checkbox" data-category="males" data-code="{{ $male->code }}" data-name="{{ $male->nom }}">
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">{{ $male->nom }}</div>
                                <div style="font-size: 12px; color: var(--text-tertiary);">{{ $male->code }} • {{ $male->race ?? 'Non spécifié' }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ✅ Femelles Tab --}}
                <div class="tab-content" id="females-tab">
                    <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                        <input type="text" class="form-control" placeholder="Rechercher une femelle..." id="searchFemales" style="flex: 1; min-width: 250px;" onkeyup="filterRabbits('females', this.value)">
                        <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('females')">
                            <i class="bi bi-check-square"></i> Tout sélectionner
                        </button>
                        <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('females', false)">
                            <i class="bi bi-square"></i> Tout déselectionner
                        </button>
                    </div>
                    <div class="rabbit-selection-grid" id="femalesGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                        @foreach($femelles as $femelle)
                        <label class="rabbit-card" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--surface-alt); border: 1px solid var(--surface-border); border-radius: var(--radius); cursor: pointer;">
                            <input type="checkbox" name="selected_females[]" value="{{ $femelle->id }}" class="rabbit-checkbox" data-category="females" data-code="{{ $femelle->code }}" data-name="{{ $femelle->nom }}">
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">{{ $femelle->nom }}</div>
                                <div style="font-size: 12px; color: var(--text-tertiary);">{{ $femelle->code }} • {{ $femelle->race ?? 'Non spécifié' }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ✅ Lapereaux Tab --}}
                <div class="tab-content" id="lapereaux-tab">
                    <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                        <input type="text" class="form-control" placeholder="Rechercher un lapereau..." id="searchLapereaux" style="flex: 1; min-width: 250px;" onkeyup="filterRabbits('lapereaux', this.value)">
                        <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('lapereaux')">
                            <i class="bi bi-check-square"></i> Tout sélectionner
                        </button>
                        <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('lapereaux', false)">
                            <i class="bi bi-square"></i> Tout déselectionner
                        </button>
                    </div>
                    <div class="rabbit-selection-grid" id="lapereauxGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                        @foreach($lapereaux as $lapereau)
                        <label class="rabbit-card" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--surface-alt); border: 1px solid var(--surface-border); border-radius: var(--radius); cursor: pointer;">
                            <input type="checkbox" name="selected_lapereaux[]" value="{{ $lapereau->id }}" class="rabbit-checkbox" data-category="lapereaux" data-code="{{ $lapereau->code }}" data-name="{{ $lapereau->nom ?? 'Sans nom' }}">
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">{{ $lapereau->nom ?? 'Sans nom' }}</div>
                                <div style="font-size: 12px; color: var(--text-tertiary);">{{ $lapereau->code }} • {{ $lapereau->naissance->miseBas->femelle->nom ?? 'N/A' }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Selected Summary --}}
                <div style="margin-top: 24px; padding: 16px; background: var(--primary-subtle); border-radius: var(--radius-lg);">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <strong style="color: var(--primary);">📊 Résumé de la sélection:</strong>
                            <span id="selectedSummary" style="margin-left: 12px;">0 lapin(s) sélectionné(s)</span>
                        </div>
                        <div style="display: flex; gap: 16px;">
                            <span style="font-size: 13px;">🟦 Mâles: <strong id="selectedMalesCount">0</strong></span>
                            <span style="font-size: 13px;">🟩 Femelles: <strong id="selectedFemalesCount">0</strong></span>
                            <span style="font-size: 13px;">🟨 Lapereaux: <strong id="selectedLapereauxCount">0</strong></span>
                        </div>
                    </div>
                    <div id="quantityMismatchWarning" style="margin-top: 12px; padding: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius); display: none;">
                        <i class="bi bi-exclamation-triangle" style="color: var(--accent-orange);"></i>
                        <span style="color: var(--accent-orange); font-weight: 600;">Le nombre de lapins sélectionnés ne correspond pas à la quantité définie!</span>
                    </div>
                </div>
            </div>

            {{-- Section: Paiement --}}
            <div class="form-section" style="margin-top: 24px;">
                <h4 class="section-subtitle">
                    <i class="bi bi-credit-card"></i> Paiement
                </h4>
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Statut du paiement *</label>
                        <select name="payment_status" class="form-select" required id="paymentStatus">
                            <option value="paid">Payé intégralement</option>
                            <option value="partial" selected>Paiement partiel</option>
                            <option value="pending">En attente</option>
                        </select>
                    </div>
                    <div class="form-group" id="partialPaymentGroup">
                        <label class="form-label">Montant versé (FCFA)</label>
                        <input type="number" step="0.01" name="amount_paid" class="form-control" value="0" min="0">
                    </div>
                </div>
            </div>

            {{-- Section: Notes --}}
            <div class="form-section" style="margin-top: 24px;">
                <h4 class="section-subtitle">
                    <i class="bi bi-sticky-note"></i> Notes
                </h4>
                <div class="form-group">
                    <label class="form-label">Remarques</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <button type="submit" class="btn-cuni primary" id="submitBtn">
                    <i class="bi bi-check-circle"></i> Enregistrer la vente
                </button>
                <a href="{{ route('sales.index') }}" class="btn-cuni secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        document.getElementById(this.dataset.tab).classList.add('active');
    });
});

// Filter rabbits
function filterRabbits(category, searchTerm) {
    const grid = document.getElementById(category + 'Grid');
    const cards = grid.querySelectorAll('.rabbit-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const checkbox = card.querySelector('.rabbit-checkbox');
        const code = checkbox.dataset.code.toLowerCase();
        const name = checkbox.dataset.name.toLowerCase();
        
        if (code.includes(searchTerm.toLowerCase()) || name.includes(searchTerm.toLowerCase())) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update count
    document.getElementById(category + 'Count').textContent = visibleCount;
}

// Toggle select all
function toggleSelectAll(category, select = true) {
    const grid = document.getElementById(category + 'Grid');
    const checkboxes = grid.querySelectorAll('.rabbit-checkbox');
    
    checkboxes.forEach(checkbox => {
        if (checkbox.closest('.rabbit-card').style.display !== 'none') {
            checkbox.checked = select;
        }
    });
    
    updateSelectedSummary();
}

// Update selected summary
function updateSelectedSummary() {
    const males = document.querySelectorAll('input[name="selected_males[]"]:checked').length;
    const females = document.querySelectorAll('input[name="selected_females[]"]:checked').length;
    const lapereaux = document.querySelectorAll('input[name="selected_lapereaux[]"]:checked').length;
    const total = males + females + lapereaux;
    
    document.getElementById('selectedMalesCount').textContent = males;
    document.getElementById('selectedFemalesCount').textContent = females;
    document.getElementById('selectedLapereauxCount').textContent = lapereaux;
    document.getElementById('selectedSummary').textContent = total + ' lapin(s) sélectionné(s)';
    
    // Check quantity match
    const quantityInput = document.getElementById('totalQuantity');
    const quantity = parseInt(quantityInput.value) || 0;
    const warningDiv = document.getElementById('quantityMismatchWarning');
    const validationDiv = document.getElementById('quantityValidation');
    
    if (quantity > 0 && total !== quantity) {
        warningDiv.style.display = 'block';
        validationDiv.style.display = 'block';
        validationDiv.className = 'validation-message error';
        validationDiv.innerHTML = '<i class="bi bi-x-circle-fill"></i><span>Vous avez sélectionné ' + total + ' lapin(s) mais la quantité est de ' + quantity + '</span>';
        document.getElementById('submitBtn').disabled = true;
    } else if (quantity > 0 && total === quantity) {
        warningDiv.style.display = 'none';
        validationDiv.style.display = 'none';
        document.getElementById('submitBtn').disabled = false;
    } else {
        warningDiv.style.display = 'none';
        validationDiv.style.display = 'none';
        document.getElementById('submitBtn').disabled = false;
    }
    
    // Update total amount
    const unitPrice = parseFloat(document.getElementById('unitPrice').value) || 0;
    const totalAmount = total * unitPrice;
    document.getElementById('totalAmountDisplay').value = totalAmount.toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' FCFA';
}

// Add event listeners
document.querySelectorAll('.rabbit-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedSummary);
});

document.getElementById('unitPrice').addEventListener('input', updateSelectedSummary);

document.getElementById('totalQuantity').addEventListener('input', updateSelectedSummary);

// Initialize counts
filterRabbits('males', '');
filterRabbits('females', '');
filterRabbits('lapereaux', '');

// Form submission validation
document.getElementById('saleForm').addEventListener('submit', function(e) {
    const quantity = parseInt(document.getElementById('totalQuantity').value) || 0;
    const total = document.querySelectorAll('input[name="selected_males[]"]:checked').length +
                  document.querySelectorAll('input[name="selected_females[]"]:checked').length +
                  document.querySelectorAll('input[name="selected_lapereaux[]"]:checked').length;
    
    if (quantity === 0) {
        e.preventDefault();
        alert('Veuillez spécifier une quantité de lapins à vendre.');
        return;
    }
    
    if (total === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un lapin à vendre.');
        return;
    }
    
    if (total !== quantity) {
        e.preventDefault();
        alert('Le nombre de lapins sélectionnés (' + total + ') ne correspond pas à la quantité définie (' + quantity + ').');
        return;
    }
});
</script>
@endpush

<style>
.tabs-container {
    display: flex;
    gap: 4px;
    border-bottom: 1px solid var(--surface-border);
}

.tab-btn {
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
}

.tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    background: var(--primary-subtle);
}

.tab-content {
    display: none;
    padding: 16px 0;
}

.tab-content.active {
    display: block;
}

.rabbit-card:hover {
    border-color: var(--primary);
    background: var(--primary-subtle);
}

.rabbit-checkbox {
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
    cursor: pointer;
}

.validation-message {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    padding: 8px 12px;
    border-radius: var(--radius);
}

.validation-message.error {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-red);
    border: 1px solid rgba(239, 68, 68, 0.2);
}
</style>
@endsection