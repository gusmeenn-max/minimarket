@extends('layouts.app')

@section('title', 'Laporan Persediaan')
@section('subtitle', 'Rekap transaksi barang masuk dan keluar berdasarkan periode, siap diekspor.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/buttons.bootstrap5.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Jenis Transaksi</label>
                    <select name="type" class="form-select">
                        <option value="">Semua transaksi</option>
                        <option value="masuk" @selected(request('type') == 'masuk')>Barang Masuk</option>
                        <option value="keluar" @selected(request('type') == 'keluar')>Barang Keluar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i>Terapkan</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-light">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        @foreach ([['Total Transaksi', $totalTransactions, 'bi-receipt', 'blue'], ['Total Barang Masuk', $totalIn, 'bi-box-arrow-in-down', 'green'], ['Total Barang Keluar', $totalOut, 'bi-box-arrow-up', 'amber']] as [$label, $val, $icon, $tone])
            <div class="col-md-4">
                <div class="card stat-card mb-0">
                    <span class="stat-icon {{ $tone }}"><i class="bi {{ $icon }}"></i></span>
                    <div><div class="stat-label">{{ $label }}</div><div class="stat-value">{{ number_format($val) }}</div></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data Transaksi</h4>
            <p class="card-sub">Gunakan tombol di atas tabel untuk mengekspor ke Excel, PDF, atau mencetak.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="laporanTable" class="table">
                    <thead><tr><th>No</th><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Jenis</th><th>Jumlah</th><th>Petugas</th><th>Catatan</th></tr></thead>
                    <tbody>
                        @foreach ($transactions as $tx)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-nowrap" data-order="{{ $tx->transaction_date?->format('Y-m-d') }}">{{ $tx->transaction_date?->format('d-m-Y') }}</td>
                                <td><span class="badge badge-code">{{ $tx->product->code ?? '-' }}</span></td>
                                <td>{{ $tx->product->name ?? '-' }}</td>
                                <td>{{ $tx->product->category->name ?? '-' }}</td>
                                <td>@if ($tx->type == 'masuk')<span class="badge badge-soft-success">Barang Masuk</span>@else<span class="badge badge-soft-danger">Barang Keluar</span>@endif</td>
                                <td>{{ number_format($tx->quantity) }} {{ $tx->product->unit ?? '' }}</td>
                                <td>{{ $tx->user->name ?? '-' }}</td>
                                <td>{{ Str::limit($tx->note, 60) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/buttons.print.min.js') }}"></script>
    <script>
        $(function () {
            $('#laporanTable').DataTable({
                language: window.mmDataTableLang,
                pageLength: 25,
                order: [],
                layout: {
                    topStart: {
                        buttons: [
                            { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel"></i> Excel', className: 'btn btn-light-soft btn-sm', title: 'Laporan Persediaan Minimarket' },
                            { extend: 'pdfHtml5', text: '<i class="bi bi-file-earmark-pdf"></i> PDF', className: 'btn btn-light-soft btn-sm', title: 'Laporan Persediaan Minimarket', orientation: 'landscape' },
                            { extend: 'print', text: '<i class="bi bi-printer"></i> Cetak', className: 'btn btn-light-soft btn-sm', title: 'Laporan Persediaan Minimarket' }
                        ]
                    }
                }
            });
        });
    </script>
@endpush
