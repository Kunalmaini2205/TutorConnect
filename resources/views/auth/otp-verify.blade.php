@extends('layouts.app')

@section('title', 'OTP Verification')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card glass-card p-4">
            <div class="text-center mb-4">
                <i class="bi bi-shield-check text-primary-color display-4"></i>
                <h3 class="fw-bold mt-2">Verify Your Account</h3>
                <p class="text-muted">An OTP (One-Time Password) verification has been initiated for <strong>{{ $user->email }}</strong>.</p>
            </div>

            <!-- MOCK EMAIL/SMS SYSTEM FOR SANDBOX TESTING -->
            @if(session('otp_debug'))
                <div class="alert alert-info border-info border-opacity-20 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope-open-fill me-2 fs-5 text-info animate-pulse"></i>
                        <div>
                            <span class="fw-bold">Mock Notification System:</span><br>
                            Your 6-digit OTP code is: <strong class="fs-5 text-primary-color">{{ session('otp_debug') }}</strong> (Expires in 10 mins).
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info border-info border-opacity-20 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5 text-info"></i>
                        <div>
                            For sandbox testing, the code generated is: <strong class="fs-5 text-primary-color">{{ $user->otp_code ?? '123456' }}</strong>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('otp.verify', ['userId' => $user->id]) }}" method="POST">
                @csrf
                <div class="mb-4 text-center">
                    <label for="otp_code" class="form-label fw-bold mb-2">Enter 6-Digit Passcode</label>
                    <input type="text" class="form-control text-center fs-3 fw-bold @error('otp_code') is-invalid @enderror" id="otp_code" name="otp_code" maxlength="6" autofocus required placeholder="••••••" style="letter-spacing: 12px; height: 60px;">
                    @error('otp_code')
                        <div class="invalid-feedback text-center mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                    Verify & Continue <i class="bi bi-arrow-right ms-1"></i>
                </button>
                
                <div class="text-center text-muted text-sm">
                    Didn't receive the code? <a href="#" onclick="alert('A new OTP has been logged in storage.'); return false;" class="text-primary-color text-decoration-none fw-semibold">Resend OTP</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
