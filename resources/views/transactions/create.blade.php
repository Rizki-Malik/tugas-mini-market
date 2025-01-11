<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Proses Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form id="transactionForm" class="space-y-6">
                        <div>
                            <x-input-label for="store_id" :value="__('Toko')" class="dark:text-gray-300" />
                            <x-select-input id="store_id" name="store_id" required class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                <option value="">{{ __('Pilih Toko') }}</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </x-select-input>
                        </div>

                        <div id="itemsContainer" class="space-y-4">
                            <div class="item-row grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label :value="__('Produk')" class="dark:text-gray-300" />
                                    <x-select-input name="items[0][product_id]" required 
                                        class="product-select mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                        <option value="">{{ __('Pilih Produk') }}</option>
                                    </x-select-input>
                                </div>
                                <div>
                                    <x-input-label :value="__('Kuantitas')" class="dark:text-gray-300" />
                                    <x-text-input type="number" name="items[0][quantity]" min="1" required 
                                        class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="removeItem(this)" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                        {{ __('Hapus Item') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <x-secondary-button type="button" onclick="addItem()">
                                {{ __('Tambah Item') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Simpan Transaksi') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let itemCount = 1;

        function addItem() {
            const template = document.querySelector('.item-row').cloneNode(true);
            template.querySelectorAll('select, input').forEach(input => {
                input.name = input.name.replace('[0]', `[${itemCount}]`);
                input.value = '';
            });
            itemCount++;
            document.getElementById('itemsContainer').appendChild(template);
        }

        function removeItem(button) {
            if (document.querySelectorAll('.item-row').length > 1) {
                button.closest('.item-row').remove();
            }
        }

        document.getElementById('transactionForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                const response = await fetch('{{ route("transactions.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(Object.fromEntries(new FormData(e.target)))
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    window.location.href = '{{ route("transactions.index") }}';
                } else {
                    alert(data.message || 'An error occurred');
                }
            } catch (error) {
                alert('An error occurred');
            }
        });

        // Load products when store is selected
        document.getElementById('store_id').addEventListener('change', async (e) => {
            const storeId = e.target.value;
            try {
                const response = await fetch(`/api/stores/${storeId}/products`);
                const products = await response.json();
                
                document.querySelectorAll('.product-select').forEach(select => {
                    select.innerHTML = '<option value="">{{ __("Select Product") }}</option>' +
                        products.map(product => 
                            `<option value="${product.id}">${product.name} - Stock: ${product.quantity}</option>`
                        ).join('');
                });
            } catch (error) {
                alert('Error loading products');
            }
        });
    </script>
    @endpush
</x-app-layout>