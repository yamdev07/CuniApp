{{-- resources/views/sales/partials/rabbit-grid.blade.php --}}
@forelse($rabbits as $rabbit)
    @php
        $isSold = in_array($rabbit->id, $soldIds ?? []);
        
        // ✅ MAPPING CORRECT : pluriel → singulier
        $categoryMap = [
            'males' => 'male',
            'females' => 'female',
            'lapereaux' => 'lapereau'
        ];
        $category = $categoryMap[$type] ?? rtrim($type, 's');
        
        $defaultPrice = \App\Models\Setting::get('default_price_'.$category, $category === 'male' ? 25000 : ($category === 'female' ? 30000 : 15000));
    @endphp

    <label class="rabbit-card" style="display: flex; flex-direction: column; gap: 8px; padding: 12px; background: var(--surface-alt); border: 1px solid var(--surface-border); border-radius: var(--radius); cursor: pointer;" onclick="event.stopPropagation();">
        
        {{-- Checkbox + Info Row --}}
        <div style="display: flex; align-items: center; gap: 12px;">
            <input type="checkbox" 
                   name="selected_{{ $type }}[]" 
                   value="{{ $rabbit->id }}" 
                   class="rabbit-checkbox" 
                   data-category="{{ $category }}" 
                   data-rabbit-id="{{ $rabbit->id }}"
                   {{ $isSold ? 'checked' : '' }} 
                   onchange="handleRabbitSelection('{{ $type }}', {{ $rabbit->id }})">
            <div style="flex: 1;">
                <div style="font-weight: 600;">{{ $rabbit->nom ?? 'Sans nom' }}</div>
                <div style="font-size: 12px; color: var(--text-tertiary);">
                    {{ $rabbit->code }} • {{ $rabbit->race ?? 'Non spécifié' }}
                </div>
            </div>
            
            {{-- ✅ BADGE D'ÉTAT - Couleur orange pour 'vendu' --}}
            @if($rabbit->etat === 'vendu')
                <span class="badge" 
                      style="background: rgba(245, 158, 11, 0.15); 
                             color: #F59E0B; 
                             border: 1px solid rgba(245, 158, 11, 0.3);
                             font-size: 11px; 
                             font-weight: 600;
                             padding: 4px 10px;
                             border-radius: 20px;">
                    <i class="bi bi-check-circle-fill" style="margin-right: 4px;"></i>
                    Vendu
                </span>
            @elseif($rabbit->etat === 'Active' || $rabbit->etat === 'vivant')
                <span class="badge" 
                      style="background: rgba(16, 185, 129, 0.15); 
                             color: #10B981; 
                             border: 1px solid rgba(16, 185, 129, 0.3);
                             font-size: 11px; 
                             font-weight: 600;
                             padding: 4px 10px;
                             border-radius: 20px;">
                    <i class="bi bi-check-circle" style="margin-right: 4px;"></i>
                    {{ $rabbit->etat === 'vivant' ? 'Vivant' : 'Actif' }}
                </span>
            @elseif($rabbit->etat === 'Inactive')
                <span class="badge" 
                      style="background: rgba(107, 114, 128, 0.15); 
                             color: #6B7280; 
                             border: 1px solid rgba(107, 114, 128, 0.3);
                             font-size: 11px; 
                             font-weight: 600;
                             padding: 4px 10px;
                             border-radius: 20px;">
                    <i class="bi bi-pause-circle" style="margin-right: 4px;"></i>
                    Inactif
                </span>
            @elseif($rabbit->etat === 'Malade')
                <span class="badge" 
                      style="background: rgba(239, 68, 68, 0.15); 
                             color: #EF4444; 
                             border: 1px solid rgba(239, 68, 68, 0.3);
                             font-size: 11px; 
                             font-weight: 600;
                             padding: 4px 10px;
                             border-radius: 20px;">
                    <i class="bi bi-exclamation-triangle" style="margin-right: 4px;"></i>
                    Malade
                </span>
            @elseif(in_array($rabbit->etat, ['Gestante', 'Allaitante', 'Vide']))
                <span class="badge" 
                      style="background: rgba(139, 92, 246, 0.15); 
                             color: #8B5CF6; 
                             border: 1px solid rgba(139, 92, 246, 0.3);
                             font-size: 11px; 
                             font-weight: 600;
                             padding: 4px 10px;
                             border-radius: 20px;">
                    <i class="bi bi-egg-fill" style="margin-right: 4px;"></i>
                    {{ $rabbit->etat }}
                </span>
            @endif
        </div>

        {{-- Price Input Container --}}
        <div class="price-input-container" id="price-{{ $category }}-{{ $rabbit->id }}" style="display: {{ $isSold ? 'block' : 'none' }}; margin-top: 8px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                <label style="font-size: 11px; color: var(--text-secondary); flex: 1;">
                    Prix (FCFA)
                </label>
                <button type="button" 
                        class="btn-reset-price" 
                        onclick="event.stopPropagation(); resetToGlobalPrice('{{ $category }}', {{ $rabbit->id }})" 
                        style="background: var(--primary-subtle); border: none; border-radius: 4px; padding: 4px 8px; font-size: 10px; color: var(--primary); cursor: pointer;">
                    <i class="bi bi-arrow-counterclockwise"></i> Global
                </button>
            </div>

            <input type="number" 
                   name="{{ $category }}_prices[]" 
                   class="form-control rabbit-price" 
                   data-category="{{ $category }}" 
                   data-rabbit-id="{{ $rabbit->id }}" 
                   placeholder="0" 
                   min="0" 
                   step="100" 
                   value="{{ $isSold ? (old($category.'_prices')[$loop->index] ?? $defaultPrice) : $defaultPrice }}" 
                   onchange="calculateTotalAmount(); markPriceAsCustom('{{ $category }}', {{ $rabbit->id }})" 
                   onclick="event.stopPropagation();" 
                   style="padding: 8px; font-size: 13px; width: 100%;">

            <div class="price-indicator" id="price-indicator-{{ $category }}-{{ $rabbit->id }}" style="font-size: 10px; color: var(--accent-green); margin-top: 4px; display: none;">
                <i class="bi bi-check-circle"></i> Prix global
            </div>
        </div>
    </label>
@empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-tertiary);">
        <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.5;"></i>
        <p>Aucun {{ $type }} trouvé</p>
    </div>
@endforelse