@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan stok, pergerakan barang, dan kondisi persediaan minimarket hari ini.')

@section('actions')
    <div class="d-flex gap-2">
        <a href="{{ route('inventory.index') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i>Catat Transaksi</a>
        <a href="{{ route('reports.index') }}" class="btn btn-light-soft"><i class="bi bi-file-earmark-bar-graph"></i>Laporan</a>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        @php
            $stats = [
                ['Total Jenis Barang', number_format($totalProducts), 'Item terdaftar di master data', 'bi-basket3-fill', 'green'],
                ['Total Stok', number_format($totalStock), 'Seluruh unit tersedia di rak & gudang', 'bi-boxes', 'blue'],
                ['Barang Masuk', number_format($totalStockIn), 'Akumulasi seluruh penerimaan', 'bi-box-arrow-in-down', 'amber'],
                ['Stok Menipis', number_format($lowStockCount), 'Item di bawah / sama dengan stok minimum', 'bi-exclamation-triangle-fill', 'red'],
            ];
        @endphp
        @foreach ($stats as [$label, $value, $note, $icon, $tone])
            <div class="col-sm-6 col-xl-3">
                <div class="card stat-card h-100 mb-0">
                    <span class="stat-icon {{ $tone }}"><i class="bi {{ $icon }}"></i></span>
                    <div>
                        <div class="stat-label">{{ $label }}</div>
                        <div class="stat-value">{{ $value }}</div>
                        <div class="stat-note">{{ $note }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card h-100 mb-0">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="card-title">Pergerakan Stok Bulan Ini</h4>
                        <p class="card-sub">Perbandingan barang masuk dan keluar per tanggal transaksi.</p>
                    </div>
                    <span class="badge badge-soft-secondary">{{ now()->translatedFormat('F Y') }}</span>
                </div>
                <div class="card-body">
                    @if ($chartLabels->count() > 0)
                        <div style="position:relative;height:300px"><canvas id="stockChart"></canvas></div>
                    @else
                        <div class="empty-state"><i class="bi bi-graph-up"></i>Belum ada transaksi pada bulan ini.</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 mb-0">
                <div class="card-header">
                    <h4 class="card-title">Transaksi Terbaru</h4>
                    <p class="card-sub">5 aktivitas persediaan terakhir.</p>
                </div>
                <div class="card-body pt-2">
                    <ul class="list-tx">
                        @forelse ($latestTransactions as $tx)
                            <li>
                                <div class="d-flex gap-3">
                                    <span class="tx-dot {{ $tx->type === 'masuk' ? 'in' : 'out' }}">
                                        <i class="bi {{ $tx->type === 'masuk' ? 'bi-arrow-down-left' : 'bi-arrow-up-right' }}"></i>
                                    </span>
                                    <div>
                                        <strong class="d-block text-dark">{{ $tx->product->name ?? '-' }}</strong>
                                        <small class="text-muted">{{ $tx->transaction_date?->format('d M Y') }} · {{ $tx->user->name ?? '-' }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <strong class="{{ $tx->type === 'masuk' ? 'text-success' : 'text-danger' }}">{{ $tx->type === 'masuk' ? '+' : '−' }}{{ number_format($tx->quantity) }}</strong>
                                    <small class="d-block text-muted">{{ $tx->product->unit ?? '' }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="d-block"><div class="empty-state"><i class="bi bi-inbox"></i>Belum ada transaksi.</div></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @foreach ([['Perlu Segera Restok', 'Barang dengan stok di bawah atau sama dengan batas minimum.', $topMinProducts, 'Semua stok masih aman.'], ['Stok Terbanyak', 'Lima barang dengan jumlah stok terbesar.', $topMaxProducts, 'Belum ada data barang.']] as [$title, $sub, $rows, $empty])
            <div class="col-lg-6">
                <div class="card mb-0 h-100">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title }}</h4>
                        <p class="card-sub">{{ $sub }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Barang</th><th>Kategori</th><th>Stok</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse ($rows as $product)
                                    <tr>
                                        <td><strong class="text-dark">{{ $product->name }}</strong><br><small class="text-muted">{{ $product->code }}</small></td>
                                        <td>{{ $product->category->name ?? '-' }}</td>
                                        <td><strong>{{ number_format($product->stock) }}</strong> <small class="text-muted">{{ $product->unit }}</small></td>
                                        <td><span class="badge {{ $product->stock_status_class }}">{{ $product->stock_status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4"><div class="empty-state py-4"><i class="bi bi-check2-circle"></i>{{ $empty }}</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/chartjs/chart.umd.min.js') }}"></script>
    <script>
        $(function () {
            const labels = @json($chartLabels);
            const el = document.getElementById('stockChart');
            if (!el || !labels.length) return;
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#6b7a72';
            new Chart(el, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: 'Barang Masuk', data: @json($chartIn), backgroundColor: '#0d9157', borderRadius: 6, maxBarThickness: 22 },
                        { label: 'Barang Keluar', data: @json($chartOut), backgroundColor: '#f59e0b', borderRadius: 6, maxBarThickness: 22 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', align: 'start', labels: { usePointStyle: true, boxWidth: 8 } } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#eef2f0' } }
                    }
                }
            });
        });
    </script>
@endpush
