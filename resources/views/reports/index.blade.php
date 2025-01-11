<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sales Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Sales Summary</h3>
                    <ul>
                        <li>Total Sales: ${{ number_format($summary['total_sales'], 2) }}</li>
                        <li>Total Transactions: {{ $summary['total_transactions'] }}</li>
                        <li>Average Transaction: ${{ number_format($summary['average_transaction'], 2) }}</li>
                    </ul>

                    <h4 class="mt-6 text-lg font-semibold">Penjualan Toko :</h4>
                    <ul>
                        @foreach ($summary['sales_by_store'] as $storeSummary)
                            <li>
                                {{ $storeSummary['store_name'] }} = 
                                Rp. {{ number_format($storeSummary['total'], 2) }} 
                                ({{ $storeSummary['count'] }} Transaksi)
                            </li>
                        @endforeach
                    </ul>

                    <h4 class="mt-6 text-lg font-semibold">Sales Transactions</h4>
                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Transaction ID</th>
                                <th class="px-4 py-2">Store</th>
                                <th class="px-4 py-2">Total Amount</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($sales as $sale)
                                <tr>
                                    <td class="px-4 py-2">{{ $sale->id }}</td>
                                    <td class="px-4 py-2">{{ $sale->store->name }}</td>
                                    <td class="px-4 py-2">${{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="px-4 py-2">{{ $sale->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>