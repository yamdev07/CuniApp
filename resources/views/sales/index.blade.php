@extends('layouts.cuniapp')
@section('title', 'Ventes - CuniApp Élevage')
@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">
            <i class="bi bi-cart-check-fill"></i> Gestion des Ventes
        </h2>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Tableau de bord</a>
            <span>/</span>
            <span>Ventes</span>
        </div>
    </div>
    <a href="{{ route('sales.create') }}" class="btn-cuni primary">
        <i class="bi bi-plus-lg"></i> Nouvelle vente
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="cuni-card">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total des ventes</p>
                    <p class="text-2xl font-bold mt-1 text-gray-800">{{ $stats['total_sales'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(59, 130, 246, 0.1)">
                    <i class="bi bi-cart text-blue-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="cuni-card">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Chiffre d'affaires</p>
                    <p class="text-2xl font-bold mt-1 text-gray-800">{{ number_format($stats['total_revenue'], 2, ',', ' ') }} €</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(16, 185, 129, 0.1)">
                    <i class="bi bi-currency-euro text-green-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="cuni-card">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Ce mois-ci</p>
                    <p class="text-2xl font-bold mt-1 text-gray-800">{{ number_format($stats['this_month'], 2, ',', ' ') }} €</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(139, 92, 246, 0.1)">
                    <i class="bi bi-graph-up text-purple-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="cuni-card">
        <div class="card-body p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Paiements en attente</p>
                    <p class="text-2xl font-bold mt-1 text-amber-600">{{ number_format($stats['pending_payments'], 2, ',', ' ') }} €</p>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(245, 158, 11, 0.1)">
                    <i class="bi bi-hourglass-split text-amber-500 text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cuni-card">
    <div class="card-header-custom">
        <h3 class="card-title">
            <i class="bi bi-list-ul"></i> Historique des ventes
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-muted fw-semibold small">Date</th>
                        <th class="text-uppercase text-muted fw-semibold small">Article</th>
                        <th class="text-uppercase text-muted fw-semibold small">Quantité</th>
                        <th class="text-uppercase text-muted fw-semibold small">Acheteur</th>
                        <th class="text-uppercase text-muted fw-semibold small">Montant</th>
                        <th class="text-uppercase text-muted fw-semibold small">Statut</th>
                        <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold">{{ $sale->date_sale->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                {{ ucfirst($sale->type) }}{{ $sale->category ? " ({$sale->category})" : '' }}
                            </span>
                        </td>
                        <td class="fw-semibold">{{ $sale->quantity }}</td>
                        <td>{{ $sale->buyer_name }}</td>
                        <td class="fw-bold text-primary">{{ number_format($sale->total_amount, 2, ',', ' ') }} €</td>
                        <td>
                            @if($sale->payment_status === 'paid')
                                <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">Payé</span>
                            @elseif($sale->payment_status === 'partial')
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">Partiel</span>
                            @else
                                <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">En attente</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="action-buttons">
                                <a href="{{ route('sales.show', $sale) }}" class="btn-cuni sm secondary" title="Détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('sales.edit', $sale) }}" class="btn-cuni sm secondary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($sale->payment_status !== 'paid')
                                <form action="{{ route('sales.mark-paid', $sale) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-cuni sm primary" title="Marquer comme payé" 
                                        onclick="return confirm('Marquer cette vente comme payée ?')">
                                        <i class="bi bi-cash-coin"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-cuni sm danger" title="Supprimer"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="table-empty-state">
                                <i class="bi bi-cart-x" style="font-size: 3rem; color: var(--text-tertiary);"></i>
                                <p class="mt-3">Aucune vente enregistrée pour le moment</p>
                                <a href="{{ route('sales.create') }}" class="btn-cuni primary mt-3">
                                    <i class="bi bi-plus-lg"></i> Enregistrer une première vente
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sales->hasPages())
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
            <div class="text-muted" style="font-size: 13px;">
                Affichage de <strong>{{ $sales->firstItem() }}</strong> à <strong>{{ $sales->lastItem() }}</strong> sur <strong>{{ $sales->total() }}</strong> ventes
            </div>
            <nav>{{ $sales->links('vendor.pagination.bootstrap-5-sm') }}</nav>
        </div>
        @endif
    </div>
</div>
@endsection