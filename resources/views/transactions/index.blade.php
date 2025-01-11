<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @can('create transactions')
                        <div class="mb-4">
                            <a href="{{ route('transactions.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Tambah Data') }}
                            </a>
                        </div>
                    @endcan

                    <form action="{{ route('transactions.index') }}" method="GET" class="mb-6">
                        <div class="flex gap-4">
                            <div>
                                <x-input-label for="store_id" :value="__('Store')" class="dark:text-gray-300" />
                                <x-select-input id="store_id" name="store_id" class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">{{ __('Pilih Toko') }}</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" @selected(request('store_id') == $store->id)>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>
                            <div>
                                <x-input-label for="date_from" :value="__('Date From')" class="dark:text-gray-300" />
                                <x-text-input id="date_from" type="date" name="date_from" 
                                    :value="request('date_from')" 
                                    class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            </div>
                            <div>
                                <x-input-label for="date_to" :value="__('Date To')" class="dark:text-gray-300" />
                                <x-text-input id="date_to" type="date" name="date_to" 
                                    :value="request('date_to')" 
                                    class="mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            </div>
                            <div class="flex items-end">
                                <x-primary-button>{{ __('Filter') }}</x-primary-button>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-left">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Invoice') }}
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-left">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Store') }}
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-left">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900 text-right">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Total') }}
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-900">
                                        <span class="sr-only">{{ __('Actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">
                                            {{ $transaction->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">
                                            {{ $transaction->store->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-gray-200">
                                            {{ $transaction->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right dark:text-gray-200">
                                            {{ number_format($transaction->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('transactions.show', $transaction) }}" 
                                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                {{ __('View') }}
                                            </a>
                                            <a href="{{ route('transactions.print', $transaction) }}" 
                                               class="ml-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300">
                                                {{ __('Print') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('No transactions found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>