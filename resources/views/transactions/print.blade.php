<!DOCTYPE html>
<html>
<head>
    <title>Struk #{{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .total-row {
            font-weight: bold;
        }
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>INVOICE</h1>
        <p>{{ $transaction->invoice_number }}</p>
    </div>

    <div class="invoice-details">
        <p><strong>Toko:</strong> {{ $transaction->store->name }}</p>
        <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('M d, Y H:i') }}</p>
        <p><strong>Diserahkan Oleh:</strong> {{ $transaction->user->name ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kuantitas</th>
                <th>Harga Unit</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ number_format($transaction->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="text-align: center;">Senang telah berbisnis dengan anda!</p>
</body>
</html>