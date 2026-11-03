<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/minimarket.css') }}?v=6">
</head>
<body>
<div class="auth-shell">
    <section class="auth-brand">
        <div class="d-flex align-items-center gap-3">
            <span class="brand-mark"><i class="bi bi-shop"></i></span>
            <strong style="font-size:18px;font-weight:800">{{ config('app.name') }}</strong>
        </div>
        <div>
            <h2>Kelola stok minimarket dengan rapi dan akurat.</h2>
            <p>Pantau barang masuk dan keluar, kenali stok yang menipis lebih awal, dan susun laporan persediaan dalam hitungan detik.</p>
            <ul class="auth-points">
                <li><i class="bi bi-box-seam"></i>Pencatatan barang masuk &amp; keluar real-time</li>
                <li><i class="bi bi-bell"></i>Peringatan stok menipis per barang</li>
                <li><i class="bi bi-file-earmark-bar-graph"></i>Laporan siap ekspor Excel &amp; PDF</li>
            </ul>
        </div>
        <small style="color:rgba(255,255,255,.6)">&copy; {{ date('Y') }} {{ config('app.name') }}</small>
    </section>

    <section class="auth-form">
        <div class="auth-box">
            <h1>Selamat datang</h1>
            <p class="text-muted mb-4">Masuk untuk mengelola persediaan barang.</p>

            @if (session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><div>{{ session('success') }}</div></div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i><div>{{ session('error') }}</div></div>
            @endif

            <form action="{{ route('login.process') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrap">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" required autocomplete="current-password">
                        <button type="button" class="password-toggle" data-toggle-password="#password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Masuk ke Sistem <i class="bi bi-arrow-right"></i></button>
            </form>
        </div>
    </section>
</div>
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/minimarket.js') }}?v=4"></script>
</body>
</html>
