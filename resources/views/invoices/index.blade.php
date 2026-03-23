@extends('layouts.cuniapp')

@section('title', 'Mes Factures - CuniApp Élevage')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-receipt"></i> Mes Factures
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <span>Factures</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Factures</p>
                        <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(59, 130, 246, 0.1)">
                        <i class="bi bi-file-earmark text-blue-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Payées</p>
                        <p class="text-2xl font-bold mt-1" style="color: var(--accent-green)">{{ $stats['paid'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(16, 185, 129, 0.1)">
                        <i class="bi bi-check-circle text-green-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">En Attente</p>
                        <p class="text-2xl font-bold mt-1" style="color: var(--accent-orange)">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(245, 158, 11, 0.1)">
                        <i class="bi bi-clock text-amber-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Montant Total</p>
                        <p class="text-2xl font-bold mt-1" style="color: var(--primary)">
                            {{ number_format($stats['total_amount'], 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(139, 92, 246, 0.1)">
                        <i class="bi bi-currency-euro text-purple-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- resources/views/invoices/index.blade.php - Search Section --}}
    <div class="cuni-card" style="margin-bottom: 20px;">
        <div class="card-body" style="padding: 16px;">
            <form method="GET" action="{{ route('invoices.index') }}">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
                    {{-- Recherche par numéro de facture --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-search" style="margin-right: 4px;"></i> N° Facture
                        </label>
                        <input type="text" name="invoice_number" value="{{ request()->get('invoice_number') }}"
                            class="form-control" placeholder="INV-2026-00001" style="border-radius: var(--radius-lg);">
                    </div>

                    {{-- Filtre par statut --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-filter-circle" style="margin-right: 4px;"></i> Statut
                        </label>
                        <select name="status" class="form-select" style="border-radius: var(--radius-lg);">
                            <option value="">Tous les statuts</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payée</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée
                            </option>
                        </select>
                    </div>

                    {{-- Filtre date début --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-calendar-event" style="margin-right: 4px;"></i> Du
                        </label>
                        <input type="date" name="start_date" value="{{ request()->get('start_date') }}"
                            class="form-control" style="border-radius: var(--radius-lg);">
                    </div>

                    {{-- Filtre date fin --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-calendar-check" style="margin-right: 4px;"></i> Au
                        </label>
                        <input type="date" name="end_date" value="{{ request()->get('end_date') }}" class="form-control"
                            style="border-radius: var(--radius-lg);">
                    </div>

                    {{-- Boutons d'action --}}
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-cuni primary" style="flex: 1; padding: 10px;">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        @if (request()->anyFilled(['invoice_number', 'status', 'start_date', 'end_date']))
                            <a href="{{ route('invoices.index') }}" class="btn-cuni secondary" style="padding: 10px 16px;"
                                title="Réinitialiser les filtres">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Résumé des filtres actifs --}}
                @if (request()->anyFilled(['invoice_number', 'status', 'start_date', 'end_date']))
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--surface-border);">
                        <small style="color: var(--text-tertiary);">
                            Filtres actifs :
                            @if (request('invoice_number'))
                                <span class="badge"
                                    style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    📄 {{ request('invoice_number') }}
                                </span>
                            @endif
                            @if (request('status'))
                                <span class="badge"
                                    style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    🏷️ {{ ucfirst(request('status')) }}
                                </span>
                            @endif
                            @if (request('start_date'))
                                <span class="badge"
                                    style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    📅 Du: {{ request('start_date') }}
                                </span>
                            @endif
                            @if (request('end_date'))
                                <span class="badge"
                                    style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    📅 Au: {{ request('end_date') }}
                                </span>
                            @endif
                            <a href="{{ route('invoices.index') }}"
                                style="color: var(--accent-red); margin-left: 8px; text-decoration: none;">
                                <i class="bi bi-x-circle"></i> Tout effacer
                            </a>
                        </small>
                    </div>
                @endif
            </form>
        </div>
    </div>

  

    <!-- Invoices Table -->
    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-list-ul"></i> Historique des Factures
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold small">N° Facture</th>
                            <th class="text-uppercase text-muted fw-semibold small">Date</th>
                            <th class="text-uppercase text-muted fw-semibold small">Montant</th>
                            <th class="text-uppercase text-muted fw-semibold small">Statut</th>
                            <th class="text-uppercase text-muted fw-semibold small">Type</th>
                            <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr class="border-bottom border-light">
                                <td class="ps-4 fw-semibold text-dark">
                                    {{ $invoice->invoice_number }}
                                </td>
                                <td class="text-muted">
                                    {{ $invoice->invoice_date->format('d/m/Y') }}
                                </td>
                                <td class="fw-bold" style="color: var(--primary)">
                                    {{ number_format($invoice->total_amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td>
                                    @if ($invoice->status === 'paid')
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                            <i class="bi bi-check-circle"></i> Payée
                                        </span>
                                    @elseif($invoice->status === 'pending')
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                                            <i class="bi bi-clock"></i> En attente
                                        </span>
                                    @else
                                        <span class="badge"
                                            style="background: rgba(107, 114, 128, 0.1); color: #6B7280;">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                        {{ ucfirst($invoice->invoice_type) }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn-cuni sm secondary"
                                            title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.download', $invoice) }}" class="btn-cuni sm primary"
                                            title="Télécharger PDF">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="table-empty-state">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-3">Aucune facture trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

                        {{-- ✅ PAGINATION STYLISÉE CuniApp --}}
            @if ($invoices->hasPages())
                <div class="flex flex-col md:flex-row justify-between items-center mt-8 gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                    
                    {{-- Résumé --}}
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Affichage de <strong>{{ $invoices->firstItem() }}</strong> à 
                        <strong>{{ $invoices->lastItem() }}</strong> sur 
                        <strong>{{ $invoices->total() }}</strong> factures
                    </div>

                    <div class="flex gap-2 flex-wrap justify-center">
                        
                        {{-- Bouton Précédent --}}
                        @if ($invoices->onFirstPage())
                            <span class="btn-cuni secondary sm opacity-50 cursor-not-allowed" style="pointer-events: none;">
                                <i class="bi bi-chevron-left"></i> Précédent
                            </span>
                        @else
                            <a href="{{ $invoices->previousPageUrl() }}" class="btn-cuni secondary sm">
                                <i class="bi bi-chevron-left"></i> Précédent
                            </a>
                        @endif

                        {{-- Numéros de pages (Logique intelligente) --}}
                        @php
                            $start = max($invoices->currentPage() - 2, 1);
                            $end = min($invoices->currentPage() + 2, $invoices->lastPage());
                        @endphp

                        {{-- Page 1 --}}
                        @if ($start > 1)
                            <a href="{{ $invoices->url(1) }}" class="btn-cuni sm" style="min-width: 40px; justify-content: center;">1</a>
                            @if ($start > 2)
                                <span class="text-gray-400 px-2 flex items-center">...</span>
                            @endif
                        @endif

                        {{-- Pages intermédiaires --}}
                        @for ($i = $start; $i <= $end; $i++)
                            <a href="{{ $invoices->url($i) }}" 
                               class="btn-cuni sm {{ $i == $invoices->currentPage() ? 'primary' : 'secondary' }}" 
                               style="min-width: 40px; justify-content: center;">
                                {{ $i }}
                            </a>
                        @endfor

                        {{-- Dernière Page --}}
                        @if ($end < $invoices->lastPage())
                            @if ($end < $invoices->lastPage() - 1)
                                <span class="text-gray-400 px-2 flex items-center">...</span>
                            @endif
                            <a href="{{ $invoices->url($invoices->lastPage()) }}" 
                               class="btn-cuni sm" style="min-width: 40px; justify-content: center;">
                                {{ $invoices->lastPage() }}
                            </a>
                        @endif

                        {{-- Bouton Suivant --}}
                        @if ($invoices->hasMorePages())
                            <a href="{{ $invoices->nextPageUrl() }}" class="btn-cuni secondary sm">
                                Suivant <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="btn-cuni secondary sm opacity-50 cursor-not-allowed" style="pointer-events: none;">
                                Suivant <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
