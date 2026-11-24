<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Catalog') - Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --app-blue: #2b6cb0;
            --app-blue-hover: #245a94;
            --app-bg: #f0f5fa;
            --app-text: #1e293b;
            --app-muted: #64748b;
            --app-border: #d5e2ef;
        }
        body {
            background-color: var(--app-bg);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--app-text);
        }
        .site-header {
            background-color: var(--app-blue);
            padding: 0.7rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }
        .brand-title {
            font-size: 1.14rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
        }
        .brand-title:hover {
            color: #e6f0fa;
        }
        .site-header .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.38rem 0.9rem !important;
            border-radius: 5px;
        }
        .site-header .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12);
        }
        .site-header .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.22);
            font-weight: 600;
        }
        .btn-blue {
            background-color: var(--app-blue);
            color: #ffffff;
            border: 1px solid var(--app-blue);
            font-weight: 500;
            border-radius: 5px;
        }
        .btn-blue:hover {
            background-color: var(--app-blue-hover);
            border-color: var(--app-blue-hover);
            color: #ffffff;
        }
        .card {
            border: 1px solid var(--app-border);
            border-radius: 7px;
            background-color: #ffffff;
            box-shadow: none !important;
        }
        .shadow-sm {
            box-shadow: none !important;
        }
        .form-control,
        .form-select {
            border-color: var(--app-border);
            border-radius: 5px;
        }
        .form-control:focus,
        .form-select:focus,
        .form-check-input:focus,
        .btn:focus,
        .btn:active {
            box-shadow: none !important;
            outline: none !important;
            border-color: var(--app-blue) !important;
        }
        .form-check-input:checked {
            background-color: var(--app-blue);
            border-color: var(--app-blue);
        }
        .price-text {
            color: var(--app-blue);
            font-weight: 700;
            font-size: 0.95rem;
        }
        .table th {
            color: var(--app-blue);
            font-weight: 600;
            border-bottom-width: 1px;
        }
        .site-footer {
            background-color: var(--app-blue);
            color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 0;
            font-size: 0.82rem;
        }
        .site-footer strong {
            color: #ffffff;
            font-weight: 600;
        }
        .footer-sep {
            color: rgba(255, 255, 255, 0.45);
            margin: 0 0.45rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <header class="navbar navbar-expand-lg navbar-dark site-header sticky-top">
        <div class="container">
            <a class="brand-title" href="{{ route('home') }}">Online Bookstore</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-1 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Catalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('books.create') ? 'active' : '' }}" href="{{ route('books.create') }}">Add Book</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->routeIs('books.manage') || request()->routeIs('books.edit')) ? 'active' : '' }}" href="{{ route('books.manage') }}">Manage Books</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">Logout ({{ Auth::user()->name }})</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </header>

    <main class="container py-4 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer mt-auto">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div>
                <strong>COMP-315</strong> <span class="footer-sep">|</span> Online Bookstore
            </div>
            <div>
                Jazan University
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function calcTotal() {
            var checks = document.querySelectorAll('.book-check:checked');
            var total = 0;
            for (var i = 0; i < checks.length; i++) {
                total += parseFloat(checks[i].getAttribute('data-price') || 0);
            }
            var el = document.getElementById('selectedTotal');
            if (el) {
                el.textContent = total.toFixed(2) + ' SAR';
            }
        }
    </script>
</body>
</html>
