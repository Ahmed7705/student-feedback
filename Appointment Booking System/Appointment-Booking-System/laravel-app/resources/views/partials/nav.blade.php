<nav class="main-nav" aria-label="Primary">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
    <a href="{{ route('appointments.create') }}" class="{{ request()->routeIs('appointments.create') ? 'active' : '' }}">Book</a>
    <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.index', 'appointments.edit') ? 'active' : '' }}">Manage</a>

    @auth
        <span class="nav-user">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="inline-form">
            @csrf
            <button type="submit" class="btn-link nav-muted">Sign Out</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="{{ request()->routeIs('login', 'register') ? 'active' : '' }}">Sign In</a>
    @endauth
</nav>
