<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Adjust Stock for {{ $inventory->product->name }} at {{ $inventory->store->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('inventory.adjust-stock', $inventory->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="quantity" :value="'Quantity'" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="adjustment_type" :value="'Adjustment Type'" />
                            <select id="adjustment_type" name="adjustment_type" class="block mt-1 w-full" required>
                                <option value="add">Add</option>
                                <option value="subtract">Subtract</option>
                                <option value="set">Set</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="'Notes'" />
                            <textarea id="notes" name="notes" class="block mt-1 w-full form-control"></textarea>
                        </div>

                        <x-primary-button class="ml-3">
                            {{ __('Adjust Stock') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>