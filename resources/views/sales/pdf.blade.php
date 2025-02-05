<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info div {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals div {
            margin-bottom: 5px;
        }
        .total {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #333;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Facture #{{ $sale->invoice_number }}</h1>
    </div>

    <div class="invoice-info">
        <div>Date: {{ $sale->created_at->format('d/m/Y H:i') }}</div>
        <div>Mode de paiement: {{ strtoupper($sale->payment_method) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }} €</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->line_total, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div>
            <span>Sous-total:</span>
            <span class="text-right">{{ number_format($sale->subtotal, 2) }} €</span>
        </div>
        <div>
            <span>TVA (20%):</span>
            <span class="text-right">{{ number_format($sale->tax, 2) }} €</span>
        </div>
        <div class="total">
            <span>Total:</span>
            <span class="text-right">{{ number_format($sale->total_amount, 2) }} €</span>
        </div>
    </div>
</body>
</html>
