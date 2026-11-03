<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/minimarket.css') }}?v=6">
    @stack('styles')
</head>
<body>
    @include('partials.sidebar')
    <div class="sidebar-backdrop"></div>

    <div class="app-main">
        @include('partials.navbar')

        <main class="app-content">
            <div class="page-header">
                <div>
                    <div class="breadcrumb-lite">
                        {{ config('app.name') }} <i class="bi bi-chevron-right"></i> @yield('breadcrumb', View::yieldContent('title'))
                    </div>
                    <h1>@yield('title')</h1>
                    @hasSection('subtitle')<p>@yield('subtitle')</p>@endif
                </div>
                @yield('actions')
            </div>

            @include('partials.flash')
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/minimarket.js') }}?v=4"></script>
    @stack('scripts')
</body>
</html>
