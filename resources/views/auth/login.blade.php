@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card glass-card p-4">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock-fill text-primary-color display-4"></i>
                <h3 class="fw-bold mt-2">Welcome Back</h3>
                <p class="text-muted">Sign in to manage your learning schedule</p>
            </div>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="yourname@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <label for="password" class="form-label fw-medium mb-0">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-primary-color text-decoration-none">Forgot password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label text-muted" for="remember">Keep me signed in</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                </button>
                
                <div class="text-center text-muted text-sm">
                    Don't have an account? <a href="{{ route('register') }}" class="text-primary-color text-decoration-none fw-semibold">Register here</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
