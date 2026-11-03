@php
    $userName = auth()->user()->name ?? 'Admin';
    $initials = collect(explode(' ', trim($userName)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $items = [
        ['Dashboard', 'dashboard', 'bi-grid-1x2-fill', 'dashboard'],
        ['Persediaan Barang', 'inventory.index', 'bi-box-seam-fill', 'inventory.*'],
    ];
    $master = [
        ['Kategori Barang', 'master-data.kategori-barang.index', 'bi-tags-fill', 'master-data.kategori-barang.*'],
        ['Daftar Barang', 'master-data.daftar-barang.index', 'bi-basket3-fill', 'master-data.daftar-barang.*'],
        ['Manajemen Pengguna', 'master-data.manajemen-pengguna.index', 'bi-people-fill', 'master-data.manajemen-pengguna.*'],
    ];
@endphp
<aside class="app-sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <span class="brand-mark"><i class="bi bi-shop"></i></span>
        <span class="brand-text"><strong>{{ config('app.name') }}</strong><small>Sistem Persediaan Barang</small></span>
    </a>

    <nav class="sidebar-nav">
        <div class="nav-section">Menu Utama</div>
        @foreach ($items as [$label, $route, $icon, $pattern])
            <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                <i class="bi {{ $icon }}"></i>{{ $label }}
            </a>
        @endforeach

        <div class="nav-section">Data Master</div>
        @foreach ($master as [$label, $route, $icon, $pattern])
            <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                <i class="bi {{ $icon }}"></i>{{ $label }}
            </a>
        @endforeach

        <div class="nav-section">Laporan</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>Laporan Persediaan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <span class="avatar">{{ $initials }}</span>
            <span class="meta"><strong>{{ $userName }}</strong><small>Administrator</small></span>
        </div>
    </div>
</aside>
