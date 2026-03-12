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
                        <i class="bi bi-info-circle"></i> Méthode sélectionnée
                    </h4>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 40px; height: 40px; border-radius: var(--radius); 
                background: var(--surface); display: flex; align-items: center; 
                justify-content: center;">
                            {{-- FedaPay Logo or Generic Payment Icon --}}
                            <i class="bi bi-credit-card-2-front" style="color: var(--primary); font-size: 20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-primary);">
                                Paiement Mobile Money
                            </div>
                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                MTN MoMo • Moov Pay • Celtis Cash via FedaPay
                            </div>
                        </div>
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
                <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_id }}">
                    <input type="hidden" name="payment_method" value="{{ $provider }}">

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label class="form-label">Numéro de téléphone *</label>
                        <div class="form-input-wrapper" style="position: relative;">
                            <input type="tel" name="phone_number" class="form-control" placeholder="+229 01 XX XX XX XX"
                                value="{{ old('phone_number', $transaction->phone_number) }}" required
                                pattern="^(\+229)?[0-9]{8,12}$"
                                style="padding-left: 44px; font-family: 'JetBrains Mono', monospace;">
                            <i class="bi bi-phone"
                                style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--gray-400); font-size: 16px;"></i>
                        </div>
                        <small style="color: var(--text-tertiary); font-size: 12px; margin-top: 6px; display: block;">
                            <i class="bi bi-info-circle"></i> Format international recommandé (ex: +2290152415241)
                        </small>
                        @error('phone_number')
                            <div class="validation-message error" style="margin-top: 8px;">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
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
                            <input type="checkbox" name="confirm_payment" required
                                style="width: 16px; height: 16px; accent-color: var(--primary); margin-top: 2px;">
                            <span style="font-size: 13px; color: var(--text-secondary);">
                                Je confirme vouloir payer <strong>{{ number_format($transaction->amount, 0, ',', ' ') }}
                                    FCFA</strong> via {{ ucfirst($provider) }}
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div style="margin-top: 32px; display: flex; gap: 12px;">
                        <button type="submit" class="btn-cuni primary" style="flex: 1;" id="submitBtn">
                            <i class="bi bi-lock"></i>
                            <span>Payer {{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('paymentForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<i class="bi bi-hourglass-split"></i> <span>Traitement en cours...</span>';

                // Optional: Add client-side phone validation
                const phoneInput = form.querySelector('input[name="phone_number"]');
                const phoneRegex = /^(\+229)?[0-9]{8,12}$/;

                if (!phoneRegex.test(phoneInput.value.replace(/\s/g, ''))) {
                    e.preventDefault();
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-lock"></i> <span>Payer</span>';
                    alert('Veuillez entrer un numéro de téléphone valide au format Bénin (+229XXXXXXXX).');
                    phoneInput.focus();
                }
            });

            // Format phone number as user types
            const phoneInput = form.querySelector('input[name="phone_number"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    // Remove all non-digit characters except +
                    let value = this.value.replace(/[^\d+]/g, '');

                    // Auto-add +229 prefix if starting with 01, 02, etc.
                    if (value.startsWith('0') && value.length === 1) {
                        value = '+229' + value.substring(1);
                    }

                    this.value = value;
                });
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
        }

        .form-input-wrapper input {
            padding-left: 44px !important;
        }

        .validation-message {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 8px 12px;
            border-radius: var(--radius);
            background: rgba(239, 68, 68, 0.1);
            color: var(--accent-red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .validation-message i {
            font-size: 14px;
            flex-shrink: 0;
        }

        .btn-cuni.primary.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-cuni.primary.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
