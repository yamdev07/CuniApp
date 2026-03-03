{{-- resources/views/sales/show.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Détails Vente - CuniApp Élevage')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-receipt"></i> Détails de la Vente #{{ $sale->id }}
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <a href="{{ route('sales.index') }}">Ventes</a>
            <span>/</span>
            <span>#{{ $sale->id }}</span>
        </div>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('sales.edit', $sale) }}" class="btn-cuni primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="{{ route('sales.index') }}" class="btn-cuni secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-info-circle"></i> Informations Principales</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="form-group">
                        <label class="form-label">Date de vente</label>
                        <p class="fw-semibold">{{ $sale->date_sale->format('d/m/Y') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type de produit</label>
                        <p>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ ucfirst($sale->type) }}
                            </span>
                            @if($sale->category)
                                <small class="text-muted">({{ $sale->category }})</small>
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantité</label>
                        <p class="fw-semibold">{{ $sale->quantity }}</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix unitaire</label>
                        <p>{{ number_format($sale->unit_price, 2, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant total</label>
                        <p class="fw-bold" style="color: var(--primary);">
                            {{ number_format($sale->total_amount, 2, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($sale->notes)
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-sticky-note"></i> Notes</h3>
            </div>
            <div class="card-body">
                <p class="text-gray-600">{{ nl2br(e($sale->notes)) }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar: Buyer & Payment -->
    <div>
        <!-- Buyer Info -->
        <div class="cuni-card">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-person"></i> Acheteur</h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Nom</label>
                        <p class="font-semibold">{{ $sale->buyer_name }}</p>
                    </div>
                    @if($sale->buyer_contact)
                    <div>
                        <label class="text-sm text-gray-500">Contact</label>
                        <p class="font-semibold">{{ $sale->buyer_contact }}</p>
                    </div>
                    @endif
                    @if($sale->buyer_address)
                    <div>
                        <label class="text-sm text-gray-500">Adresse</label>
                        <p class="font-semibold">{{ nl2br(e($sale->buyer_address)) }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-credit-card"></i> Paiement</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-500">Statut</label>
                        <p>
                            @if($sale->payment_status === 'paid')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                    <i class="bi bi-check-circle"></i> Payé
                                </span>
                            @elseif($sale->payment_status === 'partial')
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                                    <i class="bi bi-hourglass-split"></i> Partiel
                                </span>
                            @else
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                                    <i class="bi bi-clock"></i> En attente
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Montant versé</label>
                        <p class="font-semibold">{{ number_format($sale->amount_paid, 2, ',', ' ') }} FCFA</p>
                    </div>
                    @if($sale->balance > 0)
                    <div>
                        <label class="text-sm text-gray-500">Solde restant</label>
                        <p class="font-semibold" style="color: var(--accent-orange);">
                            {{ number_format($sale->balance, 2, ',', ' ') }} FCFA
                        </p>
                    </div>
                    @endif
                </div>

                @if($sale->payment_status !== 'paid')
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
                    <form action="{{ route('sales.mark-paid', $sale) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-cuni primary" style="width: 100%;"
                                onclick="return confirm('Marquer cette vente comme payée ?')">
                            <i class="bi bi-cash-coin"></i> Marquer comme payé
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Metadata -->
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title"><i class="bi bi-info-circle"></i> Métadonnées</h3>
            </div>
            <div class="card-body">
                <div class="space-y-2" style="font-size: 13px;">
                    <div>
                        <span class="text-gray-500">Créée par:</span>
                        <span class="fw-semibold">{{ $sale->user->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Créée le:</span>
                        <span class="fw-semibold">{{ $sale->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dernière mise à jour:</span>
                        <span class="fw-semibold">{{ $sale->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection