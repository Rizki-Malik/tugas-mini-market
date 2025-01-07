<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Statistik Hari ini --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Penjualan Hari ini</h3>
                        <p class="text-3xl font-bold text-indigo-600">
                            Rp {{ number_format($todayStats['total_sales'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Transaksi Hari ini</h3>
                        <p class="text-3xl font-bold text-indigo-600">
                            {{ $todayStats['transaction_count'] }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Rata-rata Penjualan Hari ini</h3>
                        <p class="text-3xl font-bold text-indigo-600">
                            Rp {{ number_format($todayStats['average_sale'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Chart Penjualan Mingguan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Penjualan Mingguan</h3>
                        <canvas id="weeklySalesChart" height="200"></canvas>
                    </div>
                </div>

                {{-- Low Stock Items --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-700">Low Stock Items</h3>
                            @can('view inventory')
                                <a href="{{ route('inventory.low-stock') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Lihat Detail
                                </a>
                            @endcan
                        </div>
                        <div class="space-y-4">
                            @forelse($lowStockItems as $item)
                                <div class="flex justify-between items-center border-b pb-2">
                                    <div>
                                        <h4 class="font-medium">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $item->store->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium {{ $item->quantity == 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                            {{ $item->quantity }} units
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No low stock items</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('weeklySalesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($weeklyStats->toArray())) !!},
                datasets: [{
                    label: 'Daily Sales',
                    data: {!! json_encode(array_values($weeklyStats->toArray())) !!},
                    borderColor: 'rgb(79, 70, 229)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>