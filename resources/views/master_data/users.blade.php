@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('subtitle', 'Kelola akun yang dapat mengakses sistem persediaan.')

@section('actions')
    <button type="button" class="btn btn-primary btn-create"><i class="bi bi-person-plus"></i>Tambah Pengguna</button>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table js-datatable">
                    <thead><tr><th>No</th><th>Pengguna</th><th>Email</th><th>Dibuat</th><th class="text-center no-sort">Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar" style="width:34px;height:34px;font-size:12px">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                                        <div><strong class="text-dark">{{ $user->name }}</strong>@if (auth()->id() === $user->id) <span class="badge badge-soft-success ms-1">Anda</span>@endif</div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td data-order="{{ $user->created_at?->format('Y-m-d') }}">{{ $user->created_at?->format('d-m-Y') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-icon btn-soft-warning btn-edit" title="Edit"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}"><i class="bi bi-pencil-square"></i></button>
                                        @if (auth()->id() !== $user->id)
                                            <form action="{{ route('master-data.manajemen-pengguna.destroy', $user->id) }}" method="POST" data-confirm="Yakin ingin menghapus pengguna ini?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-soft-danger" title="Hapus"><i class="bi bi-trash3"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formUser" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="" disabled>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Tambah Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Password <span class="required" id="pwReq">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" minlength="6" autocomplete="new-password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-text mt-2" id="pwHint">Minimal 6 karakter.</div>
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
            const storeUrl = @json(route('master-data.manajemen-pengguna.store'));
            const updateUrl = @json(route('master-data.manajemen-pengguna.update', ':id'));
            const $m = $('#modalUser'), $f = $('#formUser'), $method = $('#formMethod');
            function reset() {
                $f[0].reset(); $f.attr('action', storeUrl); $method.prop('disabled', true).val('');
                $('#password').prop('required', true); $('#pwReq').show(); $('#pwHint').text('Minimal 6 karakter.');
            }
            $('.btn-create').on('click', function () { reset(); $('#modalLabel').text('Tambah Pengguna'); $m.modal('show'); });
            $(document).on('click', '.btn-edit', function () {
                reset(); const d = $(this).data();
                $f.attr('action', updateUrl.replace(':id', d.id)); $method.prop('disabled', false).val('PUT');
                $('#name').val(d.name); $('#email').val(d.email);
                $('#password').prop('required', false); $('#pwReq').hide(); $('#pwHint').text('Kosongkan jika tidak ingin mengubah password.');
                $('#modalLabel').text('Edit Pengguna'); $m.modal('show');
            });
            $m.on('hidden.bs.modal', reset);
        });
    </script>
@endpush
