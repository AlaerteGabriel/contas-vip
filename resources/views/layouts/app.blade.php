<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel - Carteira Financeira')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .gradient-card { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); }
        .icon-box { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
        .action-btn { transition: transform 0.2s, box-shadow 0.2s; border-radius: 16px; }
        .action-btn:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important; }
        .transaction-row:last-child td { border-bottom: 0; }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Top Navbar -->
    @include('layouts.partials.navbar')

    <!-- Main Content -->
    <div class="container mt-4 mb-5">
        <div class="row g-4">

            <!-- Left Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                @include('layouts.partials.sidebar')
            </div>

            <!-- Right Content -->
            <div class="col-lg-9">
                <x-alert />
                @yield('content')
            </div>

        </div>
    </div>

    <!-- Modals -->
    @include('layouts.partials.modals')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
