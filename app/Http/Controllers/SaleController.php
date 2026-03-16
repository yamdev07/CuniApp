<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleRabbit;
use App\Models\Male;
use App\Models\Femelle;
use App\Models\Lapereau;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    use Notifiable;

    public function index(Request $request)
    {
        // ✅ Start query with user isolation (Defense-in-Depth)
        $query = Sale::where('user_id', Auth::id())
            ->with('user');

        // ✅ TEXT SEARCH: Buyer name, notes, or transaction reference
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('buyer_name', 'LIKE', "%{$search}%")
                    ->orWhere('notes', 'LIKE', "%{$search}%")
                    ->orWhere('buyer_contact', 'LIKE', "%{$search}%");
            });
        }

        // ✅ PAYMENT STATUS FILTER
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // ✅ DATE RANGE FILTER: From
        if ($request->filled('date_from')) {
            $query->whereDate('date_sale', '>=', $request->date_from);
        }

        // ✅ DATE RANGE FILTER: To
        if ($request->filled('date_to')) {
            $query->whereDate('date_sale', '<=', $request->date_to);
        }

        // ✅ PAGINATION (15 per page)
        $sales = $query->latest('date_sale')->paginate(15)->withQueryString();

        // ✅ STATISTICS (for dashboard cards)
        $stats = [
            'total_sales' => Sale::where('user_id', Auth::id())->count(),
            'total_revenue' => Sale::where('user_id', Auth::id())
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'pending_payments' => Sale::where('user_id', Auth::id())
                ->where('payment_status', 'pending')
                ->sum('total_amount'),
            'this_month' => Sale::where('user_id', Auth::id())
                ->where('payment_status', 'paid')
                ->whereMonth('date_sale', now()->month)
                ->whereYear('date_sale', now()->year)
                ->sum('total_amount'),
            'deletable_sales' => Sale::where('user_id', Auth::id())
                ->where('date_sale', '<=', now()->subDays(60))
                ->count(),
        ];

        return view('sales.index', compact('sales', 'stats'));
    }

    /**
     * Show form for creating new sale
     */
    public function create()
    {
        //  MÂLES : Exclure UNIQUEMENT 'vendu' 
        // → Active, Inactive, Malade restent visibles pour la gestion
        $males = Male::where('etat', '!=', 'vendu')
            ->orderBy('nom')
            ->paginate(20, ['*'], 'males_page');

        //  FEMELLES : Exclure UNIQUEMENT 'vendu'
        // → Active, Gestante, Allaitante, Vide restent visibles pour le suivi d'élevage
        $femelles = Femelle::where('etat', '!=', 'vendu')
            ->orderBy('nom')
            ->paginate(20, ['*'], 'females_page');

        //  LAPEREAUX : Montrer UNIQUEMENT 'vivant'
        // → Exclut automatiquement 'vendu', 'mort', 'archivé'
        $lapereaux = Lapereau::where('etat', 'vivant')
            ->with('naissance.miseBas.femelle')
            ->orderBy('code')
            ->paginate(20, ['*'], 'lapereaux_page');

        //  Totaux pour l'affichage (mêmes filtres que ci-dessus)
        $totalCounts = [
            'males' => Male::where('etat', '!=', 'vendu')->count(),
            'females' => Femelle::where('etat', '!=', 'vendu')->count(),
            'lapereaux' => Lapereau::where('etat', 'vivant')->count(),
        ];

        return view('sales.create', compact('males', 'femelles', 'lapereaux', 'totalCounts'));
    }

    /**
     * Store a newly created sale with INDIVIDUAL pricing per rabbit
     */
    public function store(Request $request)
    {
        //  VALIDATION: Accept arrays for individual prices
        $validated = $request->validate([
            'date_sale' => 'required|date',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'nullable|string|max:100',
            'buyer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_status' => 'required|in:paid,pending,partial',
            'amount_paid' => 'nullable|numeric|min:0',

            //  Rabbit selections with individual prices
            'selected_males' => 'nullable|array',
            'selected_males.*' => 'exists:males,id',
            'male_prices' => 'nullable|array',
            'male_prices.*' => 'nullable|numeric|min:0',

            'selected_females' => 'nullable|array',
            'selected_females.*' => 'exists:femelles,id',
            'female_prices' => 'nullable|array',
            'female_prices.*' => 'nullable|numeric|min:0',

            'selected_lapereaux' => 'nullable|array',
            'selected_lapereaux.*' => 'exists:lapereaux,id',
            'lapereau_prices' => 'nullable|array',
            'lapereau_prices.*' => 'nullable|numeric|min:0',
        ], [
            'male_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'male_prices.*.min' => 'Le prix doit être supérieur à 0',
            'female_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'female_prices.*.min' => 'Le prix doit être supérieur à 0',
            'lapereau_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'lapereau_prices.*.min' => 'Le prix doit être supérieur à 0',
        ]);

        //  Get selected rabbits
        $selectedMales = $request->input('selected_males', []);
        $selectedFemales = $request->input('selected_females', []);
        $selectedLapereaux = $request->input('selected_lapereaux', []);

        $malePrices = $request->input('male_prices', []);
        $femalePrices = $request->input('female_prices', []);
        $lapereauPrices = $request->input('lapereau_prices', []);

        $totalQuantity = count($selectedMales) + count($selectedFemales) + count($selectedLapereaux);

        //  Validation: At least one rabbit must be selected
        if ($totalQuantity === 0) {
            return back()->withErrors([
                'rabbits' => 'Vous devez sélectionner au moins un lapin pour cette vente.'
            ])->withInput();
        }

        //  VALIDATION: Ensure all selected rabbits have prices > 0
        $missingPrices = [];

        foreach ($selectedMales as $index => $maleId) {
            $price = isset($malePrices[$index]) ? (float) $malePrices[$index] : null;
            if (empty($price) || $price <= 0) {
                $missingPrices[] = "Mâle #{$maleId}";
            }
        }

        foreach ($selectedFemales as $index => $femaleId) {
            $price = isset($femalePrices[$index]) ? (float) $femalePrices[$index] : null;
            if (empty($price) || $price <= 0) {
                $missingPrices[] = "Femelle #{$femaleId}";
            }
        }

        foreach ($selectedLapereaux as $index => $lapereauId) {
            $price = isset($lapereauPrices[$index]) ? (float) $lapereauPrices[$index] : null;
            if (empty($price) || $price <= 0) {
                $missingPrices[] = "Lapereau #{$lapereauId}";
            }
        }

        if (!empty($missingPrices)) {
            return back()->withErrors([
                'prices' => ' Prix manquants ou invalides pour: ' . implode(', ', array_slice($missingPrices, 0, 5)) . (count($missingPrices) > 5 ? ' et ' . (count($missingPrices) - 5) . ' autres...' : '')
            ])->withInput();
        }

        //  Calculate total amount from INDIVIDUAL prices
        $totalAmount = 0;
        foreach ($selectedMales as $index => $maleId) {
            $totalAmount += (float) ($malePrices[$index] ?? 0);
        }
        foreach ($selectedFemales as $index => $femaleId) {
            $totalAmount += (float) ($femalePrices[$index] ?? 0);
        }
        foreach ($selectedLapereaux as $index => $lapereauId) {
            $totalAmount += (float) ($lapereauPrices[$index] ?? 0);
        }

        $validated['total_amount'] = $totalAmount;
        $validated['quantity'] = $totalQuantity;
        $validated['user_id'] = Auth::id();
        $validated['unit_price'] = 0;

        // Set amount_paid based on payment status
        if ($validated['payment_status'] === 'paid') {
            $validated['amount_paid'] = $totalAmount;
        } elseif ($validated['payment_status'] === 'partial') {
            $validated['amount_paid'] = $validated['amount_paid'] ?? 0;
        } else {
            $validated['amount_paid'] = 0;
        }

        DB::beginTransaction();
        try {
            // Create sale
            $sale = Sale::create($validated);

            //  Link selected males with INDIVIDUAL prices
            foreach ($selectedMales as $index => $maleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'male',
                    'rabbit_id' => $maleId,
                    'sale_price' => $malePrices[$index] ?? 0,
                ]);
            }

            //  Link selected females with INDIVIDUAL prices
            foreach ($selectedFemales as $index => $femaleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'female',
                    'rabbit_id' => $femaleId,
                    'sale_price' => $femalePrices[$index] ?? 0,
                ]);
            }

            //  Link selected lapereaux with INDIVIDUAL prices
            foreach ($selectedLapereaux as $index => $lapereauId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'lapereau',
                    'rabbit_id' => $lapereauId,
                    'sale_price' => $lapereauPrices[$index] ?? 0,
                ]);
            }

            //  Update rabbit status to 'vendu' (CORRECTION PRINCIPALE)
            foreach ($selectedMales as $maleId) {
                Male::where('id', $maleId)->update(['etat' => 'vendu']); // ← 'vendu' au lieu de 'Inactive'
            }
            foreach ($selectedFemales as $femaleId) {
                Femelle::where('id', $femaleId)->update(['etat' => 'vendu']); // ← 'vendu' au lieu de 'Vide'
            }
            foreach ($selectedLapereaux as $lapereauId) {
                Lapereau::where('id', $lapereauId)->update(['etat' => 'vendu']); // ← Déjà correct
            }

            // Notification
            $this->notifyUser([
                'type' => $sale->payment_status === 'paid' ? 'success' : 'warning',
                'title' => '💰 Nouvelle Vente Enregistrée',
                'message' => "Vente #{$sale->id}: {$totalQuantity} lapin(s) à {$sale->buyer_name} pour " . number_format($sale->total_amount, 2, ',', ' ') . " FCFA",
                'action_url' => route('sales.show', $sale)
            ]);

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Vente enregistrée avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale, Request $request)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $sale->load(['user']);

        $rabbitsQuery = $sale->rabbits()->with('rabbit');

        if ($request->has('search_rabbit')) {
            $search = $request->search_rabbit;
            $rabbitsQuery->whereHas('rabbit', function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('filter_category')) {
            $rabbitsQuery->where('rabbit_type', $request->filter_category);
        }

        $rabbits = $rabbitsQuery->paginate(10)->withQueryString();

        return view('sales.show', compact('sale', 'rabbits'));
    }

    /**
     * Show form for editing sale
     */
    public function edit(Sale $sale)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette vente');
        }

        $sale->load(['rabbits.rabbit']);

        $currentSaleRabbitIds = $sale->rabbits->pluck('rabbit_id')->toArray();

        //  MÂLES : Disponibles OU déjà dans cette vente
        $males = Male::where(function ($q) use ($currentSaleRabbitIds) {
            $q->where('etat', '!=', 'vendu')
                ->orWhereIn('id', $currentSaleRabbitIds);
        })
            ->orderBy('nom')
            ->paginate(20, ['*'], 'males_page');

        //  FEMELLES : Disponibles OU déjà dans cette vente
        $femelles = Femelle::where(function ($q) use ($currentSaleRabbitIds) {
            $q->where('etat', '!=', 'vendu')
                ->orWhereIn('id', $currentSaleRabbitIds);
        })
            ->orderBy('nom')
            ->paginate(20, ['*'], 'females_page');

        //  LAPEREAUX : Vivants OU déjà dans cette vente
        $lapereaux = Lapereau::where(function ($q) use ($currentSaleRabbitIds) {
            $q->where('etat', 'vivant')
                ->orWhereIn('id', $currentSaleRabbitIds);
        })
            ->with('naissance.miseBas.femelle')
            ->orderBy('code')
            ->paginate(20, ['*'], 'lapereaux_page');

        return view('sales.edit', compact(
            'sale',
            'males',
            'femelles',
            'lapereaux',
            'currentSaleRabbitIds'
        ));
    }

    /**
     * Update the specified sale
     */
    public function update(Request $request, Sale $sale)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette vente');
        }

        $validated = $request->validate([
            'date_sale' => 'required|date',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'nullable|string|max:100',
            'buyer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_status' => 'required|in:paid,pending,partial',
            'amount_paid' => 'nullable|numeric|min:0',

            'selected_males' => 'nullable|array',
            'selected_males.*' => 'exists:males,id',
            'male_prices' => 'nullable|array',
            'male_prices.*' => 'nullable|numeric|min:0',

            'selected_females' => 'nullable|array',
            'selected_females.*' => 'exists:femelles,id',
            'female_prices' => 'nullable|array',
            'female_prices.*' => 'nullable|numeric|min:0',

            'selected_lapereaux' => 'nullable|array',
            'selected_lapereaux.*' => 'exists:lapereaux,id',
            'lapereau_prices' => 'nullable|array',
            'lapereau_prices.*' => 'nullable|numeric|min:0',
        ], [
            'male_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'female_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'lapereau_prices.*.numeric' => 'Le prix doit être un nombre valide',
        ]);

        $selectedMales = $request->input('selected_males', []);
        $selectedFemales = $request->input('selected_females', []);
        $selectedLapereaux = $request->input('selected_lapereaux', []);
        $malePrices = $request->input('male_prices', []);
        $femalePrices = $request->input('female_prices', []);
        $lapereauPrices = $request->input('lapereau_prices', []);

        $totalQuantity = count($selectedMales) + count($selectedFemales) + count($selectedLapereaux);

        if ($totalQuantity === 0) {
            return back()->withErrors([
                'rabbits' => 'Vous devez sélectionner au moins un lapin pour cette vente.'
            ])->withInput();
        }

        $missingPrices = [];
        foreach ($selectedMales as $index => $maleId) {
            $price = isset($malePrices[$index]) ? (float) $malePrices[$index] : null;
            if (empty($price) || $price <= 0) {
                $missingPrices[] = "Mâle #{$maleId}";
            }
        }
        foreach ($selectedFemales as $index => $femaleId) {
            $price = isset($femalePrices[$index]) ? (float) $femalePrices[$index] : null;
            if (empty($price) || $price <= 0) {
                $missingPrices[] = "Femelle #{$femaleId}";
            }
        }
        foreach ($selectedLapereaux as $index => $lapereauId) {
            $price = isset($lapereauPrices[$index]) ? (float) $lapereauPrices[$index] : null;
            if (empty($price) || $price <= 0) {
                $missingPrices[] = "Lapereau #{$lapereauId}";
            }
        }

        if (!empty($missingPrices)) {
            return back()->withErrors([
                'prices' => ' Prix manquants ou invalides pour: ' . implode(', ', array_slice($missingPrices, 0, 5))
            ])->withInput();
        }

        $totalAmount = 0;
        foreach ($selectedMales as $index => $maleId) {
            $totalAmount += (float) ($malePrices[$index] ?? 0);
        }
        foreach ($selectedFemales as $index => $femaleId) {
            $totalAmount += (float) ($femalePrices[$index] ?? 0);
        }
        foreach ($selectedLapereaux as $index => $lapereauId) {
            $totalAmount += (float) ($lapereauPrices[$index] ?? 0);
        }

        $validated['total_amount'] = $totalAmount;
        $validated['quantity'] = $totalQuantity;

        if ($validated['payment_status'] === 'paid') {
            $validated['amount_paid'] = $totalAmount;
        } elseif ($validated['payment_status'] === 'partial') {
            $validated['amount_paid'] = $validated['amount_paid'] ?? $sale->amount_paid;
        } else {
            $validated['amount_paid'] = 0;
        }

        DB::beginTransaction();
        try {
            $statusChanged = ($sale->payment_status !== $validated['payment_status']);
            $oldStatus = $sale->payment_status;
            $newStatus = $validated['payment_status'];

            $sale->update($validated);
            $sale->rabbits()->delete();

            //  Link selected males with INDIVIDUAL prices + mark as 'vendu'
            foreach ($selectedMales as $index => $maleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'male',
                    'rabbit_id' => $maleId,
                    'sale_price' => $malePrices[$index] ?? 0,
                ]);
                Male::where('id', $maleId)->update(['etat' => 'vendu']);
            }

            //  Link selected females with INDIVIDUAL prices + mark as 'vendu'
            foreach ($selectedFemales as $index => $femaleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'female',
                    'rabbit_id' => $femaleId,
                    'sale_price' => $femalePrices[$index] ?? 0,
                ]);
                Femelle::where('id', $femaleId)->update(['etat' => 'vendu']); // ← CORRECTION
            }

            //  Link selected lapereaux with INDIVIDUAL prices + mark as 'vendu'
            foreach ($selectedLapereaux as $index => $lapereauId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'lapereau',
                    'rabbit_id' => $lapereauId,
                    'sale_price' => $lapereauPrices[$index] ?? 0,
                ]);
                Lapereau::where('id', $lapereauId)->update(['etat' => 'vendu']);
            }

            $this->notifyUser([
                'type' => 'info',
                'title' => ' Vente Modifiée',
                'message' => "Vente #{$sale->id} mise à jour: {$totalQuantity} lapin(s) - " . number_format($sale->total_amount, 2, ',', ' ') . " FCFA",
                'action_url' => route('sales.show', $sale)
            ]);

            if ($statusChanged) {
                $statusLabels = [
                    'paid' => ' Payé',
                    'pending' => ' En attente',
                    'partial' => ' Paiement partiel'
                ];
                $this->notifyUser([
                    'type' => $newStatus === 'paid' ? 'success' : ($newStatus === 'partial' ? 'info' : 'warning'),
                    'title' => '💳 Statut de Paiement Mis à Jour',
                    'message' => "Vente #{$sale->id}: {$statusLabels[$oldStatus]} → {$statusLabels[$newStatus]}",
                    'action_url' => route('sales.show', $sale)
                ]);
            }

            DB::commit();
            return redirect()->route('sales.index')
                ->with('success', 'Vente mise à jour avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified sale (only after 60 days)
     *  LES LAPINS SONT SUPPRIMÉS DÉFINITIVEMENT (pas de restauration)
     */


    public function destroy(Sale $sale)
    {


        // ✅ SECURITY FIX: Explicit Ownership Check (Was missing in provided code)
        if ($sale->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to this record.');
        }

        // Vérification : Vente doit avoir plus de 60 jours
        $daysSinceSale = now()->diffInDays($sale->date_sale);

        if ($daysSinceSale < 60) {
            return back()->with(
                'warning',
                "⚠️ Vous ne pouvez supprimer cette vente qu'après 60 jours. 
            Il reste " . (60 - $daysSinceSale) . " jours d'attente."
            );
        }

        // SUPPRIMER DÉFINITIVEMENT les lapins associés
        foreach ($sale->rabbits as $saleRabbit) {
            if ($saleRabbit->rabbit_type === 'male') {
                Male::where('id', $saleRabbit->rabbit_id)->delete();
            } elseif ($saleRabbit->rabbit_type === 'female') {
                Femelle::where('id', $saleRabbit->rabbit_id)->delete();
            } elseif ($saleRabbit->rabbit_type === 'lapereau') {
                Lapereau::where('id', $saleRabbit->rabbit_id)->delete();
            }
        }

        $typeLabel = $this->getTypeLabel('groupe');
        $saleInfo = "{$sale->quantity} {$typeLabel} à {$sale->buyer_name} pour " .
            number_format($sale->total_amount, 2, ',', ' ') . " FCFA";

        $sale->delete();

        // Notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Vente Supprimée',
            'message' => "Vente #{$sale->id} supprimée: {$saleInfo} (lapins également supprimés)",
            'action_url' => route('sales.index')
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Vente et lapins associés supprimés définitivement !');
    }

    /**
     * Vérifie si une vente peut être supprimée (après 60 jours)
     */
    public function canBeDeleted(Sale $sale): bool
    {
        return now()->diffInDays($sale->date_sale) >= 60;
    }

    /**
     * Get remaining days before deletion is allowed
     */
    public function getRemainingDays(Sale $sale): int
    {
        $daysSinceSale = now()->diffInDays($sale->date_sale);
        return max(0, 60 - $daysSinceSale);
    }
    /**
     * Mark sale as paid
     */
    public function markAsPaid(Sale $sale)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette vente');
        }

        if ($sale->payment_status === 'paid') {
            return back()->with('warning', 'Cette vente est déjà payée !');
        }

        $sale->update([
            'payment_status' => 'paid',
            'amount_paid' => $sale->total_amount,
        ]);

        // Update rabbit statuses to 'vendu'
        foreach ($sale->rabbits as $saleRabbit) {
            if ($saleRabbit->rabbit_type === 'male') {
                \App\Models\Male::where('id', $saleRabbit->rabbit_id)
                    ->update(['etat' => 'vendu']); // ← CORRECTION
            } elseif ($saleRabbit->rabbit_type === 'female') {
                \App\Models\Femelle::where('id', $saleRabbit->rabbit_id)
                    ->update(['etat' => 'vendu']); // ← CORRECTION
            } elseif ($saleRabbit->rabbit_type === 'lapereau') {
                \App\Models\Lapereau::where('id', $saleRabbit->rabbit_id)
                    ->update(['etat' => 'vendu']);
            }
        }

        $this->notifyUser([
            'type' => 'success',
            'title' => ' Paiement Reçu',
            'message' => "Paiement complet reçu pour la vente #{$sale->id} (" .
                number_format($sale->total_amount, 2, ',', ' ') . " FCFA)",
            'action_url' => route('sales.show', $sale),
        ]);

        return back()->with('success', 'Paiement marqué comme reçu !');
    }

    /**
     * Record Partial Payment
     */
    public function recordPartialPayment(Request $request, Sale $sale)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0|max:' . $sale->total_amount
        ]);

        $oldAmount = $sale->amount_paid;
        $newAmount = $request->amount_paid;
        $remaining = $sale->total_amount - $newAmount;

        $sale->update([
            'payment_status' => $remaining > 0 ? 'partial' : 'paid',
            'amount_paid' => $newAmount
        ]);

        $this->notifyUser([
            'type' => $remaining > 0 ? 'info' : 'success',
            'title' => $remaining > 0 ? ' Paiement Partiel Reçu' : ' Paiement Final Reçu',
            'message' => "Vente #{$sale->id}: +" . number_format($newAmount - $oldAmount, 2, ',', ' ') .
                " FCFA reçus. " .
                ($remaining > 0 ? "Solde restant: " . number_format($remaining, 2, ',', ' ') . " FCFA" : "Solde soldé !"),
            'action_url' => route('sales.show', $sale)
        ]);

        return back()->with('success', 'Paiement enregistré avec succès !');
    }

    /**
     * Change Payment Status
     */
    public function changePaymentStatus(Request $request, Sale $sale)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,pending,partial'
        ]);

        $oldStatus = $sale->payment_status;
        $newStatus = $request->payment_status;

        $sale->update([
            'payment_status' => $newStatus,
            'amount_paid' => $newStatus === 'paid' ? $sale->total_amount : ($newStatus === 'pending' ? 0 : $sale->amount_paid)
        ]);

        $statusLabels = [
            'paid' => 'Payé',
            'pending' => ' En attente',
            'partial' => 'Paiement partiel'
        ];

        $this->notifyUser([
            'type' => $newStatus === 'paid' ? 'success' : ($newStatus === 'partial' ? 'info' : 'warning'),
            'title' => '💳 Statut de Paiement Modifié',
            'message' => "Vente #{$sale->id}: {$statusLabels[$oldStatus]} → {$statusLabels[$newStatus]}",
            'action_url' => route('sales.show', $sale)
        ]);

        return back()->with('success', 'Statut de paiement mis à jour !');
    }

    /**
     * Export Sales Data
     */
    public function export()
    {
        $sales = Sale::with('user')->get();

        $this->notifyUser([
            'type' => 'info',
            'title' => 'Export de Données',
            'message' => "Export des ventes généré: {$sales->count()} ventes exportées",
            'action_url' => route('sales.index')
        ]);

        $filename = 'ventes_cuniapp_' . date('Y-m-d_His') . '.json';
        $json = json_encode($sales, JSON_PRETTY_PRINT);

        return response($json, 200)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Bulk Delete Sales
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sales,id'
        ]);

        $count = count($request->ids);
        Sale::whereIn('id', $request->ids)->delete();

        $this->notifyUser([
            'type' => 'warning',
            'title' => ' Suppression Multiple',
            'message' => "{$count} vente(s) supprimée(s) en masse",
            'action_url' => route('sales.index')
        ]);

        return back()->with('success', "{$count} vente(s) supprimée(s) avec succès !");
    }

    /**
     * Helper: Get type label
     */
    private function getTypeLabel(string $type): string
    {
        return match ($type) {
            'male' => 'mâle(s)',
            'female' => 'femelle(s)',
            'lapereau' => 'lapereau(x)',
            'groupe' => 'groupe(s)',
            default => 'article(s)'
        };
    }

    /**
     * Load rabbits via AJAX for pagination/search
     */
    public function loadRabbits(Request $request)
    {
        $request->validate([
            'type' => 'required|in:males,females,lapereaux',
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:100',
            'count_only' => 'nullable|boolean',
        ]);

        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $perPage = 20;

        switch ($request->type) {
            case 'males':
                //  Exclure 'vendu' pour les mâles
                $query = Male::where('etat', '!=', 'vendu')->orderBy('nom');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
                break;

            case 'females':
                //  Exclure 'vendu' pour les femelles
                $query = Femelle::where('etat', '!=', 'vendu')->orderBy('nom');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
                break;

            case 'lapereaux':
                //  Montrer uniquement 'vivant' pour les lapereaux
                $query = Lapereau::where('etat', 'vivant')
                    ->with('naissance.miseBas.femelle')
                    ->orderBy('code');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
                break;

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }

        if ($request->boolean('count_only')) {
            return response()->json([
                'success' => true,
                'total_count' => $query->count(),
            ]);
        }

        $rabbits = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'html' => view('sales.partials.rabbit-grid', [
                'rabbits' => $rabbits,
                'type' => $request->type,
                'soldIds' => $request->get('sold_ids', [])
            ])->render(),
            'pagination' => [
                'current_page' => $rabbits->currentPage(),
                'last_page' => $rabbits->lastPage(),
                'has_more' => $rabbits->hasMorePages(),
                'next_page' => $rabbits->currentPage() + 1,
                'total' => $rabbits->total(),
            ],
            'total_count' => $query->count(),
        ]);
    }
}
