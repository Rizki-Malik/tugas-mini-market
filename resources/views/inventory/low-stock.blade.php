<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Low Stok Inventory
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <h3 class="text-lg font-bold">Produk dengan Low Stok</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2">Produk</th>
                                    <th class="border px-4 py-2">Toko</th>
                                    <th class="border px-4 py-2">Kuantitas</th>
                                    <th class="border px-4 py-2">Minimum Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventory as $item)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $item->product->name }} ({{ $item->product->code }})</td>
                                        <td class="border px-4 py-2">{{ $item->store->name }}</td>
                                        <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                        <td class="border px-4 py-2">{{ $item->minimum_stock }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="border px-4 py-2 text-center">Tidak ada low-stock inventory yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $inventory->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>