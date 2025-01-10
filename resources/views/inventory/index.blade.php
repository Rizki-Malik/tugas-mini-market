<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Inventory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <x-primary-button tag="a" href="{{ route('products.create') }}" 
                    class="bg-gray-800 text-white hover:bg-gray-500 dark:bg-gray-500 dark:text-gray-200 dark:hover:bg-gray-500">
                    Tambah Produk
                </x-primary-button>

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-left">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID
                                    </span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-left">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID Store
                                    </span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-left">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID Product
                                    </span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-right">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Quantity
                                    </span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($inventory as $item)
                            <tr>
                                <!-- ID -->
                                <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">
                                    {{ $item->id }}
                                </td>
                                <!-- ID Store -->
                                <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">
                                    {{ $item->store->name }}
                                </td>
                                <!-- ID Product -->
                                <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">
                                    {{ $item->product_id }}
                                </td>
                                <!-- Quantity -->
                                <td class="px-6 py-4 whitespace-nowrap text-right dark:text-gray-200">
                                    {{ number_format($item->quantity, 2) }}
                                </td>
                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('inventory.show', $item->id) }}" 
                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                        
                    </table>

                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>