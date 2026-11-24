@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center mt-3">
    <div class="col-md-5">
        <div class="card p-4 bg-white">
            <h5 class="fw-bold mb-3" style="color: var(--app-blue);">Create Account</h5>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Minimum 6 characters" required>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="{{ route('login') }}" class="small text-decoration-none" style="color: var(--app-blue);">Already have an account?</a>
                    <button type="submit" class="btn btn-blue btn-sm px-4">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
