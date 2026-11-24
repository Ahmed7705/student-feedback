@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-5 col-lg-5">
        <div class="panel-box p-4 p-md-5" style="min-height: 480px;">
            <div class="mb-4 pb-3 border-bottom">
                <h4 class="fw-bold mb-2" style="color: var(--theme-main);">Sign In</h4>
                <div class="text-secondary small">Enter your credentials to access your account</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small text-center mb-4">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label small fw-bold mb-2">Email</label>
                    <input type="email" name="email" class="form-control py-2" value="{{ old('email', 'admin@bookstore.com') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold mb-2">Password</label>
                    <input type="password" name="password" class="form-control py-2" placeholder="Password (e.g. 123456)" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 mt-4 border-top">
                    <a href="{{ route('register') }}" class="small text-decoration-none fw-semibold" style="color: var(--theme-main);">Create an account</a>
                    <button type="submit" class="btn btn-store px-4 py-2">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
