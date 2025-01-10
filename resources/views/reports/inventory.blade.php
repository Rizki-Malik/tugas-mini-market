<x-app-layout>
    <div class="container">
    <h1>Laporan Inventaris</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Stok Tersedia</th>
                <th>Stok Minimum</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventory as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>{{ $item->min_stock }}</td>
                    <td>
                        @if ($item->stock < $item->min_stock)
                            <span class="badge bg-danger">Stok Rendah</span>
                        @else
                            <span class="badge bg-success">Stok Aman</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
