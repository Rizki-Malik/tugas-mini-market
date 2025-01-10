<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inventory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="post" action="{{ route('inventory.update', $inventory->id) }}" enctype="multipart/form-data"
                        class="mt-6 space-y6">
                        @method('PATCH')
                        @csrf

                        <!-- Name -->
                        <div class="max-w-xl">
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" type="text" name="name" class="mt-1 block w-full"
                                value="{{ old('name', $inventory->name) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Code -->
                        <div class="max-w-xl">
                            <x-input-label for="code" value="Code" />
                            <x-text-input id="code" type="text" name="code" class="mt-1 block w-full"
                                value="{{ old('code', $inventory->code) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>

                        <!-- Description -->
                        <div class="max-w-xl">
                            <x-input-label for="description" value="Description" />
                            <x-textarea id="description" name="description" class="mt-1 block w-full" required>{{ old('description', $inventory->description) }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Price -->
                        <div class="max-w-xl">
                            <x-input-label for="price" value="Price" />
                            <x-text-input id="price" type="number" name="price" step="0.01" class="mt-1 block w-full"
                                value="{{ old('price', $inventory->price) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <!-- Buttons -->
                        <x-secondary-button tag="a" href="{{ route('inventory') }}">Cancel</x-secondary-button>
                        <x-primary-button value="true">Update</x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
