{{-- resources/views/subscription/status.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Statut de l\'Abonnement - CuniApp Élevage')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-pie-chart"></i> Mon Abonnement
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Abonnement</span>
            </div>
        </div>
    </div>

    @if ($subscription && $subscription->isActive())
        <div class="cuni-card mb-6"
            style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white;">
            <div class="card-body" style="padding: 32px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Plan Actuel</div>
                        <div style="font-size: 24px; font-weight: 700;">{{ $subscription->plan->name }}</div>
                    </div>
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Statut</div>
                        <div style="font-size: 24px; font-weight: 700;">
                            <i class="bi bi-check-circle"></i> Actif
                        </div>
                    </div>
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Jours Restants</div>
                        <div style="font-size: 24px; font-weight: 700;">{{ $subscription->days_remaining }} jours</div>
                    </div>
                    <div>
                        <div style="font-size: 13px; opacity: 0.9; margin-bottom: 8px;">Date d'Expiration</div>
                        <div style="font-size: 24px; font-weight: 700;">{{ $subscription->end_date->format('d/m/Y') }}</div>
                    </div>
                </div>

                {{-- ✅ FIXED RENEWAL BUTTON --}}
                <div style="margin-top: 32px; display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="button" class="btn-cuni" style="background: white; color: var(--primary); border: none;"
                        onclick="showRenewalModal()">
                        <i class="bi bi-arrow-repeat"></i> Renouveler
                    </button>

                    {{-- User CANNOT cancel - only admin can --}}
                    @if (auth()->user()->role === 'admin')
                        <button type="button" class="btn-cuni"
                            style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);"
                            onclick="showCancelModal()">
                            <i class="bi bi-x-circle"></i> Annuler l'abonnement
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- ✅ RENEWAL MODAL --}}
        <div id="renewalModal"
            style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
            <div
                style="background: var(--surface); border-radius: var(--radius-lg); max-width: 500px; width: 90%; padding: 32px;">
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">
                    <i class="bi bi-arrow-repeat" style="color: var(--primary);"></i> Renouveler l'Abonnement
                </h3>
                <form action="{{ route('subscription.renew') }}" method="POST">
                    @csrf
                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Méthode de
                            paiement</label>
                        <select name="payment_method" class="form-select" required onchange="togglePhoneNumber(this.value)">
                            <option value="momo">MTN MoMo</option>
                            <option value="celtis">Celtis Cash</option>
                            <option value="moov">Moov Pay</option>
                            @if (auth()->user()->role === 'admin')
                                <option value="manual">Manuel (Admin)</option>
                            @endif
                        </select>
                    </div>

                    <div style="margin-bottom: 16px; display: none;" id="phoneNumberGroup">
                        <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 8px;">Numéro de
                            téléphone</label>
                        <input type="tel" name="phone_number" class="form-control" placeholder="+229 01 XX XX XX XX">
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                        <button type="button" class="btn-cuni secondary"
                            onclick="document.getElementById('renewalModal').style.display='none'">
                            Annuler
                        </button>
                        <button type="submit" class="btn-cuni primary">
                            <i class="bi bi-credit-card"></i> Payer {{ number_format($subscription->price, 0, ',', ' ') }}
                            FCFA
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
            <script>
                function showRenewalModal() {
                    document.getElementById('renewalModal').style.display = 'flex';
                }

                function togglePhoneNumber(method) {
                    const phoneGroup = document.getElementById('phoneNumberGroup');
                    if (['momo', 'celtis', 'moov'].includes(method)) {
                        phoneGroup.style.display = 'block';
                        phoneGroup.querySelector('input').required = true;
                    } else {
                        phoneGroup.style.display = 'none';
                        phoneGroup.querySelector('input').required = false;
                    }
                }

                function showCancelModal() {
                    document.getElementById('cancelModal').style.display = 'flex';
                }

                // Close modals on outside click
                document.querySelectorAll('[id$="Modal"]').forEach(modal => {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            this.style.display = 'none';
                        }
                    });
                });
            </script>
        @endpush
    @endif

    {{-- All Subscriptions History --}}
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-list-ul"></i> Historique des Abonnements
            </h3>
        </div>
        <div class="card-body">
            @if ($allSubscriptions->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table" style="width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--surface-border);">
                                <th style="padding: 12px; text-align: left;">Plan</th>
                                <th style="padding: 12px; text-align: left;">Prix</th>
                                <th style="padding: 12px; text-align: left;">Début</th>
                                <th style="padding: 12px; text-align: left;">Fin</th>
                                <th style="padding: 12px; text-align: left;">Statut</th>
                                <th style="padding: 12px; text-align: left;">Paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allSubscriptions as $sub)
                                <tr style="border-bottom: 1px solid var(--surface-border);">
                                    <td style="padding: 12px;">{{ $sub->plan->name }}</td>
                                    <td style="padding: 12px; font-weight: 600;">
                                        {{ number_format($sub->price, 0, ',', ' ') }} FCFA</td>
                                    <td style="padding: 12px;">{{ $sub->start_date->format('d/m/Y') }}</td>
                                    <td style="padding: 12px;">{{ $sub->end_date->format('d/m/Y') }}</td>
                                    <td style="padding: 12px;">
                                        @if ($sub->status === 'active')
                                            <span class="badge"
                                                style="background: rgba(16, 185, 129, 0.1); color: var(--accent-green);">Actif</span>
                                        @elseif($sub->status === 'expired')
                                            <span class="badge"
                                                style="background: rgba(107, 114, 128, 0.1); color: var(--gray-500);">Expiré</span>
                                        @elseif($sub->status === 'cancelled')
                                            <span class="badge"
                                                style="background: rgba(239, 68, 68, 0.1); color: var(--accent-red);">Annulé</span>
                                        @else
                                            <span class="badge"
                                                style="background: rgba(245, 158, 11, 0.1); color: var(--accent-orange);">En
                                                attente</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px;">{{ strtoupper($sub->payment_method) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: var(--text-tertiary);">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
                    <p>Aucun abonnement trouvé</p>
                    <a href="{{ route('subscription.plans') }}" class="btn-cuni primary" style="margin-top: 16px;">
                        <i class="bi bi-plus-lg"></i> Souscrire un abonnement
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Add this section after payment history -->
    <div class="cuni-card" style="margin-top: 24px;">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-receipt"></i> Factures
            </h3>
            <a href="{{ route('invoices.index') }}" class="btn-cuni sm secondary">
                Voir tout <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="card-body">
            @php
                $recentInvoices = \App\Models\Invoice::where('user_id', auth()->id())
                    ->orderBy('invoice_date', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            @if ($recentInvoices->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table" style="width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--surface-border);">
                                <th style="padding: 12px; text-align: left;">N°</th>
                                <th style="padding: 12px; text-align: left;">Date</th>
                                <th style="padding: 12px; text-align: left;">Montant</th>
                                <th style="padding: 12px; text-align: left;">Statut</th>
                                <th style="padding: 12px; text-align: left;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentInvoices as $invoice)
                                <tr style="border-bottom: 1px solid var(--surface-border);">
                                    <td style="padding: 12px;">{{ $invoice->invoice_number }}</td>
                                    <td style="padding: 12px;">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                    <td style="padding: 12px; font-weight: 600;">
                                        {{ number_format($invoice->total_amount, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td style="padding: 12px;">
                                        @if ($invoice->status === 'paid')
                                            <span style="color: var(--accent-green);">
                                                <i class="bi bi-check-circle"></i> Payée
                                            </span>
                                        @else
                                            <span style="color: var(--accent-orange);">
                                                <i class="bi bi-clock"></i> {{ ucfirst($invoice->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px;">
                                        <a href="{{ route('invoices.download', $invoice) }}" class="btn-cuni sm primary">
                                            <i class="bi bi-download"></i> PDF
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: var(--text-tertiary);">
                    <i class="bi bi-file-earmark" style="font-size: 48px; opacity: 0.5; margin-bottom: 16px;"></i>
                    <p>Aucune facture disponible</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Cancel Modal --}}
    @if ($subscription)
        <div id="cancelModal"
            style=" display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; ">
            <div
                style=" background: var(--surface); border-radius: var(--radius-lg); max-width: 500px; width: 90%; padding: 32px; ">
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">
                    <i class="bi bi-exclamation-triangle" style="color: var(--accent-orange);"></i> Annuler l'abonnement
                </h3>
                <p style="color: var(--text-secondary); margin-bottom: 24px;">
                    Êtes-vous sûr de vouloir annuler votre abonnement ? Vous n'aurez plus accès !
                </p>
                <form action="{{ route('subscription.cancel') }}" method="POST">
                    @csrf
                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                    <textarea name="cancellation_reason" class="form-control" rows="3"
                        placeholder="Raison de l'annulation (optionnel)"></textarea>
                    <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                        <button type="button" class="btn-cuni secondary"
                            onclick="document.getElementById('cancelModal').style.display='none'">
                            Retour
                        </button>
                        <button type="submit" class="btn-cuni danger">
                            <i class="bi bi-trash"></i> Confirmer l'annulation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            function showCancelModal() {
                document.getElementById('cancelModal').style.display = 'flex';
            }
        </script>
    @endpush
@endsection
