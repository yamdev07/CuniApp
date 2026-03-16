{{-- resources/views/sales/index.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Ventes - CuniApp Élevage')
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
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total des ventes</p>
                        <p class="text-2xl font-bold mt-1 text-gray-800 dark:text-gray-100">{{ $stats['total_sales'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(59, 130, 246, 0.1)">
                        <i class="bi bi-cart text-blue-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Chiffre d'affaires</p>
                        <p class="text-2xl font-bold mt-1 text-gray-800 dark:text-gray-100">
                            {{ number_format($stats['total_revenue'], 2, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(16, 185, 129, 0.1)">
                        <i class="bi bi-cash-stack text-green-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ce mois-ci</p>
                        <p class="text-2xl font-bold mt-1 text-gray-800 dark:text-gray-100">
                            {{ number_format($stats['this_month'], 2, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(139, 92, 246, 0.1)">
                        <i class="bi bi-graph-up text-purple-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Paiements en attente</p>
                        <p class="text-2xl font-bold mt-1 text-amber-600 dark:text-amber-400">
                            {{ number_format($stats['pending_payments'], 2, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(245, 158, 11, 0.1)">
                        <i class="bi bi-hourglass-split text-amber-500 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="cuni-card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Supprimables (60j+)
                        </p>
                        <p class="text-2xl font-bold mt-1 text-danger">
                            {{ $stats['deletable_sales'] ?? 0 }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                        style="background: rgba(239, 68, 68, 0.1)">
                        <i class="bi bi-trash text-danger text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- resources/views/sales/index.blade.php - Search Section --}}
    {{-- resources/views/sales/index.blade.php - Search Section --}}
    <div class="cuni-card" style="margin-bottom: 20px;">
        <div class="card-body" style="padding: 16px;">
            <form method="GET" action="{{ route('sales.index') }}">
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
                    {{-- Recherche texte --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-search" style="margin-right: 4px;"></i> Recherche
                        </label>
                        <input type="text" name="search" value="{{ request()->get('search') }}" class="form-control"
                            placeholder="Acheteur, note..." style="border-radius: var(--radius-lg);">
                    </div>

                    {{-- ✅ REMOVED: Statut Paiement (now in Filtres Rapides) --}}

                    {{-- Filtre date début --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-calendar-event" style="margin-right: 4px;"></i> Du
                        </label>
                        <input type="date" name="date_from" value="{{ request()->get('date_from') }}"
                            class="form-control" style="border-radius: var(--radius-lg);">
                    </div>

                    {{-- Filtre date fin --}}
                    <div>
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">
                            <i class="bi bi-calendar-check" style="margin-right: 4px;"></i> Au
                        </label>
                        <input type="date" name="date_to" value="{{ request()->get('date_to') }}" class="form-control"
                            style="border-radius: var(--radius-lg);">
                    </div>

                    {{-- Boutons d'action --}}
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-cuni primary" style="flex: 1; padding: 10px;">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        @if (request()->anyFilled(['search', 'date_from', 'date_to']))
                            <a href="{{ route('sales.index') }}" class="btn-cuni secondary" style="padding: 10px 16px;"
                                title="Réinitialiser les filtres">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Résumé des filtres actifs --}}
                @if (request()->anyFilled(['search', 'date_from', 'date_to']))
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--surface-border);">
                        <small style="color: var(--text-tertiary);">
                            Filtres actifs :
                            @if (request('search'))
                                <span class="badge"
                                    style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    🔍 "{{ request('search') }}"
                                </span>
                            @endif
                            @if (request('date_from'))
                                <span class="badge"
                                    style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    📅 Du: {{ request('date_from') }}
                                </span>
                            @endif
                            @if (request('date_to'))
                                <span class="badge"
                                    style="background: var(--primary-subtle); color: var(--primary); margin: 0 4px;">
                                    📅 Au: {{ request('date_to') }}
                                </span>
                            @endif
                            <a href="{{ route('sales.index') }}"
                                style="color: var(--accent-red); margin-left: 8px; text-decoration: none;">
                                <i class="bi bi-x-circle"></i> Tout effacer
                            </a>
                        </small>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- ✅ FILTRES RAPIDES (Payment Status filters moved here) --}}
    <div class="cuni-card" style="margin-bottom: 20px;">
        <div class="card-body" style="padding: 16px;">
            <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                <span style="font-weight: 600; color: var(--text-primary);">
                    <i class="bi bi-funnel"></i> Filtres rapides :
                </span>
                <a href="{{ route('sales.index') }}"
                    class="badge {{ !request('payment_status') && !request('filter') ? 'bg-primary' : 'bg-secondary' }}"
                    style="text-decoration: none; padding: 6px 12px; border-radius: 20px;">
                    Toutes
                </a>
                <a href="{{ route('sales.index', ['payment_status' => 'paid']) }}"
                    class="badge {{ request('payment_status') === 'paid' ? 'bg-success' : 'bg-secondary' }}"
                    style="text-decoration: none; padding: 6px 12px; border-radius: 20px;">
                    <i class="bi bi-check-circle"></i> Payé
                </a>
                <a href="{{ route('sales.index', ['payment_status' => 'partial']) }}"
                    class="badge {{ request('payment_status') === 'partial' ? 'bg-warning' : 'bg-secondary' }}"
                    style="text-decoration: none; padding: 6px 12px; border-radius: 20px;">
                    <i class="bi bi-clock"></i> Partiel
                </a>
                <a href="{{ route('sales.index', ['payment_status' => 'pending']) }}"
                    class="badge {{ request('payment_status') === 'pending' ? 'bg-danger' : 'bg-secondary' }}"
                    style="text-decoration: none; padding: 6px 12px; border-radius: 20px;">
                    <i class="bi bi-hourglass"></i> En attente
                </a>
                <a href="{{ route('sales.index', ['filter' => 'deletable']) }}"
                    class="badge {{ request('filter') === 'deletable' ? 'bg-danger' : 'bg-secondary' }}"
                    style="text-decoration: none; padding: 6px 12px; border-radius: 20px;">
                    <i class="bi bi-trash"></i> Supprimables (60j+)
                </a>
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
                    <thead class="bg-light dark:bg-gray-800">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-semibold small">Date</th>
                            <th class="text-uppercase text-muted fw-semibold small">Article</th>
                            <th class="text-uppercase text-muted fw-semibold small">Quantité</th>
                            <th class="text-uppercase text-muted fw-semibold small">Acheteur</th>
                            <th class="text-uppercase text-muted fw-semibold small">Montant</th>
                            <th class="text-uppercase text-muted fw-semibold small">Statut</th>
                            <th class="pe-4 text-end text-uppercase text-muted fw-semibold small">Actions</th>
                            <th class="text-uppercase text-muted fw-semibold small">Suppression</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            @php
                                // Parser explicitement la date de vente avec Carbon
                                $saleDate = \Carbon\Carbon::parse($sale->date_sale)->startOfDay();
                                $now = now()->startOfDay();
                                $daysSinceSale = max(0, $now->diffInDays($saleDate));
                                $canDelete = $daysSinceSale >= 60;
                                $canEdit = $daysSinceSale < 60;
                                $remainingDays = $canDelete ? 0 : max(0, min(60, 60 - $daysSinceSale));
                            @endphp
                            <tr class="border-bottom border-light dark:border-gray-700">
                                <td class="ps-4 fw-semibold text-dark dark:text-gray-100">
                                    {{ $sale->date_sale->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6;">
                                        {{ ucfirst($sale->type) }}{{ $sale->category ? " ({$sale->category})" : '' }}
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark dark:text-gray-100">{{ $sale->quantity }}</td>
                                <td class="text-dark dark:text-gray-100">{{ $sale->buyer_name }}</td>
                                <td class="fw-bold text-primary dark:text-blue-400">
                                    {{ number_format($sale->total_amount, 2, ',', ' ') }} FCFA
                                </td>
                                <td>
                                    @if ($sale->payment_status === 'paid')
                                        <span class="badge"
                                            style="background: rgba(16, 185, 129, 0.1); color: #10B981;">Payé</span>
                                    @elseif($sale->payment_status === 'partial')
                                        <span class="badge"
                                            style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">Partiel</span>
                                    @else
                                        <span class="badge"
                                            style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">En attente</span>
                                    @endif
                                </td>
                                {{-- ✅ Colonne Actions --}}
                                <td class="pe-4">
                                    <div class="action-buttons" style="display: flex; gap: 4px;">
                                        {{-- Bouton Détails --}}
                                        <a href="{{ route('sales.show', $sale) }}" class="btn-cuni sm secondary"
                                            title="Détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        {{-- Bouton Modifier (seulement si < 60 jours) --}}
                                        @if ($canEdit)
                                            <a href="{{ route('sales.edit', $sale) }}" class="btn-cuni sm secondary"
                                                title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                        {{-- Bouton Marquer comme payé --}}
                                        @if ($sale->payment_status !== 'paid')
                                            <form action="{{ route('sales.mark-paid', $sale) }}" method="POST"
                                                id="mark-paid-form-{{ $sale->id }}" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                            <button type="button" class="btn-cuni sm primary" title="Marquer comme payé"
                                                onclick="if(confirm('Voulez-vous vraiment marquer cette vente comme payée ?')) { document.getElementById('mark-paid-form-{{ $sale->id }}').submit(); }">
                                                <i class="bi bi-cash-coin"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                {{-- ✅ Colonne Suppression --}}
                                <td>
                                    @if ($canDelete)
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST"
                                            style="display:inline;" id="delete-form-{{ $sale->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-cuni sm danger"
                                                title="Supprimer définitivement" data-sale-info="{{ $sale->buyer_name }}"
                                                data-rabbit-count="{{ $sale->quantity }}"
                                                onclick="showDeleteModal(document.getElementById('delete-form-{{ $sale->id }}'), '{{ addslashes($sale->buyer_name) }}', '{{ $sale->quantity }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge"
                                            style="background: rgba(245, 158, 11, 0.15); 
                                                   color: #F59E0B; 
                                                   font-size: 10px; 
                                                   padding: 4px 8px; 
                                                   border-radius: 12px;"
                                            title="Suppression disponible dans {{ $remainingDays }} jours">
                                            <i class="bi bi-clock"></i> {{ $remainingDays }}j
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="table-empty-state">
                                        <i class="bi bi-cart-x" style="font-size: 3rem; color: var(--text-tertiary);"></i>
                                        <p class="mt-3 text-gray-600 dark:text-gray-400">Aucune vente enregistrée pour le
                                            moment</p>
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
            @if ($sales->hasPages())
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
                    <div class="text-muted dark:text-gray-400" style="font-size: 13px;">
                        Affichage de <strong class="text-dark dark:text-gray-100">{{ $sales->firstItem() }}</strong> à
                        <strong class="text-dark dark:text-gray-100">{{ $sales->lastItem() }}</strong> sur
                        <strong class="text-dark dark:text-gray-100">{{ $sales->total() }}</strong> ventes
                    </div>
                    @if ($sales->hasPages())
                        <div class="cuni-card"
                            style="margin-top: 0; border-top: none; border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                            {{ $sales->links('pagination.bootstrap-5-sm') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ✅ MODAL DE CONFIRMATION PERSONNALISÉ --}}
    <div id="deleteConfirmModal" class="modal-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: var(--surface); border-radius: var(--radius-xl); padding: 24px; max-width: 480px; width: 90%; box-shadow: var(--shadow-lg); animation: modalSlideIn 0.3s ease;">
            {{-- Header --}}
            <div
                style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--surface-border);">
                <div
                    style="width: 40px; height: 40px; border-radius: 50%; background: rgba(239, 68, 68, 0.15); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-exclamation-triangle-fill" style="color: #EF4444; font-size: 18px;"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0;">
                    Suppression définitive
                </h3>
            </div>
            {{-- Body --}}
            <div style="margin-bottom: 24px;">
                <p style="color: var(--text-secondary); margin: 0 0 12px 0; line-height: 1.6;">
                    Cette action est <strong style="color: var(--accent-red);">IRRÉVERSIBLE</strong> !
                </p>
                <div
                    style="background: rgba(239, 68, 68, 0.08); border-left: 3px solid var(--accent-red); padding: 12px 16px; border-radius: 0 var(--radius) var(--radius) 0; margin-bottom: 16px;">
                    <p style="margin: 0; font-size: 14px; color: var(--text-primary);">
                        <i class="bi bi-info-circle-fill" style="color: var(--accent-red); margin-right: 6px;"></i>
                        La vente <strong id="modalSaleInfo"></strong> et les <strong id="modalRabbitCount"></strong>
                        lapin(s) associé(s) seront supprimés définitivement.
                    </p>
                </div>
                <p style="color: var(--text-tertiary); font-size: 13px; margin: 0;">
                    <i class="bi bi-shield-check" style="margin-right: 4px;"></i>
                    Cette suppression ne peut être annulée.
                </p>
            </div>
            {{-- Footer --}}
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn-cuni secondary" onclick="hideDeleteModal()"
                    style="padding: 10px 20px;">
                    <i class="bi bi-x-lg"></i> Annuler
                </button>
                <button type="button" class="btn-cuni danger" id="modalConfirmBtn" style="padding: 10px 20px;">
                    <i class="bi bi-trash"></i> Supprimer définitivement
                </button>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .theme-dark .text-gray-500 {
                color: #9CA3AF !important;
            }

            .theme-dark .text-gray-600 {
                color: #9CA3AF !important;
            }

            .theme-dark .text-gray-800 {
                color: #F3F4F6 !important;
            }

            .theme-dark .text-gray-100 {
                color: #F9FAFB !important;
            }

            .theme-dark .text-amber-600 {
                color: #FCD34D !important;
            }

            .theme-dark .text-primary {
                color: #60A5FA !important;
            }

            .theme-dark .text-muted {
                color: #9CA3AF !important;
            }

            .theme-dark .fw-semibold {
                color: #E5E7EB !important;
            }

            .theme-dark .badge {
                opacity: 0.95;
            }

            .action-buttons {
                display: flex;
                gap: 4px;
                align-items: center;
            }

            .badge.bg-success {
                background: rgba(16, 185, 129, 0.15) !important;
                color: #10B981 !important;
            }

            .badge.bg-warning {
                background: rgba(245, 158, 11, 0.15) !important;
                color: #F59E0B !important;
            }

            .badge.bg-danger {
                background: rgba(239, 68, 68, 0.15) !important;
                color: #EF4444 !important;
            }

            .badge.bg-primary {
                background: rgba(59, 130, 246, 0.15) !important;
                color: #3B82F6 !important;
            }

            .badge.bg-secondary {
                background: rgba(107, 114, 128, 0.15) !important;
                color: #6B7280 !important;
            }

            .modal-overlay {
                backdrop-filter: blur(4px);
            }

            @keyframes modalSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(-20px) scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes modalSlideOut {
                from {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }

                to {
                    opacity: 0;
                    transform: translateY(-20px) scale(0.95);
                }
            }

            .modal-content {
                animation: modalSlideIn 0.3s ease;
            }

            .modal-overlay.closing .modal-content {
                animation: modalSlideOut 0.3s ease forwards;
            }

            .modal-content .btn-cuni.danger:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            }

            @media (max-width: 480px) {
                .modal-content {
                    padding: 20px 16px;
                    margin: 16px;
                }

                .modal-content h3 {
                    font-size: 16px;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // ============================================
            // DEBUG: Vérifier que le script se charge
            // ============================================
            console.log('✅ sales/index.blade.php scripts loaded');

            // ============================================
            // MODAL DE SUPPRESSION PERSONNALISÉ
            // ============================================
            (function() {
                let pendingForm = null;
                const modal = document.getElementById('deleteConfirmModal');

                // Vérifier que le modal existe
                if (!modal) {
                    console.error('❌ Modal #deleteConfirmModal not found!');
                    return;
                }

                // Afficher le modal
                function showDeleteModal(form, saleInfo, rabbitCount) {
                    console.log('🗑️ showDeleteModal called:', {
                        form,
                        saleInfo,
                        rabbitCount
                    });

                    pendingForm = form;

                    // Remplir les infos
                    const infoEl = document.getElementById('modalSaleInfo');
                    const countEl = document.getElementById('modalRabbitCount');
                    if (infoEl) infoEl.textContent = saleInfo || 'cette vente';
                    if (countEl) countEl.textContent = rabbitCount || 'plusieurs';

                    // Afficher le modal (forcer le display)
                    modal.style.display = 'flex';
                    modal.style.opacity = '1';
                    modal.style.visibility = 'visible';
                    document.body.style.overflow = 'hidden';

                    console.log('✅ Modal displayed');
                }

                // Masquer le modal
                function hideDeleteModal() {
                    console.log('🔒 hideDeleteModal called');

                    modal.classList.add('closing');

                    setTimeout(() => {
                        modal.style.display = 'none';
                        modal.classList.remove('closing');
                        document.body.style.overflow = '';
                        pendingForm = null;

                        // Réinitialiser le bouton
                        const btn = document.getElementById('modalConfirmBtn');
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-trash"></i> Supprimer définitivement';
                        }
                    }, 300);
                }

                // Confirmer et soumettre
                function confirmDelete() {
                    console.log('⚡ confirmDelete called, pendingForm:', pendingForm);

                    if (pendingForm) {
                        const btn = document.getElementById('modalConfirmBtn');
                        if (btn) {
                            btn.disabled = true;
                            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> En cours...';
                        }
                        pendingForm.submit();
                    }
                }

                // Initialisation au chargement
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('🔄 DOM loaded, initializing delete modal handlers');

                    // Bouton Annuler
                    const cancelBtn = modal.querySelector('.btn-cuni.secondary');
                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            hideDeleteModal();
                        });
                    }

                    // Bouton Confirmer
                    const confirmBtn = document.getElementById('modalConfirmBtn');
                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            confirmDelete();
                        });
                    }

                    // Clic en dehors du modal
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            hideDeleteModal();
                        }
                    });

                    // Touche Échap
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && modal.style.display === 'flex') {
                            hideDeleteModal();
                        }
                    });

                    // Attacher les événements aux boutons poubelle (pour les chargements AJAX)
                    function attachDeleteHandlers() {
                        document.querySelectorAll('form[id^="delete-form-"] button[type="button"].danger').forEach(
                            btn => {
                                // Éviter les doublons
                                if (btn.dataset.handlerAttached) return;
                                btn.dataset.handlerAttached = 'true';

                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    console.log('🗑️ Delete button clicked');

                                    const form = this.closest('form');
                                    const saleInfo = this.dataset.saleInfo || 'cette vente';
                                    const rabbitCount = this.dataset.rabbitCount || 'plusieurs';

                                    showDeleteModal(form, saleInfo, rabbitCount);
                                });
                            });
                    }

                    // Attacher immédiatement + pour les futurs éléments AJAX
                    attachDeleteHandlers();
                    if (window.attachDeleteHandlers) {
                        const original = window.attachDeleteHandlers;
                        window.attachDeleteHandlers = function() {
                            original();
                            attachDeleteHandlers();
                        };
                    } else {
                        window.attachDeleteHandlers = attachDeleteHandlers;
                    }
                });

                // Exposer globalement pour l'onclick inline
                window.showDeleteModal = showDeleteModal;
                window.hideDeleteModal = hideDeleteModal;
                window.confirmDelete = confirmDelete;

            })();
        </script>

        {{-- ✅ Fallback: Si le modal ne s'affiche pas, utiliser confirm() natif --}}
        <script>
            // Fallback après 2 secondes si le modal ne fonctionne pas
            setTimeout(function() {
                const modal = document.getElementById('deleteConfirmModal');
                const buttons = document.querySelectorAll('button[onclick*="showDeleteModal"]');

                if (modal && buttons.length > 0) {
                    // Tester si le modal est fonctionnel
                    buttons[0].addEventListener('click', function testModal(e) {
                        // Laisser le onclick inline s'exécuter d'abord
                        setTimeout(function() {
                            if (modal.style.display !== 'flex') {
                                console.warn('⚠️ Modal not working, falling back to native confirm()');
                                // Réattacher avec confirm() natif en fallback
                                buttons.forEach(btn => {
                                    btn.onclick = function(e) {
                                        e.preventDefault();
                                        const form = this.closest('form');
                                        if (form && confirm(
                                                '⚠️ SUPPRESSION DÉFINITIVE\n\nÊtes-vous ABSOLUMENT sûr ?'
                                            )) {
                                            form.submit();
                                        }
                                    };
                                });
                            }
                        }, 100);
                    }, {
                        once: true
                    });
                }
            }, 2000);
        </script>
    @endpush
@endsection
