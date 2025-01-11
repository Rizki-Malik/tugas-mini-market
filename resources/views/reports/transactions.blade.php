<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Transaction Summary</h3>
                    <ul>
                        <li>Total Transactions: {{ $summary['total_transactions'] }}</li>
                        <li>Total Amount: ${{ number_format($summary['total_amount'], 2) }}</li>
                        <li>Average Transaction: ${{ number_format($summary['average_transaction'], 2) }}</li>
                    </ul>

                    <h4 class="mt-6 text-lg font-semibold">Transaction Details</h4>
                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Transaction ID</th>
                                <th class="px-4 py-2">Store</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="px-4 py-2">{{ $transaction->id }}</td>
                                    <td class="px-4 py-2">{{ $transaction->store->name }}</td>
                                    <td class="px-4 py-2">${{ number_format($transaction->total_amount, 2) }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($transaction->status) }}</td>
                                    <td class="px-4 py-2">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>