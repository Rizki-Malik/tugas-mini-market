<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inventory Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Inventory Summary</h3>
                    <ul>
                        <li>Total Products: {{ $summary['total_products'] }}</li>
                        <li>Low Stock Items: {{ $summary['low_stock_items'] }}</li>
                        <li>Out of Stock Items: {{ $summary['out_of_stock_items'] }}</li>
                    </ul>

                    <h4 class="mt-6 text-lg font-semibold">Inventory by Store</h4>
                    <ul>
                        @foreach ($summary['inventory_by_store'] as $storeId => $storeSummary)
                            <li>
                                Store ID {{ $storeId }}: 
                                Total Items: {{ $storeSummary['total_items'] }},
                                Low Stock: {{ $storeSummary['low_stock'] }}
                            </li>
                        @endforeach
                    </ul>

                    <h4 class="mt-6 text-lg font-semibold">Inventory Details</h4>
                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Product</th>
                                <th class="px-4 py-2">Store</th>
                                <th class="px-4 py-2">Quantity</th>
                                <th class="px-4 py-2">Minimum Stock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($inventory as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $item->product->name }}</td>
                                    <td class="px-4 py-2">{{ $item->store->name }}</td>
                                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">{{ $item->minimum_stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>