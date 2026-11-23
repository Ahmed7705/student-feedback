@extends('layouts.main')

@section('title', 'Sign In')

@section('content')
<h1 class="page-title">Sign In</h1>
<p class="page-subtitle">Sign in to view or manage appointments.</p>

<div class="card form-card">
    @if ($errors->any())
        <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" novalidate>
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Sign In</button>
        </div>
    </form>

    <p class="auth-switch">Need an account? <a href="{{ route('register') }}">Create one</a></p>
</div>
@endsection
