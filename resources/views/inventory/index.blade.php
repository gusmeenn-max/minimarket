@extends('layouts.app')

@section('title', 'Persediaan Barang')
@section('subtitle', 'Catat barang masuk dan keluar, serta pantau riwayat pergerakan stok.')

@section('actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBarangMasuk"><i class="bi bi-box-arrow-in-down"></i>Barang Masuk</button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalBarangKeluar"><i class="bi bi-box-arrow-up"></i>Barang Keluar</button>
    </div>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        @foreach ([['Jenis Barang', $totalProducts, 'bi-basket3-fill', 'green'], ['Total Stok', $totalStock, 'bi-boxes', 'blue'], ['Stok Menipis', $lowStockCount, 'bi-exclamation-triangle-fill', 'amber'], ['Stok Habis', $emptyStockCount, 'bi-x-octagon-fill', 'red']] as [$label, $val, $icon, $tone])
            <div class="col-6 col-xl-3">
                <div class="card stat-card mb-0">
                    <span class="stat-icon {{ $tone }}"><i class="bi {{ $icon }}"></i></span>
                    <div><div class="stat-label">{{ $label }}</div><div class="stat-value">{{ number_format($val) }}</div></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Riwayat Transaksi Persediaan</h4>
            <p class="card-sub">Seluruh transaksi barang masuk dan keluar beserta sisa stok setelah transaksi.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table js-datatable">
                    <thead>
                        <tr><th>No</th><th>Tanggal</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Masuk</th><th>Keluar</th><th>Sisa Stok</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tx)
                            @php $after = (int) $tx->stock_after; $min = $tx->product->minimum_stock ?? 0; @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-nowrap" data-order="{{ $tx->transaction_date?->format('Y-m-d') }}">{{ $tx->transaction_date?->format('d/m/Y') }}</td>
                                <td><span class="badge badge-code">{{ $tx->product->code ?? '-' }}</span></td>
                                <td>
                                    <strong class="text-dark">{{ $tx->product->name ?? '-' }}</strong>
                                    @if ($tx->note)<br><small class="text-muted">{{ Str::limit($tx->note, 50) }}</small>@endif
                                </td>
                                <td>{{ $tx->product->category->name ?? '-' }}</td>
                                <td>@if ($tx->type === 'masuk')<span class="text-success fw-bold">+{{ number_format($tx->quantity) }}</span> <small class="text-muted">{{ $tx->product->unit ?? '' }}</small>@else<span class="text-muted">–</span>@endif</td>
                                <td>@if ($tx->type === 'keluar')<span class="text-danger fw-bold">−{{ number_format($tx->quantity) }}</span> <small class="text-muted">{{ $tx->product->unit ?? '' }}</small>@else<span class="text-muted">–</span>@endif</td>
                                <td><strong>{{ number_format($after) }}</strong> <small class="text-muted">{{ $tx->product->unit ?? '' }}</small></td>
                                <td>
                                    @if ($after <= 0)<span class="badge badge-soft-danger">Habis</span>
                                    @elseif ($after <= $min)<span class="badge badge-soft-warning">Menipis</span>
                                    @else<span class="badge badge-soft-success">Aman</span>@endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach (['masuk' => ['Barang Masuk', 'success', 'Contoh: Penerimaan dari supplier'], 'keluar' => ['Barang Keluar', 'danger', 'Contoh: Retur / barang rusak / kadaluarsa']] as $type => [$label, $color, $ph])
        <div class="modal fade" id="modalBarang{{ ucfirst($type) }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('inventory.transaction.store') }}" method="POST" id="form_{{ $type }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Form {{ $label }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal <span class="required">*</span></label>
                                    <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Barang <span class="required">*</span></label>
                                    <select name="product_id" id="product_{{ $type }}" class="form-select" required>
                                        <option value="">Pilih barang</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-unit="{{ e($product->unit) }}" data-stock="{{ $product->stock }}" {{ $type === 'keluar' && $product->stock <= 0 ? 'disabled' : '' }}>
                                                {{ $product->code }} — {{ $product->name }} (stok {{ $product->stock }} {{ $product->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jumlah {{ $type === 'masuk' ? 'Masuk' : 'Keluar' }} <span class="required">*</span></label>
                                    <input type="number" name="quantity" id="qty_{{ $type }}" class="form-control" min="1" placeholder="Contoh: 24" required>
                                    <div class="form-text" id="info_{{ $type }}">Pilih barang untuk melihat stok saat ini.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" id="unit_{{ $type }}" class="form-control" placeholder="Otomatis dari barang" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="note" class="form-control" rows="3" placeholder="{{ $ph }}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-{{ $color }}"><i class="bi bi-check2"></i>Simpan {{ $label }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(function () {
            ['masuk', 'keluar'].forEach(function (type) {
                const $sel = $('#product_' + type), $qty = $('#qty_' + type), $info = $('#info_' + type), $unit = $('#unit_' + type);
                const idle = type === 'masuk' ? 'Pilih barang untuk melihat stok saat ini.' : 'Pilih barang untuk melihat batas stok keluar.';
                $sel.on('change', function () {
                    const o = $(this).find(':selected'), unit = o.data('unit') || '', stock = parseInt(o.data('stock')) || 0;
                    $unit.val(unit);
                    if (!$(this).val()) { $qty.removeAttr('max'); $info.text(idle); return; }
                    if (type === 'keluar') { $qty.attr('max', stock); $info.text('Maksimal barang keluar: ' + stock + ' ' + unit); }
                    else { $info.text('Stok saat ini: ' + stock + ' ' + unit); }
                });
                $('#modalBarang' + type.charAt(0).toUpperCase() + type.slice(1)).on('hidden.bs.modal', function () {
                    $('#form_' + type)[0].reset(); $unit.val(''); $qty.removeAttr('max'); $info.text(idle);
                });
            });
        });
    </script>
@endpush
