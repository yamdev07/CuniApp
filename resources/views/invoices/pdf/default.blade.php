<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563EB;
        }

        .company-info h1 {
            color: #2563EB;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .company-info p {
            color: #666;
            font-size: 11px;
            margin: 3px 0;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .invoice-details {
            font-size: 11px;
            color: #666;
        }

        .invoice-details p {
            margin: 5px 0;
        }

        .bill-to {
            margin-bottom: 30px;
        }

        .bill-to h3 {
            color: #333;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background: #2563EB;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 11px;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .items-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .totals {
            width: 100%;
            margin-bottom: 30px;
        }

        .totals-row {
            display: flex;
            justify-content: flex-end;
            padding: 8px 0;
        }

        .totals-row .label {
            width: 200px;
            text-align: right;
            padding-right: 20px;
            color: #666;
        }

        .totals-row .value {
            width: 150px;
            text-align: right;
            font-weight: 600;
        }

        .totals-row.total {
            background: #2563EB;
            color: white;
            padding: 12px;
            border-radius: 4px;
        }

        .totals-row.total .label,
        .totals-row.total .value {
            color: white;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-paid {
            background: #10B981;
            color: white;
        }

        .status-pending {
            background: #F59E0B;
            color: white;
        }

        .status-cancelled {
            background: #EF4444;
            color: white;
        }

        .notes {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $farm['name'] }}</h1>
                <p>{{ $farm['address'] }}</p>
                <p>Tél: {{ $farm['phone'] }}</p>
                <p>Email: {{ $farm['email'] }}</p>
            </div>
            <div class="invoice-info">
                <h2>FACTURE</h2>
                <div class="invoice-details">
                    <p><strong>N°:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                    <p><strong>Échéance:</strong> {{ $invoice->due_date?->format('d/m/Y') ?? 'N/A' }}</p>
                    <p>
                        <strong>Statut:</strong>
                        <span class="status-badge status-{{ $invoice->status }}">
                            {{ $invoice->status }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Bill To -->
        <div class="bill-to">
            <h3>Facturé à</h3>
            <p><strong>{{ $user->name }}</strong></p>
            <p>{{ $user->email }}</p>
            @if ($invoice->billing_details)
                @if (isset($invoice->billing_details['phone']))
                    <p>Tél: {{ $invoice->billing_details['phone'] }}</p>
                @endif
            @endif
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="width: 80px; text-align: center;">Qté</th>
                    <th style="width: 120px; text-align: right;">Prix Unit.</th>
                    <th style="width: 120px; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->line_items ?? [] as $item)
                    <tr>
                        <td>{{ $item['description'] ?? 'Abonnement CuniApp' }}</td>
                        <td style="text-align: center;">{{ $item['quantity'] ?? 1 }}</td>
                        <td style="text-align: right;">{{ number_format($item['unit_price'], 0, ',', ' ') }} FCFA</td>
                        <td style="text-align: right;">{{ number_format($item['total'], 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span class="label">Sous-total:</span>
                <span class="value">{{ number_format($invoice->amount, 0, ',', ' ') }} FCFA</span>
            </div>
            @if ($invoice->tax_amount > 0)
                <div class="totals-row">
                    <span class="label">TVA (18%):</span>
                    <span class="value">{{ number_format($invoice->tax_amount, 0, ',', ' ') }} FCFA</span>
                </div>
            @endif
            <div class="totals-row total">
                <span class="label">TOTAL:</span>
                <span class="value">{{ number_format($invoice->total_amount, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <!-- Payment Info -->
        @if ($invoice->payment_method)
            <div class="notes">
                <strong>Informations de Paiement:</strong><br>
                Méthode: {{ strtoupper($invoice->payment_method) }}<br>
                @if ($invoice->transaction_reference)
                    Référence: {{ $invoice->transaction_reference }}<br>
                @endif
                @if ($invoice->paid_at)
                    Payé le: {{ $invoice->paid_at->format('d/m/Y à H:i') }}
                @endif
            </div>
        @endif

        <!-- Notes -->
        @if ($invoice->notes)
            <div class="notes">
                <strong>Notes:</strong><br>
                {{ $invoice->notes }}
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Merci pour votre confiance !</p>
            <p>{{ $farm['name'] }} - Tous droits réservés © {{ date('Y') }}</p>
            <p>Généré le {{ $generatedAt->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>

</html>
