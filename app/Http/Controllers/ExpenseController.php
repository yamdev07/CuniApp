<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Traits\Notifiable;
use App\Models\FirmAuditLog;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    use Notifiable;

    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();

        $query = Expense::where('user_id', $userId)
            ->latest('expense_date');

        if ($request->filled('month')) {
            $query->whereMonth('expense_date', $request->month);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->paginate(15)->withQueryString();

        Log::info('Expenses Query Result', [
            'type' => get_class($expenses),
            'count' => $expenses->count(),
            'total' => $expenses->total(),
        ]);

        $stats = [
            'total' => Expense::where('user_id', $userId)->sum('amount'),
            'this_month' => Expense::where('user_id', $userId)
                ->whereMonth('expense_date', now()->month)
                ->sum('amount'),
        ];

        return view('expenses.index', compact('expenses', 'stats'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:Alimentation,Vétérinaire,Équipement,Transport,Autre',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $expense = Expense::create($validated);

        // ✅ Audit Log
        FirmAuditLog::log(
            auth()->user()->firm_id,
            auth()->id(),
            'expense_created',
            'amount',
            null,
            $validated['amount']
        );

        $this->notifyUser([
            'type' => 'info',
            'title' => 'Dépense Enregistrée',
            'message' => "Une dépense de {$expense->formatted_amount} a été ajoutée.",
            'action_url' => route('expenses.index'),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Dépense enregistrée !');
    }

    public function destroy(Expense $expense)
    {
        // Security check handled by BelongsToUser trait scope

        FirmAuditLog::log(
            auth()->user()->firm_id,
            auth()->id(),
            'expense_deleted',
            'amount',
            $expense->amount,
            null
        );

        $expense->delete();
        return back()->with('success', 'Dépense supprimée.');
    }
}
