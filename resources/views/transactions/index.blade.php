<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Daftar Transaksi') }}
            </h2>
            @can('create transactions')
                <div class="mb-4">
                    <a href="{{ route('transactions.create') }}" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Tambah Data') }}
                </a>
            </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('transactions.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="store_id" class="block text-sm font-medium text-gray-700">Store</label>
                                <select id="store_id" name="store_id" class="form-select w-full">
                                    <option value="">{{ __('All Stores') }}</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-input w-full">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-input w-full">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">{{ __('Invoice Number') }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">{{ __('Store') }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">{{ __('Total Amount') }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->invoice_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->store->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($transaction->total_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="text-blue-600 hover:underline">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">{{ __('No transactions found.') }}</td>
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