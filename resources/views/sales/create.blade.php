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
        <a href="{{ route('sales.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
            @csrf
            <div class="settings-grid">
                <!-- Section: Produit -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-box-seam"></i> Produit vendu
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-select" required id="saleType">
                            <option value="lapereau" selected>Lapereau(x)</option>
                            <option value="male">Mâle</option>
                            <option value="female">Femelle</option>
                            <option value="groupe">Groupe / Lot</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catégorie / Âge</label>
                        <input type="text" name="category" class="form-control" placeholder="Ex: 5-8 semaines, reproducteur">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Optionnel - précisez l'âge ou la catégorie
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantité *</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix unitaire (€) *</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="unit_price" class="form-control" required min="0">
                            <span class="input-group-text">€</span>
                        </div>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-currency-euro"></i> Prix par animal ou par lot
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant total</label>
                        <div class="input-group">
                            <input type="text" id="totalAmount" class="form-control" readonly style="background: var(--surface-alt); font-weight: 600;">
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                </div>

                <!-- Section: Acheteur -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-person"></i> Informations acheteur
                    </h4>
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
                </div>

                <!-- Section: Paiement -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-credit-card"></i> Paiement
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Date de vente *</label>
                        <input type="date" name="date_sale" class="form-control" value="{{ old('date_sale', date('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Statut du paiement *</label>
                        <select name="payment_status" class="form-select" required id="paymentStatus">
                            <option value="paid">Payé intégralement</option>
                            <option value="partial" selected>Paiement partiel</option>
                            <option value="pending">En attente</option>
                        </select>
                    </div>
                    <div class="form-group" id="partialPaymentGroup">
                        <label class="form-label">Montant versé (€)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="amount_paid" class="form-control" value="0" min="0">
                            <span class="input-group-text">€</span>
                        </div>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Laissez à 0 pour "En attente"
                        </small>
                    </div>
                </div>

                <!-- Section: Notes -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-sticky-note"></i> Notes
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Remarques</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Détails sur la vente, conditions particulières, etc.
                        </small>
                    </div>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <button type="submit" class="btn-cuni primary">
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
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.querySelector('input[name="quantity"]');
    const priceInput = document.querySelector('input[name="unit_price"]');
    const totalAmountDisplay = document.getElementById('totalAmount');
    const paymentStatus = document.getElementById('paymentStatus');
    const partialPaymentGroup = document.getElementById('partialPaymentGroup');
    const amountPaidInput = document.querySelector('input[name="amount_paid"]');

    // Toggle partial payment field
    paymentStatus.addEventListener('change', function() {
        if (this.value === 'partial') {
            partialPaymentGroup.style.display = 'block';
            amountPaidInput.required = true;
        } else {
            partialPaymentGroup.style.display = 'none';
            amountPaidInput.required = false;
            if (this.value === 'paid') {
                amountPaidInput.value = totalAmountDisplay.textContent.replace(' €', '').replace(/\s/g, '');
            } else {
                amountPaidInput.value = '0';
            }
        }
    });

    // Initialize based on default selection
    if (paymentStatus.value === 'partial') {
        partialPaymentGroup.style.display = 'block';
    }

    // Calculate total amount
    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        
        totalAmountDisplay.value = total.toFixed(2).replace('.', ',') + ' €';
        
        // Update amount paid if status is "paid"
        if (paymentStatus.value === 'paid') {
            amountPaidInput.value = total.toFixed(2);
        }
    }

    quantityInput.addEventListener('input', calculateTotal);
    priceInput.addEventListener('input', calculateTotal);
    
    // Initial calculation
    calculateTotal();
});
</script>
@endpush

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
.input-group {
    display: flex;
    gap: 0;
}
.input-group .form-control {
    border-radius: var(--radius) 0 0 var(--radius);
    border-right: none;
}
.input-group .input-group-text {
    background: var(--gray-100);
    border: 1px solid var(--gray-300);
    border-radius: 0 var(--radius) var(--radius) 0;
    padding: 0 12px;
    font-weight: 500;
}
#partialPaymentGroup {
    display: none;
}
</style>
@endsection