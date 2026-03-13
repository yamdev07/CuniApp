{{-- resources/views/sales/create.blade.php --}}
@extends('layouts.cuniapp')
@section('title', 'Nouvelle Vente - CuniApp Élevage')
@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-cart-plus-fill"></i> Nouvelle Vente
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('sales.index') }}">Ventes</a>
                <span>/</span>
                <span>Nouvelle</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-cuni error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Erreurs de validation</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="cuni-card">
        <div class="card-header-custom">
            <h3 class="card-title">
                <i class="bi bi-receipt"></i> Informations de la vente
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                @csrf

                {{-- Section: Acheteur --}}
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-person"></i> Informations acheteur
                    </h4>
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Nom de l'acheteur *</label>
                            <input type="text" name="buyer_name" class="form-control" value="{{ old('buyer_name') }}"
                                required placeholder="Nom complet">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact</label>
                            <input type="text" name="buyer_contact" class="form-control"
                                value="{{ old('buyer_contact') }}" placeholder="Téléphone ou email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Adresse</label>
                            <textarea name="buyer_address" class="form-control" rows="2" placeholder="Adresse complète">{{ old('buyer_address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de vente *</label>
                            <input type="date" name="date_sale" class="form-control"
                                value="{{ old('date_sale', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>

                {{--  Global Price Settings Section --}}
                <div class="form-section"
                    style="margin-top: 24px; border: 2px solid var(--primary-subtle); background: linear-gradient(135deg, var(--primary-subtle) 0%, var(--surface-alt) 100%);">
                    <h4 class="section-subtitle">
                        <i class="bi bi-cash-coin" style="color: var(--accent-green);"></i> Prix Globaux par Catégorie
                    </h4>
                    <div class="alert-box info" style="margin-bottom: 16px;">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>💡 Astuce:</strong> Définissez un prix par défaut pour chaque catégorie. Ce prix sera
                            automatiquement appliqué à tous les lapins sélectionnés.
                            <br><small>Vous pourrez toujours modifier le prix individuellement si nécessaire.</small>
                        </div>
                    </div>

                    <div class="global-prices-grid"
                        style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px;">
                        {{-- Global Price: Mâles --}}
                        <div class="global-price-card"
                            style="background: var(--surface); padding: 16px; border-radius: var(--radius-lg); border: 1px solid var(--surface-border);">
                            <label
                                style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; display: block;">
                                <i class="bi bi-arrow-up-right-square" style="color: #3B82F6;"></i> Prix Mâles (FCFA)
                            </label>
                            <input type="number" id="globalPriceMale" class="form-control global-price-input"
                                data-category="male"
                                value="{{ old('global_price_male', \App\Models\Setting::get('default_price_male', 25000)) }}"
                                min="0" step="100" placeholder="25000"
                                style="font-weight: 600; font-size: 16px; border-color: #3B82F6;">
                            <small style="color: var(--text-tertiary); font-size: 11px; margin-top: 4px; display: block;">
                                Défaut:
                                {{ number_format(\App\Models\Setting::get('default_price_male', 25000), 0, ',', ' ') }} FCFA
                            </small>
                        </div>

                        {{-- Global Price: Femelles --}}
                        <div class="global-price-card"
                            style="background: var(--surface); padding: 16px; border-radius: var(--radius-lg); border: 1px solid var(--surface-border);">
                            <label
                                style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; display: block;">
                                <i class="bi bi-arrow-down-right-square" style="color: #EC4899;"></i> Prix Femelles (FCFA)
                            </label>
                            <input type="number" id="globalPriceFemale" class="form-control global-price-input"
                                data-category="female"
                                value="{{ old('global_price_female', \App\Models\Setting::get('default_price_female', 30000)) }}"
                                min="0" step="100" placeholder="30000"
                                style="font-weight: 600; font-size: 16px; border-color: #EC4899;">
                            <small style="color: var(--text-tertiary); font-size: 11px; margin-top: 4px; display: block;">
                                Défaut:
                                {{ number_format(\App\Models\Setting::get('default_price_female', 30000), 0, ',', ' ') }}
                                FCFA
                            </small>
                        </div>

                        {{-- Global Price: Lapereaux --}}
                        <div class="global-price-card"
                            style="background: var(--surface); padding: 16px; border-radius: var(--radius-lg); border: 1px solid var(--surface-border);">
                            <label
                                style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; display: block;">
                                <i class="bi bi-egg-fill" style="color: #10B981;"></i> Prix Lapereaux (FCFA)
                            </label>
                            <input type="number" id="globalPriceLapereau" class="form-control global-price-input"
                                data-category="lapereau"
                                value="{{ old('global_price_lapereau', \App\Models\Setting::get('default_price_lapereau', 15000)) }}"
                                min="0" step="100" placeholder="15000"
                                style="font-weight: 600; font-size: 16px; border-color: #10B981;">
                            <small style="color: var(--text-tertiary); font-size: 11px; margin-top: 4px; display: block;">
                                Défaut:
                                {{ number_format(\App\Models\Setting::get('default_price_lapereau', 15000), 0, ',', ' ') }}
                                FCFA
                            </small>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button type="button" class="btn-cuni secondary" onclick="applyGlobalPricesToAll()">
                            <i class="bi bi-magic"></i> Appliquer aux lapins déjà sélectionnés
                        </button>
                        <label class="btn-cuni secondary" style="cursor: pointer; margin: 0;">
                            <input type="checkbox" id="autoApplyGlobalPrice" checked style="display: none;">
                            <i class="bi bi-check-circle"></i> Appliquer automatiquement à la sélection
                        </label>
                        <button type="button" class="btn-cuni secondary" onclick="saveGlobalPricesAsDefault()">
                            <i class="bi bi-save"></i> Enregistrer comme défaut
                        </button>
                    </div>
                </div>

                {{-- ✅ Section: Sélection des Lapins avec Prix Individuels --}}
                <div class="form-section" style="margin-top: 24px; border: 2px solid var(--primary-subtle);">
                    <h4 class="section-subtitle">
                        <i class="bi bi-collection"></i> Sélection des Lapins à Vendre
                    </h4>
                    <div class="alert-box info" style="margin-bottom: 16px;">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Important:</strong>
                            <ul style="margin: 8px 0 0 16px; padding: 0;">
                                <li>Les prix globaux sont appliqués automatiquement à la sélection</li>
                                <li>Vous pouvez modifier individuellement le prix de chaque lapin</li>
                                <li>Le total est calculé automatiquement</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Tabs for categories --}}
                    <div class="tabs-container" style="margin-bottom: 20px;">
                        <button type="button" class="tab-btn active" data-tab="males-tab">
                            <i class="bi bi-arrow-up-right-square"></i> Mâles (<span
                                id="malesCount">{{ $totalCounts['males'] ?? 0 }}</span>)
                        </button>
                        <button type="button" class="tab-btn" data-tab="females-tab">
                            <i class="bi bi-arrow-down-right-square"></i> Femelles (<span
                                id="femalesCount">{{ $totalCounts['females'] ?? 0 }}</span>)
                        </button>
                        <button type="button" class="tab-btn" data-tab="lapereaux-tab">
                            <i class="bi bi-egg-fill"></i> Lapereaux (<span
                                id="lapereauxCount">{{ $totalCounts['lapereaux'] ?? 0 }}</span>)
                        </button>
                    </div>

                    {{-- ✅ Mâles Tab --}}
                    <div class="tab-content active" id="males-tab">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            <input type="text" class="form-control" placeholder="Rechercher un mâle..."
                                id="searchMales" style="flex: 1; min-width: 250px;"
                                onkeyup="debouncedSearch('males', this.value)">
                            <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('males')">
                                <i class="bi bi-check-square"></i> Tout sélectionner
                            </button>
                            <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('males', false)">
                                <i class="bi bi-square"></i> Tout déselectionner
                            </button>
                        </div>
                        <div class="rabbit-selection-grid" id="malesGrid"
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 12px;">
                            @include('sales.partials.rabbit-grid', [
                                'rabbits' => $males,
                                'type' => 'males',
                                'soldIds' => [],
                            ])
                        </div>
                        <div class="pagination-info" id="malesPaginationInfo"
                            style="margin-top: 16px; text-align: center; color: var(--text-tertiary); font-size: 13px;">
                            Page {{ $males->currentPage() }} sur {{ $males->lastPage() }}
                            ({{ $totalCounts['males'] ?? $males->total() }} mâles au total)
                        </div>
                    </div>

                    {{-- ✅ Femelles Tab --}}
                    <div class="tab-content" id="females-tab">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            <input type="text" class="form-control" placeholder="Rechercher une femelle..."
                                id="searchFemales" style="flex: 1; min-width: 250px;"
                                onkeyup="debouncedSearch('females', this.value)">
                            <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('females')">
                                <i class="bi bi-check-square"></i> Tout sélectionner
                            </button>
                            <button type="button" class="btn-cuni secondary"
                                onclick="toggleSelectAll('females', false)">
                                <i class="bi bi-square"></i> Tout déselectionner
                            </button>
                        </div>
                        <div class="rabbit-selection-grid" id="femalesGrid"
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 12px;">
                            @include('sales.partials.rabbit-grid', [
                                'rabbits' => $femelles,
                                'type' => 'females',
                                'soldIds' => [],
                            ])
                        </div>
                        <div class="pagination-info" id="femalesPaginationInfo"
                            style="margin-top: 16px; text-align: center; color: var(--text-tertiary); font-size: 13px;">
                            Page {{ $femelles->currentPage() }} sur {{ $femelles->lastPage() }}
                            ({{ $totalCounts['females'] ?? $femelles->total() }} femelles au total)
                        </div>
                    </div>

                    {{-- ✅ Lapereaux Tab --}}
                    <div class="tab-content" id="lapereaux-tab">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            <input type="text" class="form-control" placeholder="Rechercher un lapereau..."
                                id="searchLapereaux" style="flex: 1; min-width: 250px;"
                                onkeyup="debouncedSearch('lapereaux', this.value)">
                            <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('lapereaux')">
                                <i class="bi bi-check-square"></i> Tout sélectionner
                            </button>
                            <button type="button" class="btn-cuni secondary"
                                onclick="toggleSelectAll('lapereaux', false)">
                                <i class="bi bi-square"></i> Tout déselectionner
                            </button>
                        </div>
                        <div class="rabbit-selection-grid" id="lapereauxGrid"
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 12px;">
                            @include('sales.partials.rabbit-grid', [
                                'rabbits' => $lapereaux,
                                'type' => 'lapereaux',
                                'soldIds' => [],
                            ])
                        </div>
                        <div class="pagination-info" id="lapereauxPaginationInfo"
                            style="margin-top: 16px; text-align: center; color: var(--text-tertiary); font-size: 13px;">
                            Page {{ $lapereaux->currentPage() }} sur {{ $lapereaux->lastPage() }}
                            ({{ $totalCounts['lapereaux'] ?? $lapereaux->total() }} lapereaux au total)
                        </div>
                    </div>

                    {{-- Selected Summary with Total --}}
                    <div
                        style="margin-top: 24px; padding: 16px; background: var(--primary-subtle); border-radius: var(--radius-lg);">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                            <div>
                                <strong style="color: var(--primary);">📊 Résumé de la sélection:</strong>
                                <span id="selectedSummary" style="margin-left: 12px;">0 lapin(s) sélectionné(s)</span>
                            </div>
                            <div style="display: flex; gap: 16px;">
                                <span style="font-size: 13px;">🟦 Mâles: <strong id="selectedMalesCount">0</strong></span>
                                <span style="font-size: 13px;">🟩 Femelles: <strong
                                        id="selectedFemalesCount">0</strong></span>
                                <span style="font-size: 13px;">🟨 Lapereaux: <strong
                                        id="selectedLapereauxCount">0</strong></span>
                            </div>
                        </div>
                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--surface-border);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 14px; font-weight: 600; color: var(--text-primary);">💰 Total de la
                                    vente:</span>
                                <span id="totalAmountDisplay"
                                    style="font-size: 20px; font-weight: 700; color: var(--primary);">0 FCFA</span>
                            </div>
                        </div>
                        <div id="quantityMismatchWarning"
                            style="margin-top: 12px; padding: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius); display: none;">
                            <i class="bi bi-exclamation-triangle" style="color: var(--accent-orange);"></i>
                            <span style="color: var(--accent-orange); font-weight: 600;">Veuillez entrer un prix pour
                                chaque lapin sélectionné!</span>
                        </div>
                    </div>
                </div>

                {{-- Section: Paiement --}}
                <div class="form-section" style="margin-top: 24px;">
                    <h4 class="section-subtitle">
                        <i class="bi bi-credit-card"></i> Paiement
                    </h4>
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Statut du paiement *</label>
                            <select name="payment_status" class="form-select" required id="paymentStatus">
                                <option value="paid">Payé intégralement</option>
                                <option value="partial" selected>Paiement partiel</option>
                                <option value="pending">En attente</option>
                            </select>
                        </div>
                        <div class="form-group" id="partialPaymentGroup">
                            <label class="form-label">Montant versé (FCFA)</label>
                            <input type="number" step="0.01" name="amount_paid" class="form-control" value="0"
                                min="0">
                        </div>
                    </div>
                </div>

                {{-- Section: Notes --}}
                <div class="form-section" style="margin-top: 24px;">
                    <h4 class="section-subtitle">
                        <i class="bi bi-sticky-note"></i> Notes
                    </h4>
                    <div class="form-group">
                        <label class="form-label">Remarques</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div
                    style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                    <button type="submit" class="btn-cuni primary" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Enregistrer la vente
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn-cuni secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                // ============================================
                // GLOBAL PRICE MANAGEMENT
                // ============================================
                let globalPrices = {
                    male: {{ \App\Models\Setting::get('default_price_male', 25000) }},
                    female: {{ \App\Models\Setting::get('default_price_female', 30000) }},
                    lapereau: {{ \App\Models\Setting::get('default_price_lapereau', 15000) }}
                };

                // Track custom prices (not from global)
                let customPrices = {};

                // Track selected rabbit IDs across pagination
                let selectedRabbits = {
                    males: new Set(),
                    females: new Set(),
                    lapereaux: new Set()
                };

                // ============================================
                // HELPER: Get category from tab type
                // ============================================
                function getCategoryFromType(type) {
                    const map = {
                        'males': 'male',
                        'females': 'female',
                        'lapereaux': 'lapereau'
                    };
                    return map[type] || type.replace('s', '');
                }

                // ============================================
                // HELPER: Get plural type from category
                // ============================================
                function getTypeFromCategory(category) {
                    const map = {
                        'male': 'males',
                        'female': 'females',
                        'lapereau': 'lapereaux'
                    };
                    return map[category] || category + 's';
                }

                // Initialize global price inputs
                document.querySelectorAll('.global-price-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const category = this.dataset.category;
                        globalPrices[category] = parseFloat(this.value) || 0;

                        // Visual feedback
                        this.style.borderColor = 'var(--accent-green)';
                        setTimeout(() => {
                            this.style.borderColor = '';
                        }, 1000);

                        // Auto-apply if enabled
                        if (document.getElementById('autoApplyGlobalPrice').checked) {
                            applyGlobalPricesToSelected(category);
                        }
                    });
                });

                // ============================================
                // HANDLE RABBIT SELECTION
                // ============================================
                function handleRabbitSelection(type, rabbitId) {
                    const category = getCategoryFromType(type);
                    const checkbox = document.querySelector(
                        `input[name="selected_${type}[]"][value="${rabbitId}"]`
                    );
                    const priceContainer = document.getElementById(`price-${category}-${rabbitId}`);
                    const priceInput = priceContainer?.querySelector('.rabbit-price');
                    const indicator = document.getElementById(`price-indicator-${category}-${rabbitId}`);

                    if (checkbox && priceContainer) {
                        // Track selection across pagination
                        if (checkbox.checked) {
                            selectedRabbits[type].add(rabbitId);
                        } else {
                            selectedRabbits[type].delete(rabbitId);
                            delete customPrices[`${category}-${rabbitId}`];
                        }

                        // Show/hide price container based on checkbox state
                        priceContainer.style.display = checkbox.checked ? 'block' : 'none';

                        if (checkbox.checked && priceInput) {
                            const customKey = `${category}-${rabbitId}`;

                            // Restore custom price if previously set
                            if (customPrices[customKey] !== undefined) {
                                priceInput.value = customPrices[customKey];
                                markPriceAsCustom(category, rabbitId);
                            } else {
                                // Apply global price by default
                                priceInput.value = globalPrices[category] || 0;
                                if (indicator) {
                                    indicator.style.display = 'block';
                                }
                            }

                            priceInput.focus();
                            priceInput.classList.remove('error');
                        }
                    }

                    // Recalculate total amount
                    calculateTotalAmount();
                }

                // ============================================
                // MARK PRICE AS CUSTOM
                // ============================================
                function markPriceAsCustom(category, rabbitId) {
                    const priceInput = document.querySelector(
                        `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                    );
                    const indicator = document.getElementById(`price-indicator-${category}-${rabbitId}`);

                    if (priceInput) {
                        const currentValue = parseFloat(priceInput.value) || 0;
                        const globalValue = globalPrices[category];

                        if (currentValue !== globalValue) {
                            // Custom price
                            customPrices[`${category}-${rabbitId}`] = currentValue;
                            priceInput.style.borderColor = 'var(--accent-orange)';
                            priceInput.style.backgroundColor = 'rgba(245, 158, 11, 0.05)';
                            if (indicator) {
                                indicator.style.display = 'none';
                            }
                        } else {
                            // Same as global
                            delete customPrices[`${category}-${rabbitId}`];
                            priceInput.style.borderColor = 'var(--accent-green)';
                            priceInput.style.backgroundColor = 'var(--primary-subtle)';
                            if (indicator) {
                                indicator.style.display = 'block';
                            }
                        }

                        setTimeout(() => {
                            if (currentValue === globalValue) {
                                priceInput.style.borderColor = '';
                                priceInput.style.backgroundColor = '';
                            }
                        }, 1000);
                    }
                }

                // ============================================
                // APPLY GLOBAL PRICES TO ALL SELECTED
                // ============================================
                function applyGlobalPricesToAll() {
                    let count = 0;
                    let appliedCount = 0;

                    // Process each category
                    ['males', 'females', 'lapereaux'].forEach(type => {
                        const category = getCategoryFromType(type);
                        const globalPrice = globalPrices[category];

                        // Get ALL selected rabbits for this type (including paginated ones)
                        selectedRabbits[type].forEach(rabbitId => {
                            const priceInput = document.querySelector(
                                `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                            );
                            const indicator = document.getElementById(
                                `price-indicator-${category}-${rabbitId}`
                            );
                            const priceContainer = document.getElementById(`price-${category}-${rabbitId}`);

                            if (priceInput) {
                                // Make sure container is visible
                                if (priceContainer) {
                                    priceContainer.style.display = 'block';
                                }

                                // Set the price
                                priceInput.value = globalPrice;
                                priceInput.style.borderColor = 'var(--accent-green)';
                                priceInput.style.backgroundColor = 'var(--primary-subtle)';

                                if (indicator) {
                                    indicator.style.display = 'block';
                                }

                                // Remove from custom prices
                                delete customPrices[`${category}-${rabbitId}`];
                                appliedCount++;
                            }
                        });

                        // Also apply to currently visible checkboxes
                        const checkboxes = document.querySelectorAll(`input[name="selected_${type}[]"]:checked`);
                        checkboxes.forEach(checkbox => {
                            const rabbitId = checkbox.value;
                            const priceInput = document.querySelector(
                                `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                            );
                            const indicator = document.getElementById(
                                `price-indicator-${category}-${rabbitId}`
                            );

                            if (priceInput && priceInput.value != globalPrice) {
                                priceInput.value = globalPrice;
                                priceInput.style.borderColor = 'var(--accent-green)';
                                if (indicator) {
                                    indicator.style.display = 'block';
                                }
                                delete customPrices[`${category}-${rabbitId}`];
                                count++;
                            }
                        });
                    });

                    calculateTotalAmount();

                    if (appliedCount > 0 || count > 0) {
                        showToast(`${appliedCount + count} prix mis à jour avec les prix globaux`, 'success');
                    } else {
                        showToast('Aucun lapin sélectionné', 'warning');
                    }
                }

                // ============================================
                // APPLY GLOBAL PRICES TO SPECIFIC CATEGORY
                // ============================================
                function applyGlobalPricesToSelected(category) {
                    const type = getTypeFromCategory(category);
                    const checkboxes = document.querySelectorAll(`input[name="selected_${type}[]"]:checked`);
                    let count = 0;

                    checkboxes.forEach(checkbox => {
                        const rabbitId = checkbox.value;
                        const priceInput = document.querySelector(
                            `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                        );
                        const indicator = document.getElementById(`price-indicator-${category}-${rabbitId}`);

                        if (priceInput) {
                            priceInput.value = globalPrices[category] || 0;
                            if (indicator) {
                                indicator.style.display = 'block';
                            }
                            delete customPrices[`${category}-${rabbitId}`];
                            count++;
                        }
                    });

                    if (count > 0) {
                        calculateTotalAmount();
                    }
                }

                // ============================================
                // SAVE GLOBAL PRICES AS DEFAULT
                // ============================================
                function saveGlobalPricesAsDefault() {
                    const prices = {
                        default_price_male: parseFloat(document.getElementById('globalPriceMale').value) || 0,
                        default_price_female: parseFloat(document.getElementById('globalPriceFemale').value) || 0,
                        default_price_lapereau: parseFloat(document.getElementById('globalPriceLapereau').value) || 0,
                    };

                    fetch('{{ route('settings.update') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(prices)
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else {
                                return {
                                    success: true
                                };
                            }
                        })
                        .then(data => {
                            showToast('Prix par défaut enregistrés avec succès!', 'success');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Erreur lors de l\'enregistrement', 'error');
                        });
                }

                // ============================================
                // TAB SWITCHING
                // ============================================
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove(
                        'active'));
                        this.classList.add('active');
                        document.getElementById(this.dataset.tab).classList.add('active');
                    });
                });

                // ============================================
                // SEARCH RABBITS (AJAX)
                // ============================================
                let searchTimeouts = {};

                function debouncedSearch(type, searchTerm) {
                    clearTimeout(searchTimeouts[type]);
                    searchTimeouts[type] = setTimeout(() => {
                        loadRabbits(type, 1, searchTerm);
                    }, 300);
                }

                function loadRabbits(type, page = 1, search = '') {
                    const gridId = type + 'Grid';
                    const grid = document.getElementById(gridId);
                    const paginationInfo = document.getElementById(type + 'PaginationInfo');

                    if (!grid) return;

                    grid.style.opacity = '0.5';
                    grid.style.pointerEvents = 'none';

                    fetch('{{ route('sales.load-rabbits') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                type: type,
                                page: page,
                                search: search
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                grid.innerHTML = data.html;

                                if (paginationInfo) {
                                    const totalCount = data.total_count || data.pagination.total;
                                    paginationInfo.innerHTML =
                                        `Page ${data.pagination.current_page} sur ${data.pagination.last_page} (${totalCount} ${type} au total)`;
                                }

                                // Update tab count with filtered results
                                const countEl = document.getElementById(type + 'Count');
                                if (countEl && data.total_count !== undefined) {
                                    countEl.textContent = data.total_count;
                                }

                                // Re-initialize price inputs
                                initializePriceInputs(type);

                                // Restore selected state
                                restoreSelectedRabbits(type);
                            }
                        })
                        .catch(error => console.error('Error:', error))
                        .finally(() => {
                            grid.style.opacity = '1';
                            grid.style.pointerEvents = 'auto';
                        });
                }

                // ============================================
                // RESTORE SELECTED STATE AFTER AJAX
                // ============================================
                function restoreSelectedRabbits(type) {
                    selectedRabbits[type].forEach(rabbitId => {
                        const checkbox = document.querySelector(
                            `input[name="selected_${type}[]"][value="${rabbitId}"]`
                        );
                        if (checkbox && !checkbox.checked) {
                            checkbox.checked = true;
                            const category = getCategoryFromType(type);
                            handleRabbitSelection(type, rabbitId);
                        }
                    });
                }

                // ============================================
                // INITIALIZE PRICE INPUTS
                // ============================================
                function initializePriceInputs(type) {
                    const category = getCategoryFromType(type);
                    const checkboxes = document.querySelectorAll(`input[name="selected_${type}[]"]`);

                    checkboxes.forEach(checkbox => {
                        const rabbitId = checkbox.value;
                        const priceContainer = document.getElementById(`price-${category}-${rabbitId}`);
                        const priceInput = priceContainer?.querySelector('.rabbit-price');

                        if (checkbox.checked && priceInput) {
                            const customKey = `${category}-${rabbitId}`;
                            if (customPrices[customKey] !== undefined) {
                                priceInput.value = customPrices[customKey];
                            } else {
                                priceInput.value = globalPrices[category] || 0;
                            }
                            priceContainer.style.display = 'block';
                        }
                    });
                }

                // ============================================
                // TOGGLE SELECT ALL
                // ============================================
                function toggleSelectAll(type, select = true) {
                    const category = getCategoryFromType(type);
                    const grid = document.getElementById(type + 'Grid');
                    const checkboxes = grid.querySelectorAll('.rabbit-checkbox');

                    checkboxes.forEach(checkbox => {
                        // Only toggle visible cards
                        if (checkbox.closest('.rabbit-card').style.display !== 'none') {
                            checkbox.checked = select;
                            const rabbitId = checkbox.value;

                            if (select) {
                                selectedRabbits[type].add(rabbitId);
                            } else {
                                selectedRabbits[type].delete(rabbitId);
                                delete customPrices[`${category}-${rabbitId}`];
                            }

                            handleRabbitSelection(type, rabbitId);
                        }
                    });

                    calculateTotalAmount();
                }

                // ============================================
                // LOAD MORE RABBITS (AJAX Pagination)
                // ============================================
                function loadMoreRabbits(button) {
                    const type = button.dataset.type;
                    const page = parseInt(button.dataset.page);
                    const grid = document.getElementById(type + 'Grid');
                    const paginationInfo = document.getElementById(type + 'PaginationInfo');

                    if (!grid) return;

                    button.disabled = true;
                    button.innerHTML = '<i class="bi bi-hourglass-split"></i> Chargement...';

                    fetch('{{ route('sales.load-rabbits') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                type: type,
                                page: page,
                                search: document.getElementById('search' + type.charAt(0).toUpperCase() + type
                                    .slice(1)).value || ''
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Append new cards to grid
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = data.html;

                                tempDiv.querySelectorAll('.rabbit-card').forEach(card => {
                                    grid.appendChild(card);
                                });

                                // Update pagination info
                                if (paginationInfo) {
                                    const totalCount = data.total_count || data.pagination.total;
                                    paginationInfo.innerHTML =
                                        `Page ${data.pagination.current_page} sur ${data.pagination.last_page} (${totalCount} ${type} au total)`;
                                }

                                // Update load more button or remove if last page
                                const newLoadMoreBtn = tempDiv.querySelector('.load-more-btn');
                                const oldLoadMoreBtn = button;

                                if (newLoadMoreBtn) {
                                    oldLoadMoreBtn.replaceWith(newLoadMoreBtn);
                                } else {
                                    oldLoadMoreBtn.remove();
                                }

                                // Re-initialize price inputs for new elements
                                initializePriceInputs(type);

                                // Restore selected state for newly loaded rabbits
                                restoreSelectedRabbits(type);
                            }
                        })
                        .catch(error => {
                            console.error('Error loading more rabbits:', error);
                            showToast('Erreur lors du chargement', 'error');
                        })
                        .finally(() => {
                            button.disabled = false;
                            if (!button.classList.contains('load-more-btn')) {
                                const newBtn = document.querySelector(`.load-more-btn[data-type="${type}"]`);
                                if (newBtn) {
                                    newBtn.disabled = false;
                                    newBtn.innerHTML = '<i class="bi bi-plus-lg"></i> Charger plus';
                                }
                            } else {
                                button.innerHTML = '<i class="bi bi-plus-lg"></i> Charger plus';
                            }
                        });
                }

                // ============================================
                // CALCULATE TOTAL AMOUNT
                // ============================================
                function calculateTotalAmount() {
                    let total = 0;
                    let selectedCount = 0;
                    let missingPrices = 0;

                    // Sum all checked rabbit prices
                    document.querySelectorAll('.rabbit-checkbox:checked').forEach(checkbox => {
                        selectedCount++;
                        const card = checkbox.closest('.rabbit-card');
                        const priceInput = card.querySelector('.rabbit-price');

                        if (priceInput && priceInput.value) {
                            total += parseFloat(priceInput.value) || 0;
                        } else if (checkbox.checked) {
                            missingPrices++;
                        }
                    });

                    // Update counts by category
                    const males = document.querySelectorAll('input[name="selected_males[]"]:checked').length;
                    const females = document.querySelectorAll('input[name="selected_females[]"]:checked').length;
                    const lapereaux = document.querySelectorAll('input[name="selected_lapereaux[]"]:checked').length;

                    document.getElementById('selectedMalesCount').textContent = males;
                    document.getElementById('selectedFemalesCount').textContent = females;
                    document.getElementById('selectedLapereauxCount').textContent = lapereaux;
                    document.getElementById('selectedSummary').textContent = selectedCount + ' lapin(s) sélectionné(s)';

                    // Update total display
                    document.getElementById('totalAmountDisplay').textContent = total.toLocaleString('fr-FR', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }) + ' FCFA';

                    // Show warning if prices are missing
                    const warningDiv = document.getElementById('quantityMismatchWarning');
                    const submitBtn = document.getElementById('submitBtn');

                    if (missingPrices > 0 && selectedCount > 0) {
                        warningDiv.style.display = 'block';
                        warningDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle" style="color: var(--accent-orange);"></i>
                <span style="color: var(--accent-orange); font-weight: 600;">
                    Veuillez entrer un prix pour chaque lapin sélectionné (${missingPrices} prix manquants)!
                </span>
            `;
                        submitBtn.disabled = true;
                    } else {
                        warningDiv.style.display = 'none';
                        submitBtn.disabled = selectedCount === 0;
                    }
                }

                // ============================================
                // FORM SUBMISSION VALIDATION
                // ============================================
                document.getElementById('saleForm')?.addEventListener('submit', function(e) {
                    const selectedCount = document.querySelectorAll('.rabbit-checkbox:checked').length;
                    let missingPrices = 0;

                    document.querySelectorAll('.rabbit-checkbox:checked').forEach(checkbox => {
                        const card = checkbox.closest('.rabbit-card');
                        const priceInput = card.querySelector('.rabbit-price');

                        if (!priceInput || !priceInput.value || parseFloat(priceInput.value) <= 0) {
                            missingPrices++;
                        }
                    });

                    if (selectedCount === 0) {
                        e.preventDefault();
                        showToast('⚠️ Veuillez sélectionner au moins un lapin à vendre.', 'error');
                        return;
                    }

                    if (missingPrices > 0) {
                        e.preventDefault();
                        showToast(
                            `⚠️ ${missingPrices} prix manquants! Cliquez sur "Appliquer à TOUS les sélectionnés"`,
                            'error'
                        );
                        return;
                    }
                });

                // ============================================
                // TOAST NOTIFICATION
                // ============================================
                function showToast(message, type = 'info') {
                    const toast = document.createElement('div');
                    toast.style.cssText = `
            position: fixed;
            bottom: 100px;
            right: 30px;
            background: var(--surface);
            border: 1px solid var(--surface-border);
            border-left: 4px solid ${type === 'success' ? 'var(--accent-green)' : (type === 'error' ? 'var(--accent-red)' : 'var(--primary)')};
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

                    const icon = type === 'success' ? 'check-circle-fill' : (type === 'error' ? 'x-circle-fill' :
                        'info-circle-fill');
                    const color = type === 'success' ? 'var(--accent-green)' : (type === 'error' ? 'var(--accent-red)' :
                        'var(--primary)');

                    toast.innerHTML = `
            <i class="bi bi-${icon}" style="color: ${color}; font-size: 20px;"></i>
            <span style="color: var(--text-primary); font-size: 14px; font-weight: 500;">${message}</span>
        `;

                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.style.animation = 'slideOutRight 0.3s ease';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                // Add animation styles if not already present
                if (!document.getElementById('cuniapp-animations-style')) {
                    const style = document.createElement('style');
                    style.id = 'cuniapp-animations-style';
                    style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .rabbit-card:hover {
                border-color: var(--primary);
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }
        `;
                    document.head.appendChild(style);
                }

                // ============================================
                // INITIALIZE ON PAGE LOAD
                // ============================================
                window.addEventListener('DOMContentLoaded', () => {
                    // Initialize counts
                    ['males', 'females', 'lapereaux'].forEach(type => {
                        initializePriceInputs(type);
                    });

                    // Disable submit button initially
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                    }

                    // Show welcome toast
                    showToast('💡 Astuce: Définissez vos prix globaux pour gagner du temps!', 'info');

                    // Calculate initial total
                    calculateTotalAmount();
                });

                // ============================================
                // EXPOSE FUNCTIONS TO GLOBAL SCOPE
                // ============================================
                window.toggleSelectAll = toggleSelectAll;
                window.debouncedSearch = debouncedSearch;
                window.handleRabbitSelection = handleRabbitSelection;
                window.calculateTotalAmount = calculateTotalAmount;
                window.markPriceAsCustom = markPriceAsCustom;
                window.applyGlobalPricesToAll = applyGlobalPricesToAll;
                window.applyGlobalPricesToSelected = applyGlobalPricesToSelected;
                window.saveGlobalPricesAsDefault = saveGlobalPricesAsDefault;
                window.showToast = showToast;
                window.loadRabbits = loadRabbits;
                window.initializePriceInputs = initializePriceInputs;
                window.restoreSelectedRabbits = restoreSelectedRabbits;
                window.loadMoreRabbits = loadMoreRabbits;
                window.getCategoryFromType = getCategoryFromType;
                window.getTypeFromCategory = getTypeFromCategory;
            })();
        </script>
    @endpush

    <style>
        .tabs-container {
            display: flex;
            gap: 4px;
            border-bottom: 1px solid var(--surface-border);
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: var(--primary-subtle);
        }

        .tab-content {
            display: none;
            padding: 16px 0;
        }

        .tab-content.active {
            display: block;
        }

        .rabbit-card:hover {
            border-color: var(--primary);
            background: var(--primary-subtle);
        }

        .rabbit-checkbox {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .rabbit-price:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-subtle) !important;
        }

        .global-price-card {
            transition: all 0.3s ease;
        }

        .global-price-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .global-price-input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-subtle) !important;
        }

        .price-indicator {
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
    </style>
@endsection
