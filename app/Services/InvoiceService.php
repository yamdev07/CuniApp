<?php
// app/Services/InvoiceService.php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    /**
     * Generate PDF invoice
     */
    public function generatePdf(Invoice $invoice): string
    {
        $data = [
            'invoice' => $invoice,
            'user' => $invoice->user,
            'farm' => [
                'name' => Setting::get('farm_name', 'CuniApp Élevage'),
                'address' => Setting::get('farm_address', 'Houéyiho après le pont devant Volta United, Cotonou, Bénin'),
                'phone' => Setting::get('farm_phone', '+229 01 52 41 52 41'),
                'email' => Setting::get('farm_email', 'contact@anyxtech.com'),
            ],
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('invoices.pdf.default', $data);
        $pdf->setPaper('a4', 'portrait');

        // Generate filename
        $filename = 'invoice_' . $invoice->invoice_number . '.pdf';
        $path = 'invoices/' . now()->format('Y/m') . '/' . $filename;

        // Store PDF
        Storage::disk('public')->put($path, $pdf->output());

        // Update invoice record
        $invoice->update([
            'pdf_path' => $path,
            'pdf_generated' => true,
            'pdf_generated_at' => now(),
        ]);

        return $path;
    }

    /**
     * Download PDF invoice
     */
    public function download(Invoice $invoice)
    {
        // Generate if not exists
        if (!$invoice->pdf_generated || !Storage::disk('public')->exists($invoice->pdf_path)) {
            $this->generatePdf($invoice);
        }

        return Storage::disk('public')->download($invoice->pdf_path);
    }

    /**
     * Create invoice from payment transaction
     */
    public function createFromTransaction($transaction): Invoice
    {
        $invoice = Invoice::create([
            'user_id' => $transaction->user_id,
            'subscription_id' => $transaction->subscription_id,
            'payment_transaction_id' => $transaction->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_type' => 'subscription',
            'amount' => $transaction->amount,
            'tax_amount' => 0,
            'total_amount' => $transaction->amount,
            'currency' => 'XOF',
            'status' => $transaction->status === 'completed' ? 'paid' : 'pending',
            'invoice_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'paid_at' => $transaction->status === 'completed' ? now() : null,
            'billing_details' => [
                'name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
            'line_items' => [
                [
                    'description' => 'Abonnement CuniApp Élevage',
                    'quantity' => 1,
                    'unit_price' => $transaction->amount,
                    'total' => $transaction->amount,
                ]
            ],
            'payment_method' => $transaction->payment_method,
            'transaction_reference' => $transaction->transaction_id,
        ]);

        // Generate PDF immediately for paid invoices
        if ($transaction->status === 'completed') {
            $this->generatePdf($invoice);
        }

        return $invoice;
    }
}
