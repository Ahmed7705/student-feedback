@extends('layouts.main')

@section('title', 'Register')

@section('content')
<h1 class="page-title">Create Account</h1>
<p class="page-subtitle">Register to view and manage your bookings.</p>

<div class="card form-card">
    @if ($errors->any())
        <div class="alert alert-error" role="alert">
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}" novalidate>
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </form>

    <p class="auth-switch">Already registered? <a href="{{ route('login') }}">Sign In</a></p>
</div>
@endsection
