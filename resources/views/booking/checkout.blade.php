@extends('layouts.app')

@section('title', 'Booking Checkout')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-lg-8">
        <div class="row g-4">
            <!-- Left Side: Invoice Summary -->
            <div class="col-md-5 order-md-2">
                <div class="card glass-card p-4">
                    <h5 class="fw-bold mb-4">Lesson Summary</h5>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if($tutor->user->profile_picture)
                            <img src="{{ asset('storage/' . $tutor->user->profile_picture) }}" class="avatar-circle avatar-lg" alt="{{ $tutor->user->name }}">
                        @else
                            <div class="avatar-circle avatar-lg">
                                {{ substr($tutor->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h6 class="fw-bold mb-0">{{ $tutor->user->name }}</h6>
                            <small class="text-muted d-block">{{ $tutor->qualification }}</small>
                            <span class="text-warning text-xs"><i class="bi bi-star-fill me-0.5"></i> {{ number_format($tutor->rating, 1) }} Rating</span>
                        </div>
                    </div>
                    
                    <hr class="my-3" style="border-color: rgba(var(--primary-rgb), 0.1);">
                    
                    <div class="d-flex justify-content-between text-sm mb-2">
                        <span class="text-muted">Session Date</span>
                        <span class="fw-semibold">{{ $slot->date->format('l, M d, Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between text-sm mb-2">
                        <span class="text-muted">Session Time</span>
                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</span>
                    </div>

                    <div class="d-flex justify-content-between text-sm mb-4">
                        <span class="text-muted">Hourly Cost</span>
                        <span class="fw-semibold">${{ number_format($tutor->hourly_rate, 2) }}/hr</span>
                    </div>

                    <hr class="my-3" style="border-color: rgba(var(--primary-rgb), 0.1);">

                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <span class="fw-bold text-lg">Total Due</span>
                        <span class="fw-extrabold text-lg text-primary-color">${{ number_format($tutor->hourly_rate, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Billing Form -->
            <div class="col-md-7 order-md-1">
                <div class="card glass-card p-4">
                    <h4 class="fw-bold mb-1">Confirm Session</h4>
                    <p class="text-muted text-sm mb-4">Please select a subject and enter checkout credentials to secure your seat.</p>

                    <form action="{{ route('booking.process') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="slot_id" value="{{ $slot->id }}">
                        
                        <!-- Subject Selection -->
                        <div class="mb-4">
                            <label for="subject_id" class="form-label fw-semibold text-sm">Choose Lesson Subject</label>
                            <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Select lesson topic...</option>
                                @foreach($subjects as $subj)
                                    <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4" style="border-color: rgba(var(--primary-rgb), 0.1);">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Simulated Payment</h5>
                            <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2.5 text-xs fw-semibold" onclick="autoFillCard()">Autofill Test Card</button>
                        </div>
                        
                        <!-- Billing inputs -->
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="card_name" class="form-label text-sm fw-medium">Cardholder Name</label>
                                <input type="text" class="form-control @error('card_name') is-invalid @enderror" id="card_name" name="card_name" required placeholder="Charlie Brown">
                                @error('card_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="card_number" class="form-label text-sm fw-medium">Card Number</label>
                                <input type="text" class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" maxlength="16" required placeholder="4242424242424242">
                                @error('card_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-6">
                                <label for="card_expiry" class="form-label text-sm fw-medium">Expiration (MM/YY)</label>
                                <input type="text" class="form-control @error('card_expiry') is-invalid @enderror" id="card_expiry" name="card_expiry" maxlength="5" required placeholder="12/28">
                                @error('card_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-6">
                                <label for="card_cvc" class="form-label text-sm fw-medium">CVC</label>
                                <input type="text" class="form-control @error('card_cvc') is-invalid @enderror" id="card_cvc" name="card_cvc" maxlength="3" required placeholder="123">
                                @error('card_cvc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="alert alert-secondary border-0 mt-4 text-xs text-muted" role="alert">
                            <i class="bi bi-info-circle-fill me-1 text-primary-color"></i>
                            This is a secure checkout simulator. No real money or card billing will be processed. Card format must satisfy basic structure validation.
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mt-4" id="submit-btn">
                            <span id="btn-text"><i class="bi bi-wallet2 me-1"></i> Pay & Book Session</span>
                            <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function autoFillCard() {
        document.getElementById('card_name').value = 'Charlie Brown';
        document.getElementById('card_number').value = '4242424242424242';
        document.getElementById('card_expiry').value = '12/28';
        document.getElementById('card_cvc').value = '123';
    }

    document.getElementById('checkout-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        const text = document.getElementById('btn-text');
        const spinner = document.getElementById('btn-spinner');

        btn.disabled = true;
        text.classList.add('d-none');
        spinner.classList.remove('d-none');
    });
</script>
@endsection
