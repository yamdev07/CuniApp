{{-- resources/views/payment/initiate.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Paiement - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-credit-card"></i> Finaliser le Paiement
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('subscription.status') }}">Abonnement</a>
                <span>/</span>
                <span>Paiement</span>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="alert-cuni error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert-cuni success">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Details -->
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-receipt"></i> Détails de la Transaction
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Transaction ID</span>
                        <span class="font-mono text-sm">{{ $transaction->transaction_id }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Plan</span>
                        <span class="font-semibold">{{ $subscription->plan->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Durée</span>
                        <span class="font-semibold">{{ $subscription->plan->duration_months ?? 'N/A' }} mois</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Montant à payer</span>
                        <span class="font-bold text-primary text-lg">
                            {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Statut</span>
                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                            <i class="bi bi-clock"></i> En attente
                        </span>
                    </div>
                </div>

                <!-- Payment Method Info -->
                <div
                    style="margin-top: 24px; padding: 16px; background: var(--primary-subtle); border-radius: var(--radius-lg);">
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px; color: var(--primary);">
                        <i class="bi bi-info-circle"></i> Comment ça marche ?
                    </h4>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 40px; height: 40px; border-radius: var(--radius); background: var(--surface); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-credit-card-2-front" style="color: var(--primary); font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">Paiement Mobile Money</div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">MTN MoMo • Moov Pay • Celtis Cash via
                                FedaPay</div>
                        </div>
                    </div>
                    <div
                        style="margin-top: 16px; padding: 12px; background: var(--surface); border-radius: var(--radius); font-size: 13px; color: var(--text-secondary);">
                        <strong>📱 Étapes :</strong>
                        <ol style="margin: 8px 0 0 20px; padding: 0; line-height: 1.8;">
                            <li>Entrez votre numéro de téléphone mobile money</li>
                            <li>Vous recevrez une notification USSD sur votre téléphone</li>
                            <li>Confirmez le paiement avec votre code secret</li>
                            <li>Vous serez automatiquement redirigé vers votre espace après confirmation</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-wallet2"></i> Confirmer le Paiement
                </h3>
            </div>
            <div class="card-body">
                <form id="paymentForm" data-transaction-id="{{ $transaction->transaction_id }}">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_id }}">
                    <input type="hidden" name="payment_method" value="{{ $transaction->provider ?? 'momo' }}">

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label class="form-label">Numéro de téléphone *</label>
                        <div class="form-input-wrapper" style="position: relative;">
                            <input type="tel" id="phoneNumber" name="phone_number" class="form-control"
                                placeholder="01 XX XX XX XX" value="{{ old('phone_number', $transaction->phone_number) }}"
                                required pattern="^(\+229)?[0-9]{8,12}$"
                                style="padding-left: 44px; font-family: 'JetBrains Mono', monospace;" maxlength="12">
                            <i class="bi bi-phone"
                                style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: 16px;"></i>
                        </div>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Format: 01XXXXXX ou +22901XXXXXX
                        </small>
                        <div id="phoneError" class="validation-message error" style="display: none; margin-top: 8px;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span></span>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div
                        style="margin: 24px 0; padding: 16px; background: rgba(59, 130, 246, 0.1); border-radius: var(--radius); border-left: 4px solid var(--primary);">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-shield-check" style="color: var(--primary); font-size: 18px;"></i>
                            <div style="font-size: 13px; color: var(--text-secondary);">
                                <strong>Paiement sécurisé</strong><br>
                                Vous recevrez une notification sur votre téléphone pour confirmer la transaction.
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="form-group">
                        <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer;">
                            <input type="checkbox" id="confirmPayment" name="confirm_payment" required
                                style="width: 16px; height: 16px; accent-color: var(--primary); margin-top: 2px;">
                            <span style="font-size: 13px; color: var(--text-secondary);">
                                Je confirme vouloir payer <strong>{{ number_format($transaction->amount, 0, ',', ' ') }}
                                    FCFA</strong>
                            </span>
                        </label>
                        <div id="termsError" class="validation-message error" style="display: none; margin-top: 8px;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>Veuillez accepter les conditions de paiement</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div style="margin-top: 32px; display: flex; gap: 12px;">
                        <button type="submit" class="btn-cuni primary" style="flex: 1;" id="submitBtn">
                            <i class="bi bi-lock"></i>
                            <span class="btn-text">Payer {{ number_format($transaction->amount, 0, ',', ' ') }}
                                FCFA</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="bi bi-hourglass-split" style="animation: spin 1s linear infinite;"></i>
                                Traitement en cours...
                            </span>
                        </button>
                        <a href="{{ route('subscription.status') }}" class="btn-cuni secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                </form>

                <!-- Help Text -->
                <div
                    style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--surface-border); text-align: center;">
                    <p style="font-size: 13px; color: var(--text-tertiary); margin-bottom: 12px;">
                        <i class="bi bi-question-circle"></i> Besoin d'aide ?
                    </p>
                    <a href="{{ route('contact') }}" class="text-primary" style="font-size: 13px; font-weight: 500;">
                        Contacter le support
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('paymentForm');
                const submitBtn = document.getElementById('submitBtn');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnLoading = submitBtn.querySelector('.btn-loading');
                const phoneInput = document.getElementById('phoneNumber');
                const confirmCheckbox = document.getElementById('confirmPayment');
                const phoneError = document.getElementById('phoneError');
                const termsError = document.getElementById('termsError');

                // ✅ Phone Number Auto-Format (+229 prefix)
                phoneInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, ''); // Remove non-digits
                    value = value.replace(/^0+/, ''); // Remove leading zeros

                    // Auto-add 229 if not present
                    if (value.length > 0 && !value.startsWith('229')) {
                        value = '229' + value;
                    }

                    // Limit to 12 digits max (229 + 9 digits)
                    if (value.length > 12) {
                        value = value.substring(0, 12);
                    }

                    // Format for display
                    if (value.startsWith('229')) {
                        this.value = '+' + value;
                    } else {
                        this.value = value;
                    }
                });

                // ✅ Validate Phone Number
                function validatePhone() {
                    const phone = phoneInput.value.replace(/\s/g, '');
                    const phoneRegex = /^(\+229)?[0-9]{8,12}$/;

                    if (!phone || phone.length < 8) {
                        phoneError.style.display = 'flex';
                        phoneError.querySelector('span').textContent = 'Numéro de téléphone invalide';
                        phoneInput.classList.add('error');
                        return false;
                    }

                    if (!phoneRegex.test(phone)) {
                        phoneError.style.display = 'flex';
                        phoneError.querySelector('span').textContent = 'Format invalide. Ex: +22901524152';
                        phoneInput.classList.add('error');
                        return false;
                    }

                    phoneError.style.display = 'none';
                    phoneInput.classList.remove('error');
                    phoneInput.classList.add('success');
                    return true;
                }

                phoneInput.addEventListener('blur', validatePhone);

                // ✅ Form Submission with AJAX
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Reset errors
                    phoneError.style.display = 'none';
                    termsError.style.display = 'none';
                    phoneInput.classList.remove('error');

                    // Validate phone
                    if (!validatePhone()) {
                        phoneInput.focus();
                        return;
                    }

                    // Validate terms
                    if (!confirmCheckbox.checked) {
                        termsError.style.display = 'flex';
                        confirmCheckbox.focus();
                        return;
                    }

                    // Show loading state
                    submitBtn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'inline-flex';

                    // Prepare form data
                    const formData = new FormData(form);

                    // ✅ Send AJAX request
                    fetch('{{ route('payment.process') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success && data.checkout_url) {
                                // ✅ Show redirect message
                                showToast('✅ Paiement initié ! Redirection vers FedaPay...', 'success');

                                // ✅ Redirect to FedaPay after short delay
                                setTimeout(() => {
                                    window.location.href = data.checkout_url;
                                }, 1500);
                            } else {
                                throw {
                                    message: data.message || 'Échec du paiement'
                                };
                            }
                        })
                        .catch(error => {
                            console.error('Payment error:', error);

                            // Show error toast
                            showToast('❌ ' + (error.message || 'Une erreur est survenue'), 'error');

                            // Reset button
                            submitBtn.disabled = false;
                            btnText.style.display = 'inline';
                            btnLoading.style.display = 'none';

                            // Show error in form
                            phoneError.style.display = 'flex';
                            phoneError.querySelector('span').textContent = error.message ||
                                'Échec du paiement. Veuillez réessayer.';
                        });
                });

                // ✅ Toast Notification Function
                function showToast(message, type = 'info') {
                    const toast = document.createElement('div');
                    toast.style.cssText = `
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-left: 4px solid ${type === 'success' ? 'var(--accent-green)' : 'var(--accent-red)'};
            padding: 16px 24px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease;
            max-width: 400px;
        `;

                    const icon = type === 'success' ? 'check-circle-fill' : 'x-circle-fill';
                    const color = type === 'success' ? 'var(--accent-green)' : 'var(--accent-red)';

                    toast.innerHTML = `
            <i class="bi bi-${icon}" style="color: ${color}; font-size: 20px;"></i>
            <span style="color: var(--text-primary); font-size: 14px; font-weight: 500;">${message}</span>
        `;

                    document.body.appendChild(toast);
                    setTimeout(() => {
                        toast.style.animation = 'slideOutRight 0.3s ease';
                        setTimeout(() => toast.remove(), 300);
                    }, 4000);
                }

                // Add animation styles
                if (!document.getElementById('payment-animations')) {
                    const style = document.createElement('style');
                    style.id = 'payment-animations';
                    style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
                    document.head.appendChild(style);
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .form-input-wrapper {
                position: relative;
            }

            .form-input-wrapper i {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--gray-400);
                font-size: 16px;
                pointer-events: none;
                z-index: 2;
            }

            .form-input-wrapper input {
                padding-left: 44px !important;
            }

            .form-control.error {
                border-color: var(--accent-red) !important;
                background-color: rgba(239, 68, 68, 0.05) !important;
            }

            .form-control.success {
                border-color: var(--accent-green) !important;
                background-color: rgba(16, 185, 129, 0.05) !important;
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

            .btn-cuni.primary:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                pointer-events: none;
            }
        </style>
    @endpush
@endsection
