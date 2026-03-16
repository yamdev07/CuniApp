<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * List user invoices
     */
    public function index(Request $request)
    {
        // ✅ Start query with user isolation (Defense-in-Depth)
        $query = Invoice::where('user_id', Auth::id())
            ->orderBy('invoice_date', 'desc');

        // ✅ INVOICE NUMBER SEARCH
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'LIKE', "%{$request->invoice_number}%");
        }

        // ✅ STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ✅ DATE RANGE FILTER: Start Date
        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }

        // ✅ DATE RANGE FILTER: End Date
        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        // ✅ PAGINATION (20 per page)
        $invoices = $query->paginate(20)->withQueryString();

        // ✅ STATISTICS (for dashboard cards)
        $stats = [
            'total' => Invoice::where('user_id', Auth::id())->count(),
            'paid' => Invoice::where('user_id', Auth::id())
                ->where('status', 'paid')
                ->count(),
            'pending' => Invoice::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->count(),
            'total_amount' => Invoice::where('user_id', Auth::id())
                ->where('status', 'paid')
                ->sum('total_amount'),
        ];

        return view('invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Show invoice details
     */
    public function show(Invoice $invoice)
    {
        // Authorization check
        if ($invoice->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Download PDF invoice
     */
    public function download(Invoice $invoice)
    {
        // Authorization check
        if ($invoice->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        return $this->invoiceService->download($invoice);
    }

    /**
     * Regenerate PDF
     */
    public function regeneratePdf(Invoice $invoice)
    {
        // Authorization check
        if ($invoice->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $this->invoiceService->generatePdf($invoice);

        return back()->with('success', 'PDF régénéré avec succès');
    }

    /**
     * Send invoice via email
     */
    public function email(Invoice $invoice, Request $request)
    {
        // Authorization check
        if ($invoice->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $invoice->user->notify(new \App\Notifications\InvoiceEmailNotification($invoice));

        return back()->with('success', 'Facture envoyée par email');
    }
}
