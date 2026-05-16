@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="cl-card p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-check" style="font-size:2rem; color:var(--cl-primary);"></i>
                    <h4 style="font-weight:700; margin-top:0.5rem;">Create Account</h4>
                    <p style="color:var(--cl-gray); font-size:0.9rem;">Join CivicLens and share your feedback</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:500; font-size:0.875rem;">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Your name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                               placeholder="Minimum 6 characters" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:500; font-size:0.875rem;">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Re-enter password" required>
                    </div>

                    <button type="submit" class="btn w-100" style="background:var(--cl-primary); color:#fff; font-weight:600; padding:0.6rem;">
                        Create Account
                    </button>
                </form>

                <p class="text-center mt-3" style="font-size:0.85rem; color:var(--cl-gray);">
                    Already have an account? <a href="{{ route('login') }}" style="color:var(--cl-primary); font-weight:500;">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
