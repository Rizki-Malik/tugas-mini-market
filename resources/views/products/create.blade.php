<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inventory Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-primary-button tag="a" href="{{ route('dashboard') }}" 
                        class="bg-blue-500 text-white hover:bg-blue-700 dark:bg-blue-700 dark:text-gray-200 dark:hover:bg-blue-500">
                           Kembali ke Dashboard
                    </x-primary-button>


                    <form method="post" action="{{ route('inventory.index') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        
                        <!-- Name -->
                        <div class="max-w-xl">
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" type="text" name="name" class="mt-1 block w-full" 
                                value="{{ old('name') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        
                        <!-- Code -->
                        <div class="max-w-xl">
                            <x-input-label for="code" value="Code" />
                            <x-text-input id="code" type="text" name="code" class="mt-1 block w-full" 
                                value="{{ old('code') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>
                        
                        <!-- Description -->
                        <div class="max-w-xl">
                            <x-input-label for="description" value="Description" />
                            <x-textarea id="description" name="description" class="mt-1 block w-full" 
                                required>{{ old('description') }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                        
                        <!-- Price -->
                        <div class="max-w-xl">
                            <x-input-label for="price" value="Price" />
                            <x-text-input id="price" type="number" name="price" step="0.01" class="mt-1 block w-full" 
                                value="{{ old('price') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <!-- Buttons -->
                        <x-secondary-button tag="a" href="{{ route('inventory') }}">Cancel</x-secondary-button>
                        <x-primary-button name="save_and_create" value="true">Save & Create Another</x-primary-button>
                        <x-primary-button name="save" value="true">Save</x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
