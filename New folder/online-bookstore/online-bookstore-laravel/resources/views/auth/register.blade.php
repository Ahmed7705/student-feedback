@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-5 col-lg-5">
        <div class="panel-box p-4 p-md-5" style="min-height: 480px;">
            <div class="mb-4 pb-3 border-bottom">
                <h4 class="fw-bold mb-2" style="color: var(--theme-main);">Register Account</h4>
                <div class="text-secondary small">Create an account to manage your bookstore catalog</div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small text-center mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold mb-2">Name</label>
                    <input type="text" name="name" class="form-control py-2" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold mb-2">Email</label>
                    <input type="email" name="email" class="form-control py-2" value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold mb-2">Password</label>
                    <input type="password" name="password" class="form-control py-2" placeholder="6+ characters" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4 mt-4 border-top">
                    <a href="{{ route('login') }}" class="small text-decoration-none fw-semibold" style="color: var(--theme-main);">Sign In</a>
                    <button type="submit" class="btn btn-store px-4 py-2">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
