@php
    $userName = auth()->user()->name ?? 'Admin';
    $initials = collect(explode(' ', trim($userName)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
@endphp
<header class="app-topbar">
    <button class="topbar-toggle" type="button" data-sidebar-toggle aria-label="Buka menu"><i class="bi bi-list"></i></button>
    <div class="topbar-date"><i class="bi bi-calendar3"></i>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</div>

    <div class="topbar-actions">
        <div class="dropdown">
            <a href="#" class="user-chip dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">{{ $initials }}</span>
                <span class="d-none d-sm-inline">{{ $userName }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i>Profil Saya</a></li>
                <li><a class="dropdown-item" href="{{ route('profile.password') }}"><i class="bi bi-shield-lock"></i>Ubah Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="mb-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i>Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
