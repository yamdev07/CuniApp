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

    <div class="payment-grid">
        <!-- Payment Details -->
        <div class="payment-details-card">
            <div class="cuni-card">
                <div class="card-header-custom">
                    <h3 class="card-title">
                        <i class="bi bi-receipt"></i> Détails de la Transaction
                    </h3>
                </div>
                <div class="card-body">
                    <div class="transaction-details">
                        <div class="detail-row">
                            <span class="detail-label">Transaction ID</span>
                            <span class="detail-value font-mono">{{ $transaction->transaction_id }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Plan</span>
                            <span class="detail-value font-semibold">{{ $subscription->plan->name ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Durée</span>
                            <span class="detail-value font-semibold">{{ $subscription->plan->duration_months ?? 'N/A' }}
                                mois</span>
                        </div>
                        <div class="detail-row highlight">
                            <span class="detail-label">Montant à payer</span>
                            <span class="detail-value font-bold text-primary text-lg">
                                {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Statut</span>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                                <i class="bi bi-clock"></i> En attente
                            </span>
                        </div>
                    </div>

                    <!-- Payment Method Info -->
                    <div class="payment-info-box">
                        <h4 class="info-title">
                            <i class="bi bi-info-circle"></i> Comment ça marche ?
                        </h4>
                        <div class="payment-method-display">
                            <div class="method-icon">
                                <i class="bi bi-credit-card-2-front"></i>
                            </div>
                            <div class="method-info">
                                <div class="method-name">Paiement Mobile Money</div>
                                <div class="method-providers">MTN MoMo • Moov Pay • Celtis Cash via FedaPay</div>
                            </div>
                        </div>
                        <div class="payment-steps">
                            <strong>📱 Étapes :</strong>
                            <ol class="steps-list">
                                <li>Entrez votre numéro de téléphone mobile money</li>
                                <li>Vous recevrez une notification USSD sur votre téléphone</li>
                                <li>Confirmez le paiement avec votre code secret</li>
                                <li>Vous serez automatiquement redirigé vers votre espace après confirmation</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="payment-form-card">
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
                            <div class="form-input-wrapper">
                                <input type="tel" id="phoneNumber" name="phone_number" class="form-control"
                                    placeholder="01 XX XX XX XX ou +229 01 XX XX XX XX"
                                    value="{{ old('phone_number', $transaction->phone_number) }}" required
                                    pattern="^(\+229)?01[0-9]{8}$"
                                    style="padding-left: 44px; font-family: 'JetBrains Mono', monospace;" maxlength="14"
                                    title="Format: 01XXXXXXXX (10 chiffres) ou +22901XXXXXXXX (14 caractères)">
                                <i class="bi bi-phone"></i>
                            </div>
                            <small class="form-text">
                                <i class="bi bi-info-circle"></i> Format Bénin: <strong>01XXXXXXXX</strong> (10 chiffres) ou
                                <strong>+22901XXXXXXXX</strong>
                            </small>
                            <div id="phoneError" class="validation-message error" style="display: none;">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span></span>
                            </div>
                        </div>

                        <!-- Format Preview -->
                        <div id="formatPreview" class="format-preview">
                            <strong style="color: var(--primary);">Format FedaPay:</strong>
                            <span id="fedapayFormat" class="font-mono"></span>
                        </div>

                        <!-- Security Notice -->
                        <div class="security-notice">
                            <div class="security-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="security-text">
                                <strong>Paiement sécurisé</strong><br>
                                Vous recevrez une notification sur votre téléphone pour confirmer la transaction.
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="confirmPayment" name="confirm_payment" required
                                    style="width: 16px; height: 16px; accent-color: var(--primary); margin-top: 2px;">
                                <span class="checkbox-text">
                                    Je confirme vouloir payer
                                    <strong>{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</strong>
                                </span>
                            </label>
                            <div id="termsError" class="validation-message error" style="display: none;">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>Veuillez accepter les conditions de paiement</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-actions">
                            <button type="submit" class="btn-cuni primary btn-large" id="submitBtn">
                                <i class="bi bi-lock"></i>
                                <span class="btn-text">Payer {{ number_format($transaction->amount, 0, ',', ' ') }}
                                    FCFA</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="bi bi-hourglass-split" style="animation: spin 1s linear infinite;"></i>
                                    Traitement en cours...
                                </span>
                            </button>
                            <a href="{{ route('subscription.status') }}" class="btn-cuni secondary btn-large">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>

                    <!-- Help Text -->
                    <div class="help-section">
                        <p class="help-text">
                            <i class="bi bi-question-circle"></i> Besoin d'aide ?
                        </p>
                        <a href="{{ route('contact') }}" class="help-link">
                            Contacter le support
                        </a>
                    </div>
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
                const formatPreview = document.getElementById('formatPreview');
                const fedapayFormat = document.getElementById('fedapayFormat');

                // ============================================
                // ✅ BENIN PHONE NUMBER FORMATTING FOR FEDAPAY
                // ============================================
                phoneInput.addEventListener('input', function() {
                    let value = this.value.replace(/\s/g, '');
                    value = value.replace(/[^\d+]/g, '');

                    if (value.startsWith('+229')) {
                        if (value.length > 14) {
                            value = value.substring(0, 14);
                        }
                    } else if (value.startsWith('229')) {
                        if (value.length > 13) {
                            value = value.substring(0, 13);
                        }
                    } else {
                        if (value.length > 10) {
                            value = value.substring(0, 10);
                        }
                    }

                    if (value.startsWith('229') && !this.value.startsWith('+')) {
                        this.value = '+' + value;
                    } else {
                        this.value = value;
                    }

                    updateFedapayPreview(this.value);
                });

                phoneInput.addEventListener('blur', function() {
                    updateFedapayPreview(this.value);
                });

                function updateFedapayPreview(userInput) {
                    const cleaned = userInput.replace(/\s/g, '').replace('+', '');
                    let last8Digits = '';

                    if (cleaned.startsWith('22901')) {
                        last8Digits = cleaned.slice(-8);
                    } else if (cleaned.startsWith('01')) {
                        last8Digits = cleaned.slice(-8);
                    } else {
                        last8Digits = cleaned.slice(-8);
                    }

                    if (last8Digits.length === 8 && /^\d+$/.test(last8Digits)) {
                        formatPreview.style.display = 'block';
                        fedapayFormat.textContent = '+229' + last8Digits;
                        fedapayFormat.style.color = 'var(--accent-green)';
                    } else {
                        formatPreview.style.display = 'none';
                    }
                }

                // ============================================
                // ✅ VALIDATE PHONE NUMBER FOR BENIN
                // ============================================
                function validatePhone() {
                    const phone = phoneInput.value.replace(/\s/g, '');

                    if (!phone) {
                        phoneError.style.display = 'flex';
                        phoneError.querySelector('span').textContent = 'Numéro de téléphone requis';
                        phoneInput.classList.add('error');
                        return false;
                    }

                    const digits = phone.replace('+', '');
                    const beninRegexLocal = /^01[0-9]{8}$/;
                    const beninRegexIntl = /^22901[0-9]{8}$/;

                    if (!beninRegexLocal.test(digits) && !beninRegexIntl.test(digits)) {
                        phoneError.style.display = 'flex';
                        phoneError.querySelector('span').textContent =
                            'Format invalide. Ex: 0156550912 ou +2290156550912';
                        phoneInput.classList.add('error');
                        return false;
                    }

                    phoneError.style.display = 'none';
                    phoneInput.classList.remove('error');
                    phoneInput.classList.add('success');
                    return true;
                }

                phoneInput.addEventListener('blur', validatePhone);

                // ============================================
                // ✅ TRANSFORM NUMBER FOR FEDAPAY API
                // ============================================
                function transformForFedaPay(userPhone) {
                    const cleaned = userPhone.replace(/\s/g, '').replace('+', '');
                    let last8Digits = cleaned.slice(-8);
                    return '+229' + last8Digits;
                }

                // ============================================
                // ✅ FORM SUBMISSION WITH AJAX
                // ============================================
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    phoneError.style.display = 'none';
                    termsError.style.display = 'none';
                    phoneInput.classList.remove('error');

                    if (!validatePhone()) {
                        phoneInput.focus();
                        return;
                    }

                    if (!confirmCheckbox.checked) {
                        termsError.style.display = 'flex';
                        confirmCheckbox.focus();
                        return;
                    }

                    submitBtn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'inline-flex';

                    const originalPhone = phoneInput.value;
                    const fedapayPhone = transformForFedaPay(originalPhone);

                    const formData = new FormData(form);
                    formData.set('phone_number', fedapayPhone);

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
                                showToast('✅ Paiement initié ! Redirection vers FedaPay...', 'success');
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
                            showToast('❌ ' + (error.message || 'Une erreur est survenue'), 'error');
                            submitBtn.disabled = false;
                            btnText.style.display = 'inline';
                            btnLoading.style.display = 'none';
                            phoneError.style.display = 'flex';
                            phoneError.querySelector('span').textContent = error.message ||
                                'Échec du paiement. Veuillez réessayer.';
                        });
                });

                // ============================================
                // ✅ TOAST NOTIFICATION FUNCTION
                // ============================================
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

                // Initialize preview on load if value exists
                if (phoneInput.value) {
                    updateFedapayPreview(phoneInput.value);
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            /* ============================================
           PAYMENT PAGE LAYOUT - DESKTOP FIRST
           ============================================ */
            .payment-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
                width: 100%;
                max-width: 1400px;
                margin: 0 auto;
            }

            @media (max-width: 1024px) {
                .payment-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .payment-grid {
                    gap: 16px;
                }
            }

            /* Transaction Details */
            .transaction-details {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .detail-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                border-bottom: 1px solid var(--surface-border);
            }

            .detail-row:last-child {
                border-bottom: none;
            }

            .detail-row.highlight {
                background: var(--primary-subtle);
                padding: 16px;
                border-radius: var(--radius);
                border-bottom: none;
            }

            .detail-label {
                color: var(--text-secondary);
                font-size: 14px;
            }

            .detail-value {
                color: var(--text-primary);
                font-size: 14px;
            }

            .font-mono {
                font-family: 'JetBrains Mono', monospace;
            }

            .font-semibold {
                font-weight: 600;
            }

            .font-bold {
                font-weight: 700;
            }

            .text-primary {
                color: var(--primary);
            }

            .text-lg {
                font-size: 1.125rem;
            }

            /* Payment Info Box */
            .payment-info-box {
                margin-top: 24px;
                padding: 20px;
                background: var(--primary-subtle);
                border-radius: var(--radius-lg);
                border: 1px solid var(--surface-border);
            }

            .info-title {
                font-size: 15px;
                font-weight: 600;
                color: var(--primary);
                margin-bottom: 16px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .payment-method-display {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 16px;
            }

            .method-icon {
                width: 48px;
                height: 48px;
                border-radius: var(--radius);
                background: var(--surface);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .method-icon i {
                color: var(--primary);
                font-size: 24px;
            }

            .method-info {
                flex: 1;
            }

            .method-name {
                font-weight: 600;
                color: var(--text-primary);
                font-size: 14px;
            }

            .method-providers {
                font-size: 12px;
                color: var(--text-tertiary);
                margin-top: 2px;
            }

            .payment-steps {
                margin-top: 16px;
                padding: 16px;
                background: var(--surface);
                border-radius: var(--radius);
                font-size: 13px;
                color: var(--text-secondary);
            }

            .steps-list {
                margin: 12px 0 0 24px;
                padding: 0;
                line-height: 2;
            }

            .steps-list li {
                margin-bottom: 8px;
            }

            /* Form Input Wrapper */
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

            .form-control {
                width: 100%;
                padding: 12px 16px;
                font-size: 14px;
                border: 2px solid var(--gray-200);
                border-radius: var(--radius);
                background: var(--white);
                color: var(--text-primary);
                transition: all 0.3s ease;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 4px var(--primary-subtle);
            }

            .form-control.error {
                border-color: var(--accent-red) !important;
                background-color: rgba(239, 68, 68, 0.05) !important;
            }

            .form-control.success {
                border-color: var(--accent-green) !important;
                background-color: rgba(16, 185, 129, 0.05) !important;
            }

            .form-text {
                color: var(--text-tertiary);
                font-size: 12px;
                margin-top: 8px;
                display: block;
            }

            /* Format Preview */
            .format-preview {
                margin-top: 12px;
                padding: 12px 16px;
                background: var(--surface-alt);
                border-radius: var(--radius);
                font-size: 13px;
                display: none;
                animation: fadeIn 0.3s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Security Notice */
            .security-notice {
                margin: 24px 0;
                padding: 16px;
                background: rgba(59, 130, 246, 0.1);
                border-radius: var(--radius);
                border-left: 4px solid var(--primary);
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .security-icon {
                flex-shrink: 0;
            }

            .security-icon i {
                color: var(--primary);
                font-size: 20px;
            }

            .security-text {
                font-size: 13px;
                color: var(--text-secondary);
            }

            /* Checkbox Label */
            .checkbox-label {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                cursor: pointer;
            }

            .checkbox-text {
                font-size: 13px;
                color: var(--text-secondary);
                line-height: 1.5;
            }

            /* Validation Message */
            .validation-message {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 12px;
                padding: 8px 12px;
                border-radius: var(--radius);
                margin-top: 8px;
            }

            .validation-message.error {
                background: rgba(239, 68, 68, 0.1);
                color: var(--accent-red);
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            /* Form Actions */
            .form-actions {
                margin-top: 32px;
                display: flex;
                gap: 12px;
                flex-wrap: wrap;
            }

            .btn-large {
                flex: 1;
                min-width: 200px;
                padding: 14px 24px;
                font-size: 15px;
            }

            /* Help Section */
            .help-section {
                margin-top: 24px;
                padding-top: 24px;
                border-top: 1px solid var(--surface-border);
                text-align: center;
            }

            .help-text {
                font-size: 13px;
                color: var(--text-tertiary);
                margin-bottom: 12px;
            }

            .help-link {
                color: var(--primary);
                font-size: 13px;
                font-weight: 500;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .help-link:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }

            /* Button Loading State */
            .btn-cuni.primary:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                pointer-events: none;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .form-actions {
                    flex-direction: column;
                }

                .btn-large {
                    width: 100%;
                    min-width: auto;
                }

                .payment-info-box {
                    padding: 16px;
                }

                .detail-row {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 8px;
                }
            }

            @media (max-width: 480px) {
                .payment-grid {
                    gap: 12px;
                }

                .card-body {
                    padding: 16px !important;
                }

                .method-icon {
                    width: 40px;
                    height: 40px;
                }

                .steps-list {
                    font-size: 12px;
                }
            }

            /* Dark Mode Support */
            .theme-dark .form-control {
                background-color: var(--surface-alt);
                border-color: var(--surface-border);
                color: var(--text-primary);
            }

            .theme-dark .form-control:focus {
                background-color: var(--surface-elevated);
                border-color: var(--primary);
            }

            .theme-dark .payment-info-box {
                background: rgba(77, 166, 255, 0.08);
            }

            .theme-dark .security-notice {
                background: rgba(77, 166, 255, 0.08);
            }
        </style>
    @endpush
@endsection
