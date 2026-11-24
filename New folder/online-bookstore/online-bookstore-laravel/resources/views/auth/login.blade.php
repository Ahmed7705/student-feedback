@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center mt-3">
    <div class="col-md-5">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">User Login</h5>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', 'admin@bookstore.com') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Enter password (e.g. 123456)" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="{{ route('register') }}" class="small text-decoration-none" style="color: var(--app-blue);">Create Account</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
