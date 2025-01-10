<x-app-layout>
<div class="container">
    <h1>Laporan Penjualan</h1>
    <form method="GET" action="{{ route('reports.index') }}">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="start_date">Tanggal Mulai:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">Tanggal Selesai:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jumlah Transaksi</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $key => $sale)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $sale->date }}</td>
                    <td>{{ $sale->transactions_count }}</td>
                    <td>Rp {{ number_format($sale->total_sales, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>