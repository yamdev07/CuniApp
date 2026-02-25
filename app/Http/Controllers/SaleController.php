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

    public function create()
    {
        return view('sales.create');
    }

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

        // Create notification
        $this->notifyUser([
            'type' => 'success',
            'title' => 'Nouvelle Vente Enregistrée',
            'message' => "Vente de {$sale->quantity} {$this->getTypeLabel($sale->type)} à {$sale->buyer_name} pour {$sale->total_amount} €",
            'action_url' => route('sales.show', $sale)
        ]);

        // Flash toast
        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Vente enregistrée !',
            'message' => "{$sale->quantity} {$this->getTypeLabel($sale->type)} vendu(s) à {$sale->buyer_name}",
            'action_url' => route('sales.index'),
            'duration' => 6000,
            'timestamp' => now()->toIso8601String()
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Vente enregistrée avec succès !');
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        return view('sales.edit', compact('sale'));
    }

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
        
        if ($validated['payment_status'] === 'paid') {
            $validated['amount_paid'] = $validated['total_amount'];
        } elseif ($validated['payment_status'] === 'partial') {
            $validated['amount_paid'] = $validated['amount_paid'] ?? $sale->amount_paid;
        } else {
            $validated['amount_paid'] = 0;
        }

        $sale->update($validated);

        // Create notification
        $this->notifyUser([
            'type' => 'info',
            'title' => 'Vente Modifiée',
            'message' => "Vente #{$sale->id} mise à jour: {$sale->quantity} {$this->getTypeLabel($sale->type)} - {$sale->total_amount} €",
            'action_url' => route('sales.show', $sale)
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Vente mise à jour avec succès !');
    }

    public function destroy(Sale $sale)
    {
        $saleInfo = "{$sale->quantity} {$this->getTypeLabel($sale->type)} à {$sale->buyer_name}";
        $sale->delete();

        // Create notification
        $this->notifyUser([
            'type' => 'warning',
            'title' => 'Vente Supprimée',
            'message' => "Vente supprimée: {$saleInfo}",
            'action_url' => route('sales.index')
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Vente supprimée avec succès !');
    }

    public function markAsPaid(Sale $sale)
    {
        $sale->update([
            'payment_status' => 'paid',
            'amount_paid' => $sale->total_amount
        ]);

        $this->notifyUser([
            'type' => 'success',
            'title' => 'Paiement Reçu',
            'message' => "Paiement complet reçu pour la vente #{$sale->id} ({$sale->total_amount} €)",
            'action_url' => route('sales.show', $sale)
        ]);

        return back()->with('success', 'Paiement marqué comme reçu !');
    }

    private function getTypeLabel(string $type): string
    {
        return match($type) {
            'male' => 'mâle(s)',
            'female' => 'femelle(s)',
            'lapereau' => 'lapereau(x)',
            'groupe' => 'groupe(s)',
            default => 'article(s)'
        };
    }
}