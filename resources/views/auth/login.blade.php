@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="cl-card p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-check" style="font-size:2rem; color:var(--cl-primary);"></i>
                    <h4 style="font-weight:700; margin-top:0.5rem;">Welcome back</h4>
                    <p style="color:var(--cl-gray); font-size:0.9rem;">Sign in to your CivicLens account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:500; font-size:0.875rem;">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="you@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:500; font-size:0.875rem;">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.85rem;">Remember me</label>
                    </div>

                    <button type="submit" class="btn w-100" style="background:var(--cl-primary); color:#fff; font-weight:600; padding:0.6rem;">
                        Sign In
                    </button>
                </form>

                <p class="text-center mt-3" style="font-size:0.85rem; color:var(--cl-gray);">
                    Don't have an account? <a href="{{ route('register') }}" style="color:var(--cl-primary); font-weight:500;">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
