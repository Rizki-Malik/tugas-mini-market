<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Show Transaction') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detail Transaksi</h1>
            <a href="{{ route('transactions.print', $transaction) }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Print Struk
            </a>
        </div>
    
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Info Transaksi</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor Struk</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->invoice_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Toko</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->store->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                            <dd class="text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
    
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Items</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transaction->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ number_format($transaction->total_amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>