<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Add Products to Store: ') }} {{ $store->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('stores.products.store', $store) }}" method="POST">
                        @csrf
                        <div id="product-container">
                            <div class="product-row mb-4">
                                <label for="product_id" class="block text-sm font-medium text-gray-700">{{ __('Select Product') }}</label>
                                <select name="products[0][id]" required class="mt-1 block w-full">
                                    <option value="">{{ __('Select Product') }}</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="products[0][quantity]" min="1" required placeholder="Quantity" class="mt-2 block w-full">
                            </div>
                        </div>

                        <button type="button" onclick="addProductRow()" class="btn btn-secondary mb-4">{{ __('Add Another Product') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Add Products') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productCount = 1;

        function addProductRow() {
            const container = document.getElementById('product-container');
            const newRow = document.createElement('div');
            newRow.classList.add('product-row', 'mb-4');
            newRow.innerHTML = `
                <label for="product_id" class="block text-sm font-medium text-gray-700">{{ __('Select Product') }}</label>
                <select name="products[${productCount}][id]" required class="mt-1 block w-full">
                    <option value="">{{ __('Select Product') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="products[${productCount}][quantity]" min="1" required placeholder="Quantity" class="mt-2 block w-full">
            `;
            container.appendChild(newRow);
            productCount++;
        }
    </script>
</x-app-layout>