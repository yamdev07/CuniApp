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

    /**
     * Display a listing of sales
     */
    public function index()
    {
        $sales = Sale::with('user')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_sales' => Sale::count(),
            // ✅ FIX: Only count PAID sales for revenue
            'total_revenue' => Sale::where('payment_status', 'paid')->sum('total_amount'),
            'pending_payments' => Sale::where('payment_status', 'pending')->sum('total_amount'),
            // ✅ FIX: Only count PAID sales for this month's revenue
            'this_month' => Sale::where('payment_status', 'paid')
                ->whereMonth('date_sale', now()->month)
                ->whereYear('date_sale', now()->year)
                ->sum('total_amount')
        ];

        return view('sales.index', compact('sales', 'stats'));
    }

    /**
     * Show form for creating new sale
     */
    public function create()
    {
        // ✅ PAGINATED: Load rabbits with pagination (20 per page)
        $males = Male::orderBy('nom')->paginate(20, ['*'], 'males_page');
        $femelles = Femelle::whereIn('etat', ['Active', 'Vide', 'Allaitante'])
            ->orderBy('nom')
            ->paginate(20, ['*'], 'females_page');
        $lapereaux = Lapereau::whereIn('etat', ['vivant', 'vendu'])
            ->with('naissance.miseBas.femelle')
            ->orderBy('code')
            ->paginate(20, ['*'], 'lapereaux_page');

        // ✅ ADD THIS: Total counts across ALL pages
        $totalCounts = [
            'males' => Male::count(),
            'females' => Femelle::whereIn('etat', ['Active', 'Vide', 'Allaitante'])->count(),
            'lapereaux' => Lapereau::whereIn('etat', ['vivant', 'vendu'])->count(),
        ];

        return view('sales.create', compact('males', 'femelles', 'lapereaux', 'totalCounts'));
    }

    /**
     * Store a newly created sale with INDIVIDUAL pricing per rabbit
     */
    public function store(Request $request)
    {
        // ✅ VALIDATION: Accept arrays for individual prices (nullable to allow empty strings)
        $validated = $request->validate([
            'date_sale' => 'required|date',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'nullable|string|max:100',
            'buyer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_status' => 'required|in:paid,pending,partial',
            'amount_paid' => 'nullable|numeric|min:0',

            // ✅ Rabbit selections with individual prices (nullable to handle empty strings)
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
            'selected_males.array' => 'Les mâles sélectionnés doivent être un tableau',
            'selected_females.array' => 'Les femelles sélectionnées doivent être un tableau',
            'selected_lapereaux.array' => 'Les lapereaux sélectionnés doivent être un tableau',
            'male_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'male_prices.*.min' => 'Le prix doit être supérieur à 0',
            'female_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'female_prices.*.min' => 'Le prix doit être supérieur à 0',
            'lapereau_prices.*.numeric' => 'Le prix doit être un nombre valide',
            'lapereau_prices.*.min' => 'Le prix doit être supérieur à 0',
        ]);

        // ✅ Get selected rabbits
        $selectedMales = $request->input('selected_males', []);
        $selectedFemales = $request->input('selected_females', []);
        $selectedLapereaux = $request->input('selected_lapereaux', []);
        $malePrices = $request->input('male_prices', []);
        $femalePrices = $request->input('female_prices', []);
        $lapereauPrices = $request->input('lapereau_prices', []);

        $totalQuantity = count($selectedMales) + count($selectedFemales) + count($selectedLapereaux);

        // ✅ Validation: At least one rabbit must be selected
        if ($totalQuantity === 0) {
            return back()->withErrors([
                'rabbits' => 'Vous devez sélectionner au moins un lapin pour cette vente.'
            ])->withInput();
        }

        // ✅ VALIDATION: Ensure all selected rabbits have prices > 0
        $missingPrices = [];
        $invalidPrices = [];

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
                'prices' => '⚠️ Prix manquants ou invalides pour: ' . implode(', ', array_slice($missingPrices, 0, 5))
                    . (count($missingPrices) > 5 ? ' et ' . (count($missingPrices) - 5) . ' autres...' : '')
            ])->withInput();
        }

        // ✅ Calculate total amount from INDIVIDUAL prices
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

            // ✅ Link selected males with INDIVIDUAL prices
            foreach ($selectedMales as $index => $maleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'male',
                    'rabbit_id' => $maleId,
                    'sale_price' => $malePrices[$index] ?? 0,
                ]);
            }

            // ✅ Link selected females with INDIVIDUAL prices
            foreach ($selectedFemales as $index => $femaleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'female',
                    'rabbit_id' => $femaleId,
                    'sale_price' => $femalePrices[$index] ?? 0,
                ]);
            }

            // ✅ Link selected lapereaux with INDIVIDUAL prices
            foreach ($selectedLapereaux as $index => $lapereauId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'lapereau',
                    'rabbit_id' => $lapereauId,
                    'sale_price' => $lapereauPrices[$index] ?? 0,
                ]);
            }

            // ✅ Update rabbit status to 'vendu' or 'Inactive'
            foreach ($selectedMales as $maleId) {
                Male::where('id', $maleId)->update(['etat' => 'Inactive']);
            }
            foreach ($selectedFemales as $femaleId) {
                Femelle::where('id', $femaleId)->update(['etat' => 'Vide']);
            }
            foreach ($selectedLapereaux as $lapereauId) {
                Lapereau::where('id', $lapereauId)->update(['etat' => 'vendu']);
            }

            // Notification
            $this->notifyUser([
                'type' => $sale->payment_status === 'paid' ? 'success' : 'warning',
                'title' => '💰 Nouvelle Vente Enregistrée',
                'message' => "Vente #{$sale->id}: {$totalQuantity} lapin(s) à {$sale->buyer_name} pour "
                    . number_format($sale->total_amount, 2, ',', ' ') . " FCFA",
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
    // app/Http/Controllers/SaleController.php

    public function show(Sale $sale, Request $request)
    {
        // Check ownership in controller, not route
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $sale->load(['user']);

        // ✅ Paginate rabbits (10 per page)
        $rabbitsQuery = $sale->rabbits()->with('rabbit');

        // Search filter
        if ($request->has('search_rabbit')) {
            $search = $request->search_rabbit;
            $rabbitsQuery->whereHas('rabbit', function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('filter_category')) {
            $rabbitsQuery->where('rabbit_type', $request->filter_category);
        }

        $rabbits = $rabbitsQuery->paginate(10)->withQueryString();

        return view('sales.show', compact('sale', 'rabbits'));
    }

    /**
     * Show form for editing sale
     */
    // app/Http/Controllers/SaleController.php

    public function edit(Sale $sale)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette vente');
        }

        $sale->load(['rabbits.rabbit']);

<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
        // ✅ PAGINATED: Load available rabbits
        $soldMaleIds = $sale->rabbits()->where('rabbit_type', 'male')->pluck('rabbit_id')->toArray();
        $soldFemaleIds = $sale->rabbits()->where('rabbit_type', 'female')->pluck('rabbit_id')->toArray();
        $soldLapereauIds = $sale->rabbits()->where('rabbit_type', 'lapereau')->pluck('rabbit_id')->toArray();

<<<<<<< HEAD
=======
=======
        // ✅ FIXED: Load ALL available males (no etat filter)
>>>>>>> main
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
        $males = Male::whereDoesntHave('sales', function ($q) use ($sale) {
            $q->whereHas('sale', function ($sq) use ($sale) {
                $sq->where('payment_status', '!=', 'cancelled')
                    ->where('id', '!=', $sale->id);
            });
        })
            ->orderBy('nom')
            ->paginate(20, ['*'], 'males_page');

        $femelles = Femelle::where('etat', 'Active')
            ->whereDoesntHave('sales', function ($q) use ($sale) {
                $q->whereHas('sale', function ($sq) use ($sale) {
                    $sq->where('payment_status', '!=', 'cancelled')
                        ->where('id', '!=', $sale->id);
                });
            })
            ->orderBy('nom')
            ->paginate(20, ['*'], 'females_page');

        $lapereaux = Lapereau::where('etat', 'vivant')
            ->whereDoesntHave('sales', function ($q) use ($sale) {
                $q->whereHas('sale', function ($sq) use ($sale) {
                    $sq->where('payment_status', '!=', 'cancelled')
                        ->where('id', '!=', $sale->id);
                });
            })
            ->with('naissance.miseBas.femelle')
            ->orderBy('code')
            ->paginate(20, ['*'], 'lapereaux_page');

        return view('sales.edit', compact(
            'sale',
            'males',
            'femelles',
            'lapereaux',
            'soldMaleIds',
            'soldFemaleIds',
            'soldLapereauIds'
        ));
    }

    /**
     * Update the specified sale
     */
    // app/Http/Controllers/SaleController.php

    public function update(Request $request, Sale $sale)
    {
        // ✅ Check ownership
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette vente');
        }

        // ✅ VALIDATION: Match what the edit form actually sends
        $validated = $request->validate([
            'date_sale' => 'required|date',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'nullable|string|max:100',
            'buyer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_status' => 'required|in:paid,pending,partial',
            'amount_paid' => 'nullable|numeric|min:0',

            // ✅ Rabbit selections with individual prices
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

        // ✅ Get selected rabbits
        $selectedMales = $request->input('selected_males', []);
        $selectedFemales = $request->input('selected_females', []);
        $selectedLapereaux = $request->input('selected_lapereaux', []);
        $malePrices = $request->input('male_prices', []);
        $femalePrices = $request->input('female_prices', []);
        $lapereauPrices = $request->input('lapereau_prices', []);

        $totalQuantity = count($selectedMales) + count($selectedFemales) + count($selectedLapereaux);

        // ✅ Validation: At least one rabbit must be selected
        if ($totalQuantity === 0) {
            return back()->withErrors([
                'rabbits' => 'Vous devez sélectionner au moins un lapin pour cette vente.'
            ])->withInput();
        }

        // ✅ VALIDATION: Ensure all selected rabbits have prices > 0
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
                'prices' => '⚠️ Prix manquants ou invalides pour: ' . implode(', ', array_slice($missingPrices, 0, 5))
            ])->withInput();
        }

        // ✅ Calculate total amount from INDIVIDUAL prices
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

        // Set amount_paid based on payment status
        if ($validated['payment_status'] === 'paid') {
            $validated['amount_paid'] = $totalAmount;
        } elseif ($validated['payment_status'] === 'partial') {
            $validated['amount_paid'] = $validated['amount_paid'] ?? $sale->amount_paid;
        } else {
            $validated['amount_paid'] = 0;
        }

        DB::beginTransaction();
        try {
            // ✅ Track payment status change for notification
            $statusChanged = ($sale->payment_status !== $validated['payment_status']);
            $oldStatus = $sale->payment_status;
            $newStatus = $validated['payment_status'];
            $oldTotal = $sale->total_amount;

            // ✅ Update sale
            $sale->update($validated);

            // ✅ Delete old sale_rabbits
            $sale->rabbits()->delete();

            // ✅ Link selected males with INDIVIDUAL prices
            foreach ($selectedMales as $index => $maleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'male',
                    'rabbit_id' => $maleId,
                    'sale_price' => $malePrices[$index] ?? 0,
                ]);
                Male::where('id', $maleId)->update(['etat' => 'Inactive']);
            }

            // ✅ Link selected females with INDIVIDUAL prices
            foreach ($selectedFemales as $index => $femaleId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'female',
                    'rabbit_id' => $femaleId,
                    'sale_price' => $femalePrices[$index] ?? 0,
                ]);
                Femelle::where('id', $femaleId)->update(['etat' => 'Vide']);
            }

            // ✅ Link selected lapereaux with INDIVIDUAL prices
            foreach ($selectedLapereaux as $index => $lapereauId) {
                SaleRabbit::create([
                    'sale_id' => $sale->id,
                    'rabbit_type' => 'lapereau',
                    'rabbit_id' => $lapereauId,
                    'sale_price' => $lapereauPrices[$index] ?? 0,
                ]);
                Lapereau::where('id', $lapereauId)->update(['etat' => 'vendu']);
            }

            // ✅ NOTIFICATION: Sale Updated
            $this->notifyUser([
                'type' => 'info',
                'title' => '✏️ Vente Modifiée',
                'message' => "Vente #{$sale->id} mise à jour: {$totalQuantity} lapin(s) - " . number_format($sale->total_amount, 2, ',', ' ') . " FCFA",
                'action_url' => route('sales.show', $sale)
            ]);

            // ✅ NOTIFICATION: Payment Status Changed
            if ($statusChanged) {
                $statusLabels = [
                    'paid' => '✅ Payé',
                    'pending' => '⏳ En attente',
                    'partial' => '💵 Paiement partiel'
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
     * Remove the specified sale
     */
    public function destroy(Sale $sale)
    {
        $typeLabel = $this->getTypeLabel($sale->type);
        $saleInfo = "{$sale->quantity} {$typeLabel} à {$sale->buyer_name} pour " .
            number_format($sale->total_amount, 2, ',', ' ') . " FCFA";

        $sale->delete();

        // ✅ NOTIFICATION 5: Sale Deleted
        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Vente Supprimée',
            'message' => "Vente #{$sale->id} supprimée: {$saleInfo}",
            'action_url' => route('sales.index')
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Vente supprimée avec succès !');
    }

    /**
     * Mark sale as paid
     */
    public function markAsPaid(Sale $sale)
    {
        // Check ownership
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cette vente');
        }

        if ($sale->payment_status === 'paid') {
            return back()->with('warning', 'Cette vente est déjà payée !');
        }

        // Update sale status
        $sale->update([
            'payment_status' => 'paid',
            'amount_paid' => $sale->total_amount,
        ]);

        // Update rabbit statuses to 'vendu'
        foreach ($sale->rabbits as $saleRabbit) {
            if ($saleRabbit->rabbit_type === 'male') {
                \App\Models\Male::where('id', $saleRabbit->rabbit_id)
                    ->update(['etat' => 'Inactive']);
            } elseif ($saleRabbit->rabbit_type === 'female') {
                \App\Models\Femelle::where('id', $saleRabbit->rabbit_id)
                    ->update(['etat' => 'Vide']);
            } elseif ($saleRabbit->rabbit_type === 'lapereau') {
                \App\Models\Lapereau::where('id', $saleRabbit->rabbit_id)
                    ->update(['etat' => 'vendu']);
            }
        }

        // Create notification
        $this->notifyUser([
            'type' => 'success',
            'title' => '✅ Paiement Reçu',
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

        // ✅ NOTIFICATION 7: Partial Payment Received
        $this->notifyUser([
            'type' => $remaining > 0 ? 'info' : 'success',
            'title' => $remaining > 0 ? '💵 Paiement Partiel Reçu' : '✅ Paiement Final Reçu',
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
            'paid' => '✅ Payé',
            'pending' => '⏳ En attente',
            'partial' => '💵 Paiement partiel'
        ];

        // ✅ NOTIFICATION 8: Payment Status Changed
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

        // ✅ NOTIFICATION 9: Export Generated
        $this->notifyUser([
            'type' => 'info',
            'title' => '📊 Export de Données',
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

        // ✅ NOTIFICATION 10: Bulk Delete
        $this->notifyUser([
            'type' => 'warning',
            'title' => '🗑️ Suppression Multiple',
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

<<<<<<< HEAD
=======
    // ✅ NEW: AJAX endpoint for loading more rabbits
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
    public function loadRabbits(Request $request)
    {
        $request->validate([
            'type' => 'required|in:males,females,lapereaux',
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:100',
<<<<<<< HEAD
            'count_only' => 'nullable|boolean',  // ✅ NEW: For getting total count without HTML
=======
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
        ]);

        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $perPage = 20;

        switch ($request->type) {
            case 'males':
                $query = Male::orderBy('nom');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
<<<<<<< HEAD
=======
                $rabbits = $query->paginate($perPage, ['*'], 'page', $page);
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
                break;

            case 'females':
                $query = Femelle::whereIn('etat', ['Active', 'Vide', 'Allaitante'])
                    ->orderBy('nom');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
<<<<<<< HEAD
=======
                $rabbits = $query->paginate($perPage, ['*'], 'page', $page);
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
                break;

            case 'lapereaux':
                $query = Lapereau::whereIn('etat', ['vivant', 'vendu'])
                    ->with('naissance.miseBas.femelle')
                    ->orderBy('code');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nom', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
<<<<<<< HEAD
=======
                $rabbits = $query->paginate($perPage, ['*'], 'page', $page);
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
                break;

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }

<<<<<<< HEAD
        // ✅ NEW: If count_only requested, return just the count (for search filtering)
        if ($request->boolean('count_only')) {
            return response()->json([
                'success' => true,
                'total_count' => $query->count(),
            ]);
        }

        // ✅ Standard pagination
        $rabbits = $query->paginate($perPage, ['*'], 'page', $page);

=======
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
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
<<<<<<< HEAD
                'total' => $rabbits->total(),  // ✅ Total across ALL pages
            ],
            'total_count' => $query->count(),  // ✅ ADDED: For displaying total in tab
=======
                'total' => $rabbits->total(),
            ]
>>>>>>> a5f04f579910b129ca1584b6c433d5edd73ae076
        ]);
    }
}
