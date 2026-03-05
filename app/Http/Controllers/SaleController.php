<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use Illuminate\Support\Facades\Auth;
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
            'total_revenue' => Sale::sum('total_amount'),
            'pending_payments' => Sale::where('payment_status', 'pending')->sum('total_amount'),
            'this_month' => Sale::whereMonth('date_sale', now()->month)
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
        // Load available rabbits by category
        $males = Male::where('etat', 'Active')
            ->whereDoesntHave('sales', function ($q) {
                $q->where('payment_status', '!=', 'cancelled');
            })
            ->orderBy('nom')
            ->get();

        $femelles = Femelle::where('etat', 'Active')
            ->whereDoesntHave('sales', function ($q) {
                $q->where('payment_status', '!=', 'cancelled');
            })
            ->orderBy('nom')
            ->get();

        $lapereaux = Lapereau::where('etat', 'vivant')
            ->whereDoesntHave('sales', function ($q) {
                $q->where('payment_status', '!=', 'cancelled');
            })
            ->with('naissance.miseBas.femelle')
            ->orderBy('code')
            ->get();

        return view('sales.create', compact('males', 'femelles', 'lapereaux'));
    }

    /**
     * Store a newly created sale
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_sale' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:male,female,lapereau,groupe',
            'category' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'nullable|string|max:100',
            'buyer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_status' => 'required|in:paid,pending,partial',
            'amount_paid' => 'nullable|numeric|min:0'
        ]);

        // Calculate total amount
        $validated['total_amount'] = $validated['quantity'] * $validated['unit_price'];
        $validated['user_id'] = Auth::id();

        // Set amount_paid based on payment status
        if ($validated['payment_status'] === 'paid') {
            $validated['amount_paid'] = $validated['total_amount'];
        } elseif ($validated['payment_status'] === 'partial') {
            $validated['amount_paid'] = $validated['amount_paid'] ?? 0;
        } else {
            $validated['amount_paid'] = 0;
        }

        $sale = Sale::create($validated);

        // ✅ NOTIFICATION 1: New Sale Created
        $typeLabel = $this->getTypeLabel($sale->type);
        $statusLabels = [
            'paid' => '✅ Payée',
            'pending' => '⏳ En attente',
            'partial' => '💵 Paiement partiel'
        ];

        $this->notifyUser([
            'type' => $sale->payment_status === 'paid' ? 'success' : ($sale->payment_status === 'partial' ? 'info' : 'warning'),
            'title' => '💰 Nouvelle Vente Enregistrée',
            'message' => "Vente #{$sale->id}: {$sale->quantity} {$typeLabel} à {$sale->buyer_name} pour " .
                number_format($sale->total_amount, 2, ',', ' ') . " FCFA - {$statusLabels[$sale->payment_status]}",
            'action_url' => route('sales.show', $sale)
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Vente enregistrée !',
            'message' => "{$sale->quantity} {$typeLabel} vendu(s) à {$sale->buyer_name}",
            'action_url' => route('sales.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Vente enregistrée avec succès !');
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale)
    {
        // ✅ NOTIFICATION 2: Sale Viewed (Optional - for audit trail)
        // Uncomment if you want to track views:
        /*
        $this->notifyUser([
            'type' => 'info',
            'title' => '👁️ Vente Consultée',
            'message' => "Vente #{$sale->id} consultée par " . Auth::user()->name,
            'action_url' => route('sales.show', $sale)
        ]);
        */

        return view('sales.show', compact('sale'));
    }

    /**
     * Show form for editing sale
     */
    public function edit(Sale $sale)
    {
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified sale
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'date_sale' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:male,female,lapereau,groupe',
            'category' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'buyer_name' => 'required|string|max:255',
            'buyer_contact' => 'nullable|string|max:100',
            'buyer_address' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_status' => 'required|in:paid,pending,partial',
            'amount_paid' => 'nullable|numeric|min:0'
        ]);

        $oldTotal = $sale->total_amount;
        $validated['total_amount'] = $validated['quantity'] * $validated['unit_price'];

        // Track payment status change
        $statusChanged = ($sale->payment_status !== $validated['payment_status']);
        $oldStatus = $sale->payment_status;
        $newStatus = $validated['payment_status'];

        if ($validated['payment_status'] === 'paid') {
            $validated['amount_paid'] = $validated['total_amount'];
        } elseif ($validated['payment_status'] === 'partial') {
            $validated['amount_paid'] = $validated['amount_paid'] ?? $sale->amount_paid;
        } else {
            $validated['amount_paid'] = 0;
        }

        $sale->update($validated);

        // ✅ NOTIFICATION 3: Sale Updated
        $typeLabel = $this->getTypeLabel($sale->type);
        $this->notifyUser([
            'type' => 'info',
            'title' => '✏️ Vente Modifiée',
            'message' => "Vente #{$sale->id} mise à jour: {$sale->quantity} {$typeLabel} - " .
                number_format($sale->total_amount, 2, ',', ' ') . " FCFA",
            'action_url' => route('sales.show', $sale)
        ]);

        // ✅ NOTIFICATION 4: Payment Status Changed
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

        return redirect()->route('sales.index')
            ->with('success', 'Vente mise à jour avec succès !');
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
        if ($sale->payment_status === 'paid') {
            return back()->with('warning', 'Cette vente est déjà payée !');
        }

        $oldStatus = $sale->payment_status;
        $sale->update([
            'payment_status' => 'paid',
            'amount_paid' => $sale->total_amount
        ]);

        // ✅ NOTIFICATION 6: Payment Received
        $this->notifyUser([
            'type' => 'success',
            'title' => '✅ Paiement Reçu',
            'message' => "Paiement complet reçu pour la vente #{$sale->id} (" .
                number_format($sale->total_amount, 2, ',', ' ') . " FCFA) - {$sale->buyer_name}",
            'action_url' => route('sales.show', $sale)
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Paiement reçu !',
            'message' => "Vente #{$sale->id} marquée comme payée",
            'action_url' => route('sales.index'),
            'duration' => 5000,
            'timestamp' => now()->toIso8601String()
        ]);

        return back()->with('success', 'Paiement marqué comme reçu !');
    }

    /**
     * ✅ NEW: Record Partial Payment
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
            'message' => "Vente #{$sale->id}: +" .
                number_format($newAmount - $oldAmount, 2, ',', ' ') . " FCFA reçus. " .
                ($remaining > 0 ? "Solde restant: " . number_format($remaining, 2, ',', ' ') . " FCFA" : "Solde soldé !"),
            'action_url' => route('sales.show', $sale)
        ]);

        return back()->with('success', 'Paiement enregistré avec succès !');
    }

    /**
     * ✅ NEW: Change Payment Status
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
     * ✅ NEW: Export Sales Data
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
     * ✅ NEW: Bulk Delete Sales
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
}
