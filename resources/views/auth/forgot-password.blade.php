@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card glass-card p-4">
            <div class="text-center mb-4">
                <i class="bi bi-key-fill text-primary-color display-4"></i>
                <h3 class="fw-bold mt-2">Reset Password</h3>
                <p class="text-muted">Enter your email and we'll send you instructions to reset your password</p>
            </div>
            
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label fw-medium">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="yourname@example.com">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                    Send Reset Link
                </button>
                
                <div class="text-center text-muted text-sm">
                    Remembered password? <a href="{{ route('login') }}" class="text-primary-color text-decoration-none fw-semibold">Sign In here</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
