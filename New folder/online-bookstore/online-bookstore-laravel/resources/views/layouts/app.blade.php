<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Catalog') | Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --theme-main: #5c3d2e;
            --theme-dark: #432b20;
            --theme-bg: #f8f5f1;
            --theme-ink: #2b211c;
            --theme-line: #e2d6c8;
        }
        body {
            background-color: var(--theme-bg);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--theme-ink);
        }
        .top-bar {
            background-color: var(--theme-main);
            padding: 0.65rem 0;
        }
        .store-brand {
            font-size: 1.18rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 0.2px;
        }
        .store-brand:hover {
            color: #f3e9df;
        }
        .top-bar .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.35rem 0.85rem !important;
            border-bottom: 2px solid transparent;
        }
        .top-bar .nav-link:hover {
            color: #ffffff !important;
        }
        .top-bar .nav-link.active {
            color: #ffffff !important;
            border-bottom: 2px solid #ffffff;
            font-weight: 600;
        }
        .btn-store {
            background-color: var(--theme-main);
            color: #ffffff;
            border: 1px solid var(--theme-main);
            font-weight: 500;
            border-radius: 4px;
        }
        .btn-store:hover {
            background-color: var(--theme-dark);
            border-color: var(--theme-dark);
            color: #ffffff;
        }
        .panel-box {
            border: 1px solid var(--theme-line);
            border-radius: 6px;
            background-color: #ffffff;
            box-shadow: none !important;
        }
        .book-item {
            border: 1px solid var(--theme-line);
            border-radius: 6px;
            background-color: #ffffff;
            border-top: 3px solid var(--theme-main);
        }
        .form-control,
        .form-select {
            border-color: var(--theme-line);
            border-radius: 4px;
        }
        .form-control:focus,
        .form-select:focus,
        .form-check-input:focus,
        .btn:focus,
        .btn:active {
            box-shadow: none !important;
            outline: none !important;
            border-color: var(--theme-main) !important;
        }
        .form-check-input:checked {
            background-color: var(--theme-main);
            border-color: var(--theme-main);
        }
        .cost-label {
            color: var(--theme-main);
            font-weight: 700;
            font-size: 0.96rem;
        }
        .table th {
            color: var(--theme-main);
            font-weight: 600;
            border-bottom-width: 1px;
        }
        .notice-center {
            max-width: 520px;
            margin: 0 auto 1rem auto;
            text-align: center;
        }
        .bottom-footer {
            background-color: var(--theme-main);
            color: rgba(255, 255, 255, 0.92);
            padding: 0.7rem 0;
            font-size: 0.82rem;
            text-align: center;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark top-bar sticky-top">
        <div class="container">
            <a class="store-brand" href="{{ route('home') }}">Online Bookstore</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuLinks">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuLinks">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-1 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Book Catalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('books.create') ? 'active' : '' }}" href="{{ route('books.create') }}">Add Book</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->routeIs('books.manage') || request()->routeIs('books.edit')) ? 'active' : '' }}" href="{{ route('books.manage') }}">Manage Books</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link p-0 border-0">Sign Out ({{ Auth::user()->name }})</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2 small notice-center" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bottom-footer mt-auto">
        <div class="container">
            Online Bookstore &mdash; COMP-315 Web Programming &mdash; Jazan University
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updatePriceSum() {
            var items = document.querySelectorAll('.pick-book:checked');
            var sum = 0;
            for (var i = 0; i < items.length; i++) {
                sum += parseFloat(items[i].getAttribute('data-cost') || 0);
            }
            var box = document.getElementById('totalBox');
            if (box) {
                box.textContent = sum.toFixed(2) + ' SAR';
            }
        }
    </script>
</body>
</html>
