<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Inventory
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('inventory.index') }}" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="store_id" class="block text-sm font-medium text-gray-700">Pilih Toko</label>
                                <select name="store_id" id="store_id" class="form-control mt-1 block w-full">
                                    <option value="">Pilih Toko</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari Produk</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control mt-1 block w-full" placeholder="Search product name or code">
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="low_stock" id="low_stock" {{ request('low_stock') ? 'checked' : '' }} class="form-checkbox">
                                    <span>Low Stock</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Filter
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2">Produk</th>
                                    <th class="border px-4 py-2">Toko</th>
                                    <th class="border px-4 py-2">Kuantitas</th>
                                    <th class="border px-4 py-2">Minimum Stok</th>
                                    <th class="border px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventory as $item)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $item->product->name ?? 'N/A' }} ({{ $item->product->code ?? 'N/A' }})</td>
                                        <td class="border px-4 py-2">{{ $item->store->name ?? 'N/A' }}</td>
                                        <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                        <td class="border px-4 py-2">{{ $item->minimum_stock }}</td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('inventory.adjust-stock.form', $item->id) }}" class="bg-yellow-500 text-white px-2 py-1 mx-1 rounded-md">
                                                Atur Stok
                                            </a>
                                            <a href="{{ route('inventory.transfer-stock.form', $item->id) }}" class="bg-green-500 text-white px-2 py-1 rounded-md">
                                                Transfer
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="border px-4 py-2 text-center">Tidak ada inventory yang ditemukan.</td>
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