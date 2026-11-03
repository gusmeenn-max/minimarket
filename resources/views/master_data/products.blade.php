@extends('layouts.app')

@section('title', 'Daftar Barang')
@section('subtitle', 'Kelola master barang: kode, kategori, satuan, stok, dan batas stok minimum.')

@section('actions')
    <button type="button" class="btn btn-primary btn-create-barang"><i class="bi bi-plus-lg"></i>Tambah Barang</button>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table js-datatable" data-page-length="10">
                    <thead>
                        <tr><th>No</th><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Satuan</th><th>Stok</th><th>Min.</th><th>Status</th><th class="text-center no-sort">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @php
                                $pct = $product->minimum_stock > 0 ? min(100, round($product->stock / ($product->minimum_stock * 3) * 100)) : 100;
                                $barClass = $product->stock_status === 'Habis' ? 'low' : ($product->stock_status === 'Menipis' ? 'warn' : '');
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge badge-code">{{ $product->code }}</span></td>
                                <td>
                                    <strong class="text-dark">{{ $product->name }}</strong>
                                    @if ($product->description)<br><small class="text-muted">{{ Str::limit($product->description, 60) }}</small>@endif
                                </td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ $product->unit }}</td>
                                <td data-order="{{ $product->stock }}">
                                    <strong>{{ number_format($product->stock) }}</strong>
                                    <div class="stock-bar {{ $barClass }} mt-1"><span style="width:{{ $pct }}%"></span></div>
                                </td>
                                <td>{{ number_format($product->minimum_stock) }}</td>
                                <td><span class="badge {{ $product->stock_status_class }}">{{ $product->stock_status }}</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-icon btn-soft-info btn-detail-barang" title="Detail"
                                            data-category-id="{{ $product->category_id }}" data-code="{{ $product->code }}" data-name="{{ $product->name }}"
                                            data-unit="{{ $product->unit }}" data-stock="{{ $product->stock }}" data-minimum-stock="{{ $product->minimum_stock }}"
                                            data-description="{{ $product->description }}" data-transaction-count="{{ $product->stock_transactions_count }}"
                                            data-status="{{ $product->stock_status }}" data-status-class="{{ $product->stock_status_class }}"
                                            data-created-at="{{ $product->created_at?->format('d-m-Y H:i') ?? '-' }}" data-updated-at="{{ $product->updated_at?->format('d-m-Y H:i') ?? '-' }}"><i class="bi bi-eye"></i></button>
                                        <button type="button" class="btn btn-icon btn-soft-warning btn-edit-barang" title="Edit"
                                            data-id="{{ $product->id }}" data-category-id="{{ $product->category_id }}" data-code="{{ $product->code }}" data-name="{{ $product->name }}"
                                            data-unit="{{ $product->unit }}" data-stock="{{ $product->stock }}" data-minimum-stock="{{ $product->minimum_stock }}"
                                            data-description="{{ $product->description }}"><i class="bi bi-pencil-square"></i></button>
                                        <form action="{{ route('master-data.daftar-barang.destroy', $product->id) }}" method="POST" data-confirm="Yakin ingin menghapus barang ini?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-soft-danger" title="Hapus"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBarang" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formBarang" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="" disabled>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBarangLabel">Form Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div id="detailBarangInfo" class="detail-grid d-none mb-4">
                            <div><small>Status Stok</small><strong id="detailStatusStok">-</strong></div>
                            <div><small>Riwayat Transaksi</small><strong id="detailTransactionCount">0 transaksi</strong></div>
                            <div><small>Dibuat</small><strong id="detailCreatedAt">-</strong></div>
                            <div><small>Terakhir Diperbarui</small><strong id="detailUpdatedAt">-</strong></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="required">*</span></label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kode Barang <span class="required">*</span></label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Contoh: MNM-001" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nama Barang <span class="required">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Aqua Botol 600 ml" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Satuan <span class="required">*</span></label>
                                <input type="text" name="unit" id="unit" class="form-control" placeholder="pcs, botol, pak, dus" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stok <span class="required">*</span></label>
                                <input type="number" name="stock" id="stock" class="form-control" min="0" value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stok Minimum <span class="required">*</span></label>
                                <input type="number" name="minimum_stock" id="minimum_stock" class="form-control" min="0" value="10" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Keterangan singkat barang (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitBarang"><i class="bi bi-check2"></i><span>Simpan Barang</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const storeUrl = @json(route('master-data.daftar-barang.store'));
            const updateUrl = @json(route('master-data.daftar-barang.update', ':id'));
            const $modal = $('#modalBarang'), $form = $('#formBarang'), $method = $('#formMethod'), $btn = $('#btnSubmitBarang');
            const $fields = $('#category_id, #code, #name, #unit, #stock, #minimum_stock, #description');

            function reset() {
                $form[0].reset();
                $form.attr('action', storeUrl);
                $method.prop('disabled', true).val('');
                $fields.prop('disabled', false).prop('readonly', false);
                $('#unit').val('pcs'); $('#stock').val(0); $('#minimum_stock').val(10);
                $('#detailBarangInfo').addClass('d-none');
                $btn.removeClass('d-none').find('span').text('Simpan Barang');
            }
            function fill(d) {
                $('#category_id').val(d.categoryId); $('#code').val(d.code); $('#name').val(d.name); $('#unit').val(d.unit);
                $('#stock').val(d.stock); $('#minimum_stock').val(d.minimumStock); $('#description').val(d.description || '');
            }
            $('.btn-create-barang').on('click', function () { reset(); $('#modalBarangLabel').text('Tambah Barang'); $modal.modal('show'); });
            $(document).on('click', '.btn-edit-barang', function () {
                reset(); const d = $(this).data();
                $form.attr('action', updateUrl.replace(':id', d.id)); $method.prop('disabled', false).val('PUT');
                fill(d); $('#modalBarangLabel').text('Edit Barang'); $btn.find('span').text('Simpan Perubahan'); $modal.modal('show');
            });
            $(document).on('click', '.btn-detail-barang', function () {
                reset(); const d = $(this).data(); fill(d);
                $fields.prop('disabled', true);
                $('#detailStatusStok').html('<span class="badge ' + d.statusClass + '">' + d.status + '</span>');
                $('#detailTransactionCount').text(d.transactionCount + ' transaksi');
                $('#detailCreatedAt').text(d.createdAt); $('#detailUpdatedAt').text(d.updatedAt);
                $('#detailBarangInfo').removeClass('d-none'); $btn.addClass('d-none');
                $('#modalBarangLabel').text('Detail Barang'); $modal.modal('show');
            });
            $modal.on('hidden.bs.modal', reset);
        });
    </script>
@endpush
