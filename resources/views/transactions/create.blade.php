<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Create Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('transactions.store') }}">
                        @csrf

                        <div>
                            <label for="store_id" class="block text-sm font-medium text-gray-700">{{ __('Select Store') }}</label>
                            <select id="store_id" name="store_id" class="form-select w-full" required>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <h3 class="text-lg font-medium">{{ __('Add Products') }}</h3>
                            <div id="product-container">
                                <div class="grid grid-cols-3 gap-4 mt-2">
                                    <select name="items[0][product_id]" class="form-select" required>
                                        <option value="">{{ __('Select Product') }}</option>
                                        @foreach($stores->first()->inventories as $inventory)
                                            <option value="{{ $inventory->product->id }}">
                                                {{ $inventory->product->name }} ({{ $inventory->quantity }} in stock)
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="items[0][quantity]" class="form-input" placeholder="{{ __('Quantity') }}" min="1" required>
                                </div>
                            </div>
                            <button type="button" id="add-product-btn" class="btn btn-secondary mt-2">{{ __('Add Another Product') }}</button>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productIndex = 1;

        document.getElementById('add-product-btn').addEventListener('click', function () {
            const container = document.getElementById('product-container');
            const row = `
                <div class="grid grid-cols-3 gap-4 mt-2">
                    <select name="items[${productIndex}][product_id]" class="form-select" required>
                        <option value="">{{ __('Select Product') }}</option>
                        @foreach($stores->first()->inventories as $inventory)
                            <option value="{{ $inventory->product->id }}">
                                {{ $inventory->product->name }} ({{ $inventory->quantity }} in stock)
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="items[${productIndex}][quantity]" class="form-input" placeholder="{{ __('Quantity') }}" min="1" required>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', row);
            productIndex++;
        });
    </script>
</x-app-layout>