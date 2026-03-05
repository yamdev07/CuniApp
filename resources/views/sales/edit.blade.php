{{-- resources/views/sales/edit.blade.php --}}
@extends('layouts.cuniapp')

@section('title', 'Modifier Vente - CuniApp Élevage')

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">
                <i class="bi bi-pencil-square"></i> Modifier la Vente #{{ $sale->id }}
            </h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Tableau de bord</a>
                <span>/</span>
                <a href="{{ route('sales.index') }}">Ventes</a>
                <span>/</span>
                <span>Modification</span>
            </div>
        </div>
        <a href="{{ route('sales.index') }}" class="btn-cuni sm secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
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
            <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="saleForm">
                @csrf
                @method('PUT')

                {{-- Section: Acheteur --}}
                <div class="form-section">
                    <h4 class="section-subtitle">
                        <i class="bi bi-person"></i> Informations acheteur
                    </h4>
                    <div class="settings-grid">
                        <div class="form-group">
                            <label class="form-label">Nom de l'acheteur *</label>
                            <input type="text" name="buyer_name" class="form-control"
                                value="{{ old('buyer_name', $sale->buyer_name) }}" required placeholder="Nom complet">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact</label>
                            <input type="text" name="buyer_contact" class="form-control"
                                value="{{ old('buyer_contact', $sale->buyer_contact) }}" placeholder="Téléphone ou email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Adresse</label>
                            <textarea name="buyer_address" class="form-control" rows="2" placeholder="Adresse complète">{{ old('buyer_address', $sale->buyer_address) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date de vente *</label>
                            <input type="date" name="date_sale" class="form-control"
                                value="{{ old('date_sale', $sale->date_sale->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>

                {{-- ✅ Global Price Settings Section --}}
                <div class="form-section"
                    style="margin-top: 24px; border: 2px solid var(--primary-subtle); background: linear-gradient(135deg, var(--primary-subtle) 0%, var(--surface-alt) 100%);">
                    <h4 class="section-subtitle">
                        <i class="bi bi-cash-coin" style="color: var(--accent-green);"></i> Prix Globaux par Catégorie
                    </h4>
                    <div class="alert-box info" style="margin-bottom: 16px;">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>💡 Astuce:</strong> Définissez un prix par défaut pour chaque catégorie.
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
                            <input type="number" id="globalPriceMales" class="form-control global-price-input"
                                data-category="males"
                                value="{{ old('global_price_males', \App\Models\Setting::get('default_price_male', 25000)) }}"
                                min="0" step="100" placeholder="25000"
                                style="font-weight: 600; font-size: 16px; border-color: #3B82F6;">
                            <small style="color: var(--text-tertiary); font-size: 11px; margin-top: 4px; display: block;">
                                Défaut:
                                {{ number_format(\App\Models\Setting::get('default_price_male', 25000), 0, ',', ' ') }}
                                FCFA
                            </small>
                        </div>
                        {{-- Global Price: Femelles --}}
                        <div class="global-price-card"
                            style="background: var(--surface); padding: 16px; border-radius: var(--radius-lg); border: 1px solid var(--surface-border);">
                            <label
                                style="font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; display: block;">
                                <i class="bi bi-arrow-down-right-square" style="color: #EC4899;"></i> Prix Femelles (FCFA)
                            </label>
                            <input type="number" id="globalPriceFemales" class="form-control global-price-input"
                                data-category="females"
                                value="{{ old('global_price_females', \App\Models\Setting::get('default_price_female', 30000)) }}"
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
                            <input type="number" id="globalPriceLapereaux" class="form-control global-price-input"
                                data-category="lapereaux"
                                value="{{ old('global_price_lapereaux', \App\Models\Setting::get('default_price_lapereau', 15000)) }}"
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
                        <i class="bi bi-collection"></i> Lapins Vendus ({{ $sale->rabbits->count() }})
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
                            <i class="bi bi-arrow-up-right-square"></i> Mâles (<span id="malesCount">0</span>)
                        </button>
                        <button type="button" class="tab-btn" data-tab="females-tab">
                            <i class="bi bi-arrow-down-right-square"></i> Femelles (<span id="femalesCount">0</span>)
                        </button>
                        <button type="button" class="tab-btn" data-tab="lapereaux-tab">
                            <i class="bi bi-egg-fill"></i> Lapereaux (<span id="lapereauxCount">0</span>)
                        </button>
                    </div>

                    {{-- ✅ Mâles Tab with Individual Prices --}}
                    <div class="tab-content active" id="males-tab">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            <input type="text" class="form-control" placeholder="Rechercher un mâle..."
                                id="searchMales" style="flex: 1; min-width: 250px;"
                                onkeyup="filterRabbits('males', this.value)">
                            <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('males')">
                                <i class="bi bi-check-square"></i> Tout sélectionner
                            </button>
                            <button type="button" class="btn-cuni secondary" onclick="toggleSelectAll('males', false)">
                                <i class="bi bi-square"></i> Tout déselectionner
                            </button>
                        </div>
                        <div class="rabbit-selection-grid" id="malesGrid"
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 12px;">
                            @php
                                $soldMaleIds = $sale
                                    ->rabbits()
                                    ->where('rabbit_type', 'male')
                                    ->pluck('rabbit_id')
                                    ->toArray();
                            @endphp
                            @foreach ($males as $male)
                                <label class="rabbit-card"
                                    style="display: flex; flex-direction: column; gap: 8px; padding: 12px; background: var(--surface-alt); border: 1px solid var(--surface-border); border-radius: var(--radius); cursor: pointer; transition: all 0.2s ease;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <input type="checkbox" name="selected_males[]" value="{{ $male->id }}"
                                            class="rabbit-checkbox" data-category="males"
                                            data-code="{{ $male->code }}" data-name="{{ $male->nom }}"
                                            {{ in_array($male->id, $soldMaleIds) ? 'checked' : '' }}
                                            onchange="handleRabbitSelection('males', {{ $male->id }})">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600;">{{ $male->nom }}</div>
                                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                                {{ $male->code }} • {{ $male->race ?? 'Non spécifié' }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ✅ Individual Price Input with Global Indicator --}}
                                    <div class="price-input-container" id="price-males-{{ $male->id }}"
                                        style="display: {{ in_array($male->id, $soldMaleIds) ? 'block' : 'none' }}; margin-top: 8px;">
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                            <label style="font-size: 11px; color: var(--text-secondary); flex: 1;">Prix
                                                individuel (FCFA)</label>
                                            <button type="button" class="btn-reset-price"
                                                onclick="resetToGlobalPrice('males', {{ $male->id }})"
                                                title="Réinitialiser au prix global"
                                                style="background: var(--primary-subtle); border: none; border-radius: 4px; padding: 4px 8px; font-size: 10px; color: var(--primary); cursor: pointer;">
                                                <i class="bi bi-arrow-counterclockwise"></i> Prix global
                                            </button>
                                        </div>
                                        @php
                                            $saleRabbit = $sale
                                                ->rabbits()
                                                ->where('rabbit_type', 'male')
                                                ->where('rabbit_id', $male->id)
                                                ->first();
                                            $existingPrice = $saleRabbit?->sale_price ?? 0;
                                        @endphp
                                        <input type="number" name="male_prices[]" class="form-control rabbit-price"
                                            data-category="males" data-rabbit-id="{{ $male->id }}"
                                            value="{{ old('male_prices.' . $loop->index, $existingPrice) }}"
                                            placeholder="0" min="0" step="100"
                                            onchange="calculateTotalAmount(); markPriceAsCustom('males', {{ $male->id }})"
                                            style="padding: 8px; font-size: 13px;">
                                        <div class="price-indicator" id="price-indicator-males-{{ $male->id }}"
                                            style="font-size: 10px; color: var(--accent-green); margin-top: 4px; display: none;">
                                            <i class="bi bi-check-circle"></i> Prix global appliqué
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ✅ Femelles Tab with Individual Prices --}}
                    <div class="tab-content" id="females-tab">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            <input type="text" class="form-control" placeholder="Rechercher une femelle..."
                                id="searchFemales" style="flex: 1; min-width: 250px;"
                                onkeyup="filterRabbits('females', this.value)">
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
                            @php
                                $soldFemaleIds = $sale
                                    ->rabbits()
                                    ->where('rabbit_type', 'female')
                                    ->pluck('rabbit_id')
                                    ->toArray();
                            @endphp
                            @foreach ($femelles as $femelle)
                                <label class="rabbit-card"
                                    style="display: flex; flex-direction: column; gap: 8px; padding: 12px; background: var(--surface-alt); border: 1px solid var(--surface-border); border-radius: var(--radius); cursor: pointer; transition: all 0.2s ease;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <input type="checkbox" name="selected_females[]" value="{{ $femelle->id }}"
                                            class="rabbit-checkbox" data-category="females"
                                            data-code="{{ $femelle->code }}" data-name="{{ $femelle->nom }}"
                                            {{ in_array($femelle->id, $soldFemaleIds) ? 'checked' : '' }}
                                            onchange="handleRabbitSelection('females', {{ $femelle->id }})">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600;">{{ $femelle->nom }}</div>
                                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                                {{ $femelle->code }} • {{ $femelle->race ?? 'Non spécifié' }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ✅ Individual Price Input with Global Indicator --}}
                                    <div class="price-input-container" id="price-females-{{ $femelle->id }}"
                                        style="display: {{ in_array($femelle->id, $soldFemaleIds) ? 'block' : 'none' }}; margin-top: 8px;">
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                            <label style="font-size: 11px; color: var(--text-secondary); flex: 1;">Prix
                                                individuel (FCFA)</label>
                                            <button type="button" class="btn-reset-price"
                                                onclick="resetToGlobalPrice('females', {{ $femelle->id }})"
                                                title="Réinitialiser au prix global"
                                                style="background: var(--primary-subtle); border: none; border-radius: 4px; padding: 4px 8px; font-size: 10px; color: var(--primary); cursor: pointer;">
                                                <i class="bi bi-arrow-counterclockwise"></i> Prix global
                                            </button>
                                        </div>
                                        @php
                                            $saleRabbit = $sale
                                                ->rabbits()
                                                ->where('rabbit_type', 'female')
                                                ->where('rabbit_id', $femelle->id)
                                                ->first();
                                            $existingPrice = $saleRabbit?->sale_price ?? 0;
                                        @endphp
                                        <input type="number" name="female_prices[]" class="form-control rabbit-price"
                                            data-category="females" data-rabbit-id="{{ $femelle->id }}"
                                            value="{{ old('female_prices.' . $loop->index, $existingPrice) }}"
                                            placeholder="0" min="0" step="100"
                                            onchange="calculateTotalAmount(); markPriceAsCustom('females', {{ $femelle->id }})"
                                            style="padding: 8px; font-size: 13px;">
                                        <div class="price-indicator" id="price-indicator-females-{{ $femelle->id }}"
                                            style="font-size: 10px; color: var(--accent-green); margin-top: 4px; display: none;">
                                            <i class="bi bi-check-circle"></i> Prix global appliqué
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ✅ Lapereaux Tab with Individual Prices --}}
                    <div class="tab-content" id="lapereaux-tab">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            <input type="text" class="form-control" placeholder="Rechercher un lapereau..."
                                id="searchLapereaux" style="flex: 1; min-width: 250px;"
                                onkeyup="filterRabbits('lapereaux', this.value)">
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
                            @php
                                $soldLapereauIds = $sale
                                    ->rabbits()
                                    ->where('rabbit_type', 'lapereau')
                                    ->pluck('rabbit_id')
                                    ->toArray();
                            @endphp
                            @foreach ($lapereaux as $lapereau)
                                <label class="rabbit-card"
                                    style="display: flex; flex-direction: column; gap: 8px; padding: 12px; background: var(--surface-alt); border: 1px solid var(--surface-border); border-radius: var(--radius); cursor: pointer; transition: all 0.2s ease;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <input type="checkbox" name="selected_lapereaux[]" value="{{ $lapereau->id }}"
                                            class="rabbit-checkbox" data-category="lapereaux"
                                            data-code="{{ $lapereau->code }}"
                                            data-name="{{ $lapereau->nom ?? 'Sans nom' }}"
                                            {{ in_array($lapereau->id, $soldLapereauIds) ? 'checked' : '' }}
                                            onchange="handleRabbitSelection('lapereaux', {{ $lapereau->id }})">
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600;">{{ $lapereau->nom ?? 'Sans nom' }}</div>
                                            <div style="font-size: 12px; color: var(--text-tertiary);">
                                                {{ $lapereau->code }} •
                                                {{ $lapereau->naissance->miseBas->femelle->nom ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ✅ Individual Price Input with Global Indicator --}}
                                    <div class="price-input-container" id="price-lapereaux-{{ $lapereau->id }}"
                                        style="display: {{ in_array($lapereau->id, $soldLapereauIds) ? 'block' : 'none' }}; margin-top: 8px;">
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                            <label style="font-size: 11px; color: var(--text-secondary); flex: 1;">Prix
                                                individuel (FCFA)</label>
                                            <button type="button" class="btn-reset-price"
                                                onclick="resetToGlobalPrice('lapereaux', {{ $lapereau->id }})"
                                                title="Réinitialiser au prix global"
                                                style="background: var(--primary-subtle); border: none; border-radius: 4px; padding: 4px 8px; font-size: 10px; color: var(--primary); cursor: pointer;">
                                                <i class="bi bi-arrow-counterclockwise"></i> Prix global
                                            </button>
                                        </div>
                                        @php
                                            $saleRabbit = $sale
                                                ->rabbits()
                                                ->where('rabbit_type', 'lapereau')
                                                ->where('rabbit_id', $lapereau->id)
                                                ->first();
                                            $existingPrice = $saleRabbit?->sale_price ?? 0;
                                        @endphp
                                        <input type="number" name="lapereau_prices[]" class="form-control rabbit-price"
                                            data-category="lapereaux" data-rabbit-id="{{ $lapereau->id }}"
                                            value="{{ old('lapereau_prices.' . $loop->index, $existingPrice) }}"
                                            placeholder="0" min="0" step="100"
                                            onchange="calculateTotalAmount(); markPriceAsCustom('lapereaux', {{ $lapereau->id }})"
                                            style="padding: 8px; font-size: 13px;">
                                        <div class="price-indicator" id="price-indicator-lapereaux-{{ $lapereau->id }}"
                                            style="font-size: 10px; color: var(--accent-green); margin-top: 4px; display: none;">
                                            <i class="bi bi-check-circle"></i> Prix global appliqué
                                        </div>
                                    </div>
                                </label>
                            @endforeach
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
                                <span style="font-size: 14px; font-weight: 600; color: var(--text-primary);">
                                    💰 Total de la vente:
                                </span>
                                <span id="totalAmountDisplay"
                                    style="font-size: 20px; font-weight: 700; color: var(--primary);">
                                    0 FCFA
                                </span>
                            </div>
                        </div>
                        <div id="quantityMismatchWarning"
                            style="margin-top: 12px; padding: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: var(--radius); display: none;">
                            <i class="bi bi-exclamation-triangle" style="color: var(--accent-orange);"></i>
                            <span style="color: var(--accent-orange); font-weight: 600;">
                                Veuillez entrer un prix pour chaque lapin sélectionné!
                            </span>
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
                                <option value="paid" {{ $sale->payment_status === 'paid' ? 'selected' : '' }}>Payé
                                    intégralement</option>
                                <option value="partial" {{ $sale->payment_status === 'partial' ? 'selected' : '' }}>
                                    Paiement partiel</option>
                                <option value="pending" {{ $sale->payment_status === 'pending' ? 'selected' : '' }}>En
                                    attente</option>
                            </select>
                        </div>
                        <div class="form-group" id="partialPaymentGroup">
                            <label class="form-label">Montant versé (FCFA)</label>
                            <input type="number" step="0.01" name="amount_paid" class="form-control"
                                value="{{ old('amount_paid', $sale->amount_paid) }}" min="0">
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
                        <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires...">{{ old('notes', $sale->notes) }}</textarea>
                    </div>
                </div>

                <div
                    style="margin-top: 32px; display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid var(--surface-border);">
                    <button type="submit" class="btn-cuni primary" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Mettre à jour la vente
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
                    males: {{ \App\Models\Setting::get('default_price_male', 25000) }},
                    females: {{ \App\Models\Setting::get('default_price_female', 30000) }},
                    lapereaux: {{ \App\Models\Setting::get('default_price_lapereau', 15000) }}
                };

                // ✅ FIX: Initialize with existing sale prices
                let customPrices = {};

                // Initialize global price inputs
                document.querySelectorAll('.global-price-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const category = this.dataset.category;
                        globalPrices[category] = parseFloat(this.value) || 0;
                        this.style.borderColor = 'var(--accent-green)';
                        setTimeout(() => {
                            this.style.borderColor = '';
                        }, 1000);
                        if (document.getElementById('autoApplyGlobalPrice').checked) {
                            applyGlobalPricesToSelected(category);
                        }
                    });
                });

                function handleRabbitSelection(category, rabbitId) {
                    const checkbox = document.querySelector(
                        `input[name="selected_${category}[]"][value="${rabbitId}"]`
                    );
                    const priceContainer = document.getElementById(`price-${category}-${rabbitId}`);
                    const priceInput = priceContainer?.querySelector('.rabbit-price');
                    const indicator = document.getElementById(`price-indicator-${category}-${rabbitId}`);

                    if (checkbox && priceContainer) {
                        priceContainer.style.display = checkbox.checked ? 'block' : 'none';
                        if (checkbox.checked && priceInput) {
                            const customKey = `${category}-${rabbitId}`;
                            if (customPrices[customKey] !== undefined) {
                                priceInput.value = customPrices[customKey];
                                markPriceAsCustom(category, rabbitId);
                            } else {
                                priceInput.value = globalPrices[category] || 0;
                                if (indicator) {
                                    indicator.style.display = 'block';
                                }
                            }
                            priceInput.focus();
                            priceInput.classList.remove('error');
                        }
                    }
                    calculateTotalAmount();
                }

                function resetToGlobalPrice(category, rabbitId) {
                    const priceInput = document.querySelector(
                        `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                    );
                    const indicator = document.getElementById(`price-indicator-${category}-${rabbitId}`);
                    if (priceInput) {
                        priceInput.value = globalPrices[category];
                        priceInput.style.borderColor = 'var(--accent-green)';
                        priceInput.style.backgroundColor = 'var(--primary-subtle)';
                        if (indicator) {
                            indicator.style.display = 'block';
                        }
                        delete customPrices[`${category}-${rabbitId}`];
                        setTimeout(() => {
                            priceInput.style.borderColor = '';
                            priceInput.style.backgroundColor = '';
                        }, 1000);
                        calculateTotalAmount();
                        showToast('Prix réinitialisé au prix global', 'success');
                    }
                }

                function markPriceAsCustom(category, rabbitId) {
                    const priceInput = document.querySelector(
                        `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                    );
                    const indicator = document.getElementById(`price-indicator-${category}-${rabbitId}`);
                    if (priceInput) {
                        const currentValue = parseFloat(priceInput.value) || 0;
                        const globalValue = globalPrices[category];
                        if (currentValue !== globalValue) {
                            customPrices[`${category}-${rabbitId}`] = currentValue;
                            priceInput.style.borderColor = 'var(--accent-orange)';
                            priceInput.style.backgroundColor = 'rgba(245, 158, 11, 0.05)';
                            if (indicator) {
                                indicator.style.display = 'none';
                            }
                        } else {
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

                function applyGlobalPricesToAll() {
                    let count = 0;
                    ['males', 'females', 'lapereaux'].forEach(category => {
                        const checkboxes = document.querySelectorAll(
                            `input[name="selected_${category}[]"]:checked`
                        );
                        checkboxes.forEach(checkbox => {
                            const rabbitId = checkbox.value;
                            const priceInput = document.querySelector(
                                `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                            );
                            const indicator = document.getElementById(
                                `price-indicator-${category}-${rabbitId}`
                            );
                            if (priceInput) {
                                priceInput.value = globalPrices[category];
                                priceInput.style.borderColor = 'var(--accent-green)';
                                priceInput.style.backgroundColor = 'var(--primary-subtle)';
                                if (indicator) {
                                    indicator.style.display = 'block';
                                }
                                delete customPrices[`${category}-${rabbitId}`];
                                count++;
                            }
                        });
                    });
                    calculateTotalAmount();
                    if (count > 0) {
                        showToast(`${count} prix mis à jour avec les prix globaux`, 'success');
                    } else {
                        showToast('Aucun lapin sélectionné', 'info');
                    }
                }

                function applyGlobalPricesToSelected(category) {
                    const checkboxes = document.querySelectorAll(`input[name="selected_${category}[]"]:checked`);
                    let count = 0;
                    checkboxes.forEach(checkbox => {
                        const rabbitId = checkbox.value;
                        const priceInput = document.querySelector(
                            `.rabbit-price[data-category="${category}"][data-rabbit-id="${rabbitId}"]`
                        );
                        const indicator = document.getElementById(`price-indicator-${category}-${rabbitId}`);
                        if (priceInput) {
                            priceInput.value = globalPrices[category];
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

                function saveGlobalPricesAsDefault() {
                    const prices = {
                        default_price_male: parseFloat(document.getElementById('globalPriceMales').value) || 0,
                        default_price_female: parseFloat(document.getElementById('globalPriceFemales').value) || 0,
                        default_price_lapereau: parseFloat(document.getElementById('globalPriceLapereaux').value) || 0,
                    };
                    fetch('{{ route('settings.update') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(prices)
                        })
                        .then(response => response.json())
                        .then(data => {
                            showToast('Prix par défaut enregistrés avec succès!', 'success');
                        })
                        .catch(error => {
                            showToast('Erreur lors de l\'enregistrement', 'error');
                            console.error('Error:', error);
                        });
                }

                // ============================================
                // 1. TAB SWITCHING
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
                // 2. FILTER RABBITS BY SEARCH
                // ============================================
                function filterRabbits(category, searchTerm) {
                    const grid = document.getElementById(category + 'Grid');
                    const cards = grid.querySelectorAll('.rabbit-card');
                    let visibleCount = 0;
                    cards.forEach(card => {
                        const checkbox = card.querySelector('.rabbit-checkbox');
                        const code = checkbox.dataset.code.toLowerCase();
                        const name = checkbox.dataset.name.toLowerCase();
                        if (code.includes(searchTerm.toLowerCase()) || name.includes(searchTerm.toLowerCase())) {
                            card.style.display = 'flex';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    document.getElementById(category + 'Count').textContent = visibleCount;
                }

                // ============================================
                // 3. TOGGLE SELECT ALL
                // ============================================
                function toggleSelectAll(category, select = true) {
                    const grid = document.getElementById(category + 'Grid');
                    const checkboxes = grid.querySelectorAll('.rabbit-checkbox');
                    checkboxes.forEach(checkbox => {
                        if (checkbox.closest('.rabbit-card').style.display !== 'none') {
                            checkbox.checked = select;
                            const rabbitId = checkbox.value;
                            handleRabbitSelection(category, rabbitId);
                        }
                    });
                    calculateTotalAmount();
                }

                // ============================================
                // 5. CALCULATE TOTAL FROM INDIVIDUAL PRICES
                // ============================================
                function calculateTotalAmount() {
                    let total = 0;
                    let selectedCount = 0;
                    let missingPrices = 0;

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

                    const males = document.querySelectorAll('input[name="selected_males[]"]:checked').length;
                    const females = document.querySelectorAll('input[name="selected_females[]"]:checked').length;
                    const lapereaux = document.querySelectorAll('input[name="selected_lapereaux[]"]:checked').length;

                    document.getElementById('selectedMalesCount').textContent = males;
                    document.getElementById('selectedFemalesCount').textContent = females;
                    document.getElementById('selectedLapereauxCount').textContent = lapereaux;
                    document.getElementById('selectedSummary').textContent = selectedCount + ' lapin(s) sélectionné(s)';

                    document.getElementById('totalAmountDisplay').textContent = total.toLocaleString('fr-FR', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }) + ' FCFA';

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
                // 6. FORM SUBMISSION VALIDATION
                // ============================================
                document.getElementById('saleForm').addEventListener('submit', function(e) {
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
                        alert('⚠️ Veuillez sélectionner au moins un lapin à vendre.');
                        return;
                    }

                    if (missingPrices > 0) {
                        e.preventDefault();
                        alert(
                            `⚠️ Veuillez entrer un prix pour chaque lapin sélectionné (${missingPrices} prix manquants).`
                            );
                        return;
                    }

                    // ✅ Show loading state
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Traitement en cours...';
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

                // ✅ FIX: Correct variable name (was 'style', should be 'animationStyle')
                if (!document.getElementById('cuniapp-animations-style')) {
                    const animationStyle = document.createElement('style');
                    animationStyle.id = 'cuniapp-animations-style';
                    animationStyle.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            .btn-reset-price:hover {
                background: var(--primary) !important;
                color: white !important;
            }
            .rabbit-card:hover {
                border-color: var(--primary);
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }
        `;
                    document.head.appendChild(animationStyle); // ✅ FIXED
                }

                // ============================================
                // 7. INITIALIZE ON PAGE LOAD
                // ============================================
                window.addEventListener('DOMContentLoaded', () => {
                    filterRabbits('males', '');
                    filterRabbits('females', '');
                    filterRabbits('lapereaux', '');

                    // ✅ Calculate initial total from existing sale data
                    calculateTotalAmount();

                    // Initialize partial payment visibility
                    const paymentStatus = document.getElementById('paymentStatus');
                    const partialPaymentGroup = document.getElementById('partialPaymentGroup');
                    if (paymentStatus && partialPaymentGroup) {
                        if (paymentStatus.value === 'partial') {
                            partialPaymentGroup.style.display = 'block';
                        } else {
                            partialPaymentGroup.style.display = 'none';
                        }

                        paymentStatus.addEventListener('change', function() {
                            if (this.value === 'partial') {
                                partialPaymentGroup.style.display = 'block';
                            } else {
                                partialPaymentGroup.style.display = 'none';
                            }
                        });
                    }

                    showToast('💡 Vous pouvez modifier les prix individuellement', 'info');
                });
                // Add this BEFORE the closing })(); in your script
                window.handleRabbitSelection = handleRabbitSelection;
                window.toggleSelectAll = toggleSelectAll;
                window.filterRabbits = filterRabbits;
                window.calculateTotalAmount = calculateTotalAmount;
                window.markPriceAsCustom = markPriceAsCustom;
                window.resetToGlobalPrice = resetToGlobalPrice;
                window.applyGlobalPricesToAll = applyGlobalPricesToAll;
                window.applyGlobalPricesToSelected = applyGlobalPricesToSelected;
                window.saveGlobalPricesAsDefault = saveGlobalPricesAsDefault;
                window.showToast = showToast;
            })();
        </script>
    @endsection
