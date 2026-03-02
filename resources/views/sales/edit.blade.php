@extends('layouts.cuniapp')
@section('title', 'Modifier Vente - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-pencil-square"></i> Modifier la Vente
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('sales.index') }}">Ventes</a>
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
        <h3 class="card-title">
            <i class="bi bi-receipt"></i> Informations de la vente
        </h3>
        <a href="{{ route('sales.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="saleForm">
            @csrf
            @method('PUT')
            
            <div class="settings-grid">
                <!-- Section: Produit -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-box-seam"></i> Produit vendu
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-select" required id="saleType">
                            <option value="lapereau" {{ $sale->type === 'lapereau' ? 'selected' : '' }}>Lapereau(x)</option>
                            <option value="male" {{ $sale->type === 'male' ? 'selected' : '' }}>Mâle</option>
                            <option value="female" {{ $sale->type === 'female' ? 'selected' : '' }}>Femelle</option>
                            <option value="groupe" {{ $sale->type === 'groupe' ? 'selected' : '' }}>Groupe / Lot</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catégorie / Âge</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $sale->category) }}" placeholder="Ex: 5-8 semaines, reproducteur">
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Optionnel - précisez l'âge ou la catégorie
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantité *</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $sale->quantity) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix unitaire (F) *</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="unit_price" class="form-control" value="{{ old('unit_price', $sale->unit_price) }}" required min="0">
                            <span class="input-group-text">F</span>
                        </div>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-currency-euro"></i> Prix par animal ou par lot
                        </small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant total</label>
                        <div class="input-group">
                            <input type="text" id="totalAmount" class="form-control" readonly style="background: var(--surface-alt); font-weight: 600;" value="{{ number_format($sale->total_amount, 2, ',', ' ') }} F">
                            <span class="input-group-text">F</span>
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
                        <input type="text" name="buyer_name" class="form-control" value="{{ old('buyer_name', $sale->buyer_name) }}" required placeholder="Nom complet">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contact</label>
                        <input type="text" name="buyer_contact" class="form-control" value="{{ old('buyer_contact', $sale->buyer_contact) }}" placeholder="Téléphone ou email">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <textarea name="buyer_address" class="form-control" rows="2" placeholder="Adresse complète">{{ old('buyer_address', $sale->buyer_address) }}</textarea>
                    </div>
                </div>

                <!-- Section: Paiement -->
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-credit-card"></i> Paiement
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Date de vente *</label>
                        <input type="date" name="date_sale" class="form-control" value="{{ old('date_sale', $sale->date_sale->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Statut du paiement *</label>
                        <select name="payment_status" class="form-select" required id="paymentStatus">
                            <option value="paid" {{ $sale->payment_status === 'paid' ? 'selected' : '' }}>Payé intégralement</option>
                            <option value="partial" {{ $sale->payment_status === 'partial' ? 'selected' : '' }}>Paiement partiel</option>
                            <option value="pending" {{ $sale->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
                        </select>
                    </div>
                    <div class="form-group" id="partialPaymentGroup">
                        <label class="form-label">Montant versé (F)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="amount_paid" class="form-control" value="{{ old('amount_paid', $sale->amount_paid) }}" min="0">
                            <span class="input-group-text">F</span>
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
                        <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires...">{{ old('notes', $sale->notes) }}</textarea>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Détails sur la vente, conditions particulières, etc.
                        </small>
                    </div>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-check-circle"></i> Mettre à jour la vente
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
                amountPaidInput.value = totalAmountDisplay.textContent.replace(' F', '').replace(/\s/g, '');
            } else {
                amountPaidInput.value = '0';
            }
        }
    });

    // Initialize based on current status
    if (paymentStatus.value === 'partial') {
        partialPaymentGroup.style.display = 'block';
    } else {
        partialPaymentGroup.style.display = 'none';
    }

    // Calculate total amount
    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        
        totalAmountDisplay.value = total.toFixed(2).replace('.', ',') + ' F';
        
        // Update amount paid if status is "paid"
        if (paymentStatus.value === 'paid') {
            amountPaidInput.value = total.toFixed(2);
        }
    }

    quantityInput.addEventListener('input', calculateTotal);
    priceInput.addEventListener('input', calculateTotal);
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