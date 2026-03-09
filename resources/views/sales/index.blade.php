{{-- resources/views/sales/show.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Détails Vente - CuniApp Élevage')

@section('content')
@if (session('success'))
<div class="alert-cuni success" style="margin-bottom: 24px;">
    <i class="bi bi-check-circle-fill"></i>
    <div>{{ session('success') }}</div>
</div>
@endif

@if (session('warning'))
<div class="alert-cuni warning" style="margin-bottom: 24px;">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <div>{{ session('warning') }}</div>
</div>
@endif

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
                        <label class="form-label">Acheteur</label>
                        <p class="fw-semibold">{{ $sale->buyer_name }}</p>
                    </div>
                    @if ($sale->buyer_contact)
                    <div class="form-group">
                        <label class="form-label">Contact</label>
                        <p>{{ $sale->buyer_contact }}</p>
                    </div>
                    @endif
                    @if ($sale->buyer_address)
                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <p>{{ nl2br(e($sale->buyer_address)) }}</p>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Montant total</label>
                        <p class="fw-bold" style="color: var(--primary); font-size: 1.2rem;">
                            {{ number_format($sale->total_amount, 2, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Rabbit Category Breakdown --}}
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-graph-up"></i> Résumé de la Vente
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-3 gap-4">
                    <div style="text-align: center; padding: 16px; background: rgba(59, 130, 246, 0.1); border-radius: var(--radius);">
                        <div style="font-size: 24px; font-weight: 700; color: #3B82F6;">
                            {{ $sale->rabbits->where('rabbit_type', 'male')->count() }}
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Mâles</div>
                    </div>
                    <div style="text-align: center; padding: 16px; background: rgba(236, 72, 153, 0.1); border-radius: var(--radius);">
                        <div style="font-size: 24px; font-weight: 700; color: #EC4899;">
                            {{ $sale->rabbits->where('rabbit_type', 'female')->count() }}
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Femelles</div>
                    </div>
                    <div style="text-align: center; padding: 16px; background: rgba(16, 185, 129, 0.1); border-radius: var(--radius);">
                        <div style="font-size: 24px; font-weight: 700; color: #10B981;">
                            {{ $sale->rabbits->where('rabbit_type', 'lapereau')->count() }}
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary);">Lapereaux</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Lapins Associés avec Recherche et Pagination --}}
        <div class="cuni-card" style="margin-top: 24px;">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="bi bi-collection"></i> Lapins Vendus ({{ $sale->rabbits->count() }})
                </h3>
            </div>
            <div class="card-body">
                {{-- Search & Filters --}}
                <div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
                    <form method="GET" action="{{ route('sales.show', $sale) }}" style="display: flex; gap: 12px; flex: 1; min-width: 300px;">
                        <input type="text" name="search_rabbit" class="form-control" placeholder="Rechercher par nom ou code..." value="{{ request('search_rabbit') }}" style="flex: 1;">
                        <button type="submit" class="btn-cuni primary">
                            <i class="bi bi-search"></i>
                        </button>
                        @if (request('search_rabbit'))
                        <a href="{{ route('sales.show', $sale) }}" class="btn-cuni secondary">
                            <i class="bi bi-x"></i>
                        </a>
                        @endif
                    </form>
                    <select name="filter_category" class="form-select" style="width: auto;" onchange="this.form.submit()">
                        <option value="{{ route('sales.show', $sale) }}">Toutes les catégories</option>
                        <option value="{{ route('sales.show', $sale) }}?filter_category=male" {{ request('filter_category') == 'male' ? 'selected' : '' }}>Mâles</option>
                        <option value="{{ route('sales.show', $sale) }}?filter_category=female" {{ request('filter_category') == 'female' ? 'selected' : '' }}>Femelles</option>
                        <option value="{{ route('sales.show', $sale) }}?filter_category=lapereau" {{ request('filter_category') == 'lapereau' ? 'selected' : '' }}>Lapereaux</option>
                    </select>
                </div>

                @if ($rabbits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Type</th>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Prix Individuel</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="saleRabbitsTable">
                            @foreach ($rabbits as $saleRabbit)
                            <tr class="rabbit-row" data-category="{{ $saleRabbit->rabbit_type }}" data-code="{{ $saleRabbit->rabbit?->code ?? '' }}" data-name="{{ $saleRabbit->rabbit?->nom ?? '' }}">
                                <td>
                                    @if ($saleRabbit->rabbit_type === 'male')
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                        <i class="bi bi-arrow-up-right-square"></i> Mâle
                                    </span>
                                    @elseif($saleRabbit->rabbit_type === 'female')
                                    <span class="badge" style="background: rgba(236, 72, 153, 0.1); color: #EC4899;">
                                        <i class="bi bi-arrow-down-right-square"></i> Femelle
                                    </span>
                                    @else
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                                        <i class="bi bi-egg-fill"></i> Lapereau
                                    </span>
                                    @endif
                                </td>
                                <td class="fw-semibold" style="font-family: 'JetBrains Mono', monospace;">
                                    {{ $saleRabbit->rabbit?->code ?? 'N/A' }}
                                </td>
                                <td>{{ $saleRabbit->rabbit?->nom ?? 'N/A' }}</td>
                                <td style="font-weight: 600; color: var(--primary);">
                                    {{ number_format($saleRabbit->sale_price ?? 0, 2, ',', ' ') }} FCFA
                                </td>
                                <td>
                                    @if ($saleRabbit->rabbit_type === 'male')
                                    <a href="{{ route('males.show', $saleRabbit->rabbit_id) }}" class="btn-cuni sm secondary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @elseif($saleRabbit->rabbit_type === 'female')
                                    <a href="{{ route('femelles.show', $saleRabbit->rabbit_id) }}" class="btn-cuni sm secondary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @else
                                    <a href="{{ route('lapins.edit', $saleRabbit->rabbit_id) }}" class="btn-cuni sm secondary" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ✅ Pagination Links --}}
                @if ($rabbits->hasPages())
                <div style="margin-top: 20px;">
                    {{ $rabbits->appends(request()->query())->links('pagination.bootstrap-5-sm') }}
                </div>
                @endif

                @else
                <p class="text-muted text-center" style="padding: 40px;">
                    <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5;"></i><br>
                    Aucun lapin associé à cette vente.
                </p>
                @endif
            </div>
        </div>

        @if ($sale->notes)
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
                    @if ($sale->buyer_contact)
                    <div>
                        <label class="text-sm text-gray-500">Contact</label>
                        <p class="font-semibold">{{ $sale->buyer_contact }}</p>
                    </div>
                    @endif
                    @if ($sale->buyer_address)
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
                            @if ($sale->payment_status === 'paid')
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
                    @if ($sale->balance > 0)
                    <div>
                        <label class="text-sm text-gray-500">Solde restant</label>
                        <p class="font-semibold" style="color: var(--accent-orange);">
                            {{ number_format($sale->balance, 2, ',', ' ') }} FCFA
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Payment Actions --}}
                @if ($sale->payment_status !== 'paid')
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
                    <form action="{{ route('sales.mark-paid', $sale) }}" method="POST" id="mark-paid-form-show-{{ $sale->id }}" style="margin-bottom: 12px;">
                        @csrf
                        @method('PATCH')
                    </form>
                    <button type="button" class="btn-cuni primary" style="width: 100%; margin-bottom: 12px;" onclick="showModal('confirm', 'Confirmer le paiement', 'Voulez-vous vraiment marquer cette vente comme payée ?', function() { document.getElementById('mark-paid-form-show-{{ $sale->id }}').submit(); })">
                        <i class="bi bi-cash-coin"></i> Marquer comme payé
                    </button>

                    {{-- Partial Payment Button --}}
                    <a href="{{ route('sales.edit', $sale) }}#payment-section" class="btn-cuni secondary" style="width: 100%;">
                        <i class="bi bi-receipt"></i> Enregistrer paiement partiel
                    </a>

                    {{-- Change Status Dropdown --}}
                    <div style="margin-top: 12px;">
                        <form action="{{ route('sales.change-status', $sale) }}" method="POST">
                            @csrf
                            <select name="payment_status" class="form-select" style="margin-bottom: 8px;" onchange="this.form.submit()">
                                <option value="pending" {{ $sale->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="partial" {{ $sale->payment_status === 'partial' ? 'selected' : '' }}>Paiement partiel</option>
                                <option value="paid" {{ $sale->payment_status === 'paid' ? 'selected' : '' }}>Payé</option>
                            </select>
                        </form>
                    </div>
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

@push('scripts')
<script>
function filterSaleRabbits() {
    const searchTerm = document.getElementById('searchRabbits')?.value.toLowerCase() || '';
    const categoryFilter = document.getElementById('filterCategory')?.value || '';
    const rows = document.querySelectorAll('#saleRabbitsTable .rabbit-row');
    
    rows.forEach(row => {
        const category = row.dataset.category;
        const code = row.dataset.code.toLowerCase();
        const name = row.dataset.name.toLowerCase();
        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;
        
        if (matchesSearch && matchesCategory) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection