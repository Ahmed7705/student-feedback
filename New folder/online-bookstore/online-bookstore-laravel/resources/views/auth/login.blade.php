@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="row justify-content-center my-4">
    <div class="col-md-5">
        <div class="panel-box p-4 p-md-5">
            <h4 class="fw-bold mb-1" style="color: var(--theme-main);">Sign In</h4>
            <div class="text-secondary small mb-4">Enter your credentials to access your account</div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small text-center">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', 'admin@bookstore.com') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password (e.g. 123456)" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="{{ route('register') }}" class="small text-decoration-none" style="color: var(--theme-main);">Create an account</a>
                    <button type="submit" class="btn btn-store px-4 py-2">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
