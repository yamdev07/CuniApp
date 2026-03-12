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

    <!-- Filters -->
    <div class="cuni-card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('invoices.index') }}" class="flex gap-3 flex-wrap">
                <select name="status" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payées</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées</option>
                </select>

                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"
                    style="width: auto;" placeholder="Date début">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"
                    style="width: auto;" placeholder="Date fin">

                <button type="submit" class="btn-cuni primary">
                    <i class="bi bi-search"></i> Filtrer
                </button>

                @if (request()->hasAny(['status', 'start_date', 'end_date']))
                    <a href="{{ route('invoices.index') }}" class="btn-cuni secondary">
                        <i class="bi bi-x-circle"></i> Effacer
                    </a>
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
                                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #6B7280;">
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

            @if ($invoices->hasPages())
                <div style="margin-top: 20px;">
                    {{ $invoices->links('pagination.bootstrap-5-sm') }}
                </div>
            @endif
        </div>
    </div>
@endsection
