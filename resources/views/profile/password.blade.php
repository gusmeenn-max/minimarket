@extends('layouts.app')

@section('title', 'Ubah Password')
@section('subtitle', 'Gunakan password yang kuat dan jangan dibagikan kepada orang lain.')

@section('content')
    <div class="row">
        <div class="col-lg-7 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Password Lama <span class="required">*</span></label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru <span class="required">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8" required autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Minimal 8 karakter.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-shield-check"></i>Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
