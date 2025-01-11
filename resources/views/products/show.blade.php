<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Produk: {{ $product->name }}
            </h2>
            @can('manage products')
            <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Data
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Info Produk</h3>
                            <dl class="grid grid-cols-3 gap-4">
                                <dt class="font-medium">Code:</dt>
                                <dd class="col-span-2">{{ $product->code }}</dd>
                                
                                <dt class="font-medium">Name:</dt>
                                <dd class="col-span-2">{{ $product->name }}</dd>
                                
                                <dt class="font-medium">Price:</dt>
                                <dd class="col-span-2">{{ number_format($product->price, 2) }}</dd>
                                
                                <dt class="font-medium">Description:</dt>
                                <dd class="col-span-2">{{ $product->description ?: 'N/A' }}</dd>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Stock Berdasarkan Lokasi</h3>
                            <div class="space-y-4">
                                @forelse($inventoryByStore as $storeName => $inventories)
                                    <div class="border rounded p-4">
                                        <h4 class="font-medium mb-2">{{ $storeName }}</h4>
                                        @foreach($inventories as $inventory)
                                            <div class="flex justify-between items-center">
                                                <span>Current Stock:</span>
                                                <span class="font-medium {{ $inventory->isLowStock() ? 'text-red-600' : '' }}">
                                                    {{ $inventory->quantity }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                    <p class="text-gray-500">Tidak ada intentaris yang ditemukan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>