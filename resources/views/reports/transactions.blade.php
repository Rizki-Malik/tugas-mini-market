<x-app-layout>
    <div class="container">
        <h1>Laporan Transaksi</h1>

        <div class="summary">
            <h3>Summary</h3>
            <ul>
                <li>Total Transactions: {{ $summary['total_transactions'] }}</li>
                <li>Total Amount: {{ number_format($summary['total_amount'], 2) }}</li>
                <li>Average Transaction: {{ number_format($summary['average_transaction'], 2) }}</li>
            </ul>
        </div>

        <div class="grouped-data">
            <h3>Transactions by Status</h3>
            <ul>
                @foreach ($summary['transactions_by_status'] as $status => $data)
                    <li>
                        {{ ucfirst($status) }}: {{ $data['count'] }} transactions, Total: {{ number_format($data['total'], 2) }}
                    </li>
                @endforeach
            </ul>

            <h3>Transactions by Store</h3>
            <ul>
                @foreach ($summary['transactions_by_store'] as $storeId => $data)
                    <li>
                        Store ID {{ $storeId }}: {{ $data['count'] }} transactions, Total: {{ number_format($data['total'], 2) }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="transactions">
            <h3>Transaction Details</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Store</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->store->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($transaction->status) }}</td>
                            <td>{{ number_format($transaction->total_amount, 2) }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>