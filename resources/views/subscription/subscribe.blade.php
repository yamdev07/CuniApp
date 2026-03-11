{{-- resources/views/subscription/subscribe.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Confirmer l\'Abonnement - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-credit-card"></i> Confirmer votre Abonnement
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('subscription.plans') }}">Abonnements</a>
            <span>/</span>
            <span>Confirmation</span>
        </div>
    </div>
</div>

@if (session('error'))
<div class="alert-cuni error">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>{{ session('error') }}</div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Plan Summary -->
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-receipt"></i> Résumé de l'Abonnement
            </h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600">Plan sélectionné</span>
                    <span class="font-semibold">{{ $plan->name }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600">Durée</span>
                    <span class="font-semibold">{{ $plan->duration_months }} mois</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600">Prix</span>
                    <span class="font-bold text-primary text-lg">
                        {{ number_format($plan->price, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600">Prix/mois équivalent</span>
                    <span class="font-semibold">
                        {{ number_format($plan->price / $plan->duration_months, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Date de début</span>
                    <span class="font-semibold">{{ now()->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Date d'expiration</span>
                    <span class="font-semibold">
                        {{ now()->addMonths($plan->duration_months)->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <!-- Features List -->
            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">
                    <i class="bi bi-check-circle-fill" style="color: var(--accent-green);"></i>
                    Fonctionnalités incluses
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach (is_array($plan->features) ? $plan->features : ['Accès complet', 'Support prioritaire', 'Sauvegarde auto'] as $feature)
                    <li style="display: flex; align-items: center; gap: 8px; padding: 6px 0; font-size: 13px; color: var(--text-secondary);">
                        <i class="bi bi-check-lg" style="color: var(--accent-green); font-size: 14px;"></i>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-wallet2"></i> Méthode de Paiement
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('subscription.purchase') }}" method="POST" id="subscriptionForm">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                <!-- Payment Method Selection -->
                <div class="form-group">
                    <label class="form-label">Choisir un moyen de paiement *</label>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                        <label class="payment-option" style="
                            display: flex; align-items: center; gap: 10px;
                            padding: 14px; border: 2px solid var(--surface-border);
                            border-radius: var(--radius); cursor: pointer;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--surface-border)'">
                            <input type="radio" name="payment_method" value="momo" required style="accent-color: var(--primary);">
                            <div>
                                <div style="font-weight: 600; font-size: 14px;">MTN MoMo</div>
                                <div style="font-size: 11px; color: var(--text-tertiary);">Mobile Money</div>
                            </div>
                        </label>
                        <label class="payment-option" style="
                            display: flex; align-items: center; gap: 10px;
                            padding: 14px; border: 2px solid var(--surface-border);
                            border-radius: var(--radius); cursor: pointer;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--surface-border)'">
                            <input type="radio" name="payment_method" value="celtis" style="accent-color: var(--primary);">
                            <div>
                                <div style="font-weight: 600; font-size: 14px;">Celtis Cash</div>
                                <div style="font-size: 11px; color: var(--text-tertiary);">Mobile Money</div>
                            </div>
                        </label>
                        <label class="payment-option" style="
                            display: flex; align-items: center; gap: 10px;
                            padding: 14px; border: 2px solid var(--surface-border);
                            border-radius: var(--radius); cursor: pointer;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--surface-border)'">
                            <input type="radio" name="payment_method" value="moov" style="accent-color: var(--primary);">
                            <div>
                                <div style="font-weight: 600; font-size: 14px;">Moov Pay</div>
                                <div style="font-size: 11px; color: var(--text-tertiary);">Mobile Money</div>
                            </div>
                        </label>
                        @if(auth()->check() && auth()->user()->isAdmin())
                        <label class="payment-option" style="
                            display: flex; align-items: center; gap: 10px;
                            padding: 14px; border: 2px solid var(--surface-border);
                            border-radius: var(--radius); cursor: pointer;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--surface-border)'">
                            <input type="radio" name="payment_method" value="manual" style="accent-color: var(--primary);">
                            <div>
                                <div style="font-weight: 600; font-size: 14px;">Manuel</div>
                                <div style="font-size: 11px; color: var(--text-tertiary);">Admin uniquement</div>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>

                <!-- Phone Number (for mobile money) -->
                <div class="form-group" id="phoneNumberGroup" style="display: none;">
                    <label class="form-label">Numéro de téléphone *</label>
                    <input type="tel" name="phone_number" class="form-control" 
                           placeholder="Ex: +229 01 XX XX XX XX" 
                           pattern="^(\+229)?[0-9]{8,12}$"
                           style="font-family: 'JetBrains Mono', monospace;">
                    <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                        <i class="bi bi-info-circle"></i> Format international recommandé
                    </small>
                </div>

                <!-- Auto-renew Option -->
                <div class="form-group" style="margin-top: 24px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="auto_renew" value="1" style="width: 18px; height: 18px; accent-color: var(--primary); margin-top: 2px;">
                        <span style="font-size: 13px; color: var(--text-secondary);">
                            <strong>Activer le renouvellement automatique</strong><br>
                            Votre abonnement sera renouvelé automatiquement à l'expiration au même tarif.
                        </span>
                    </label>
                </div>

                <!-- Terms Acceptance -->
                <div class="form-group" style="margin-top: 16px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="terms" required style="width: 18px; height: 18px; accent-color: var(--primary); margin-top: 2px;">
                        <span style="font-size: 13px; color: var(--text-secondary);">
                            J'accepte les <a href="{{ route('terms') }}" target="_blank" style="color: var(--primary);">Conditions d'utilisation</a> 
                            et la <a href="{{ route('privacy') }}" target="_blank" style="color: var(--primary);">Politique de confidentialité</a>
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div style="margin-top: 32px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-cuni primary" style="flex: 1;" id="submitBtn">
                        <i class="bi bi-lock"></i> Payer {{ number_format($plan->price, 0, ',', ' ') }} FCFA
                    </button>
                    <a href="{{ route('subscription.plans') }}" class="btn-cuni secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </div>
            </form>

            <!-- Security Notice -->
            <div style="margin-top: 24px; padding: 16px; background: rgba(59, 130, 246, 0.1); border-radius: var(--radius); border-left: 4px solid var(--primary);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-shield-check" style="color: var(--primary); font-size: 18px;"></i>
                    <div style="font-size: 13px; color: var(--text-secondary);">
                        <strong>Paiement sécurisé</strong><br>
                        Vos données de paiement sont chiffrées et traitées par des fournisseurs certifiés.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const phoneNumberGroup = document.getElementById('phoneNumberGroup');
    const submitBtn = document.getElementById('submitBtn');

    // Toggle phone number field based on payment method
    paymentMethods.forEach(radio => {
        radio.addEventListener('change', function() {
            if (['momo', 'celtis', 'moov'].includes(this.value)) {
                phoneNumberGroup.style.display = 'block';
                phoneNumberGroup.querySelector('input').required = true;
            } else {
                phoneNumberGroup.style.display = 'none';
                phoneNumberGroup.querySelector('input').required = false;
            }
        });
    });

    // Form submission with loading state
    document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) {
            e.preventDefault();
            alert('Veuillez sélectionner un moyen de paiement.');
            return;
        }

        if (['momo', 'celtis', 'moov'].includes(selectedMethod.value)) {
            const phone = document.querySelector('input[name="phone_number"]').value;
            if (!phone || phone.length < 8) {
                e.preventDefault();
                alert('Veuillez entrer un numéro de téléphone valide.');
                return;
            }
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Traitement en cours...';
    });
});
</script>
@endpush

<style>
.payment-option:has(input[type="radio"]:checked) {
    border-color: var(--primary) !important;
    background: var(--primary-subtle) !important;
}
.payment-option:hover {
    border-color: var(--primary) !important;
}
</style>
@endsection