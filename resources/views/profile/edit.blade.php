@extends('layouts.app')

@section('title', 'Profil Saya')
@section('subtitle', 'Perbarui nama dan alamat email akun Anda.')

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card text-center mb-0">
                <div class="card-body py-4">
                    <span class="avatar mx-auto mb-3" style="width:76px;height:76px;font-size:28px">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge badge-soft-success">Administrator</span>
                    <hr>
                    <a href="{{ route('profile.password') }}" class="btn btn-light-soft w-100"><i class="bi bi-shield-lock"></i>Ubah Password</a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-0">
                <div class="card-header"><h4 class="card-title">Informasi Akun</h4></div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i>Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
