<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transfer Stock of {{ $inventory->product->name }} from {{ $inventory->store->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('inventory.transfer') }}" method="POST">
                        @csrf

                        <input type="hidden" name="from_inventory_id" value="{{ $inventory->id }}">

                        <div class="mb-4">
                            <x-label for="to_store_id" :value="'Transfer To Store'" />
                            <select id="to_store_id" name="to_store_id" class="block mt-1 w-full" required>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-label for="quantity" :value="'Quantity'" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" min="1" required />
                        </div>

                        <div class="mb-4">
                            <x-label for="notes" :value="'Notes'" />
                            <textarea id="notes" name="notes" class="block mt-1 w-full form-control"></textarea>
                        </div>

                        <x-primary-button class="ml-3">
                            {{ __('Transfer Stock') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>