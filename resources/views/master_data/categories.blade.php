@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('subtitle', 'Kelompokkan barang minimarket, seperti makanan ringan, minuman, sembako, dan lainnya.')

@section('actions')
    <button type="button" class="btn btn-primary btn-create"><i class="bi bi-plus-lg"></i>Tambah Kategori</button>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table js-datatable">
                    <thead><tr><th>No</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Barang</th><th class="text-center no-sort">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong class="text-dark">{{ $category->name }}</strong></td>
                                <td class="text-muted">{{ $category->description ? Str::limit($category->description, 80) : '–' }}</td>
                                <td><span class="badge badge-soft-info">{{ $category->products_count }} barang</span></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-icon btn-soft-warning btn-edit" title="Edit"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}"><i class="bi bi-pencil-square"></i></button>
                                        <form action="{{ route('master-data.kategori-barang.destroy', $category->id) }}" method="POST" data-confirm="Yakin ingin menghapus kategori ini?">
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

    <div class="modal fade" id="modalKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formKategori" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="" disabled>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="required">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Minuman" required>
                        </div>
                        <div>
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Keterangan singkat kategori (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const storeUrl = @json(route('master-data.kategori-barang.store'));
            const updateUrl = @json(route('master-data.kategori-barang.update', ':id'));
            const $m = $('#modalKategori'), $f = $('#formKategori'), $method = $('#formMethod');
            function reset() { $f[0].reset(); $f.attr('action', storeUrl); $method.prop('disabled', true).val(''); }
            $('.btn-create').on('click', function () { reset(); $('#modalLabel').text('Tambah Kategori'); $m.modal('show'); });
            $(document).on('click', '.btn-edit', function () {
                reset(); const d = $(this).data();
                $f.attr('action', updateUrl.replace(':id', d.id)); $method.prop('disabled', false).val('PUT');
                $('#name').val(d.name); $('#description').val(d.description || '');
                $('#modalLabel').text('Edit Kategori'); $m.modal('show');
            });
            $m.on('hidden.bs.modal', reset);
        });
    </script>
@endpush
