@extends('layouts.app')

@section('title', 'Monitor Payments')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md">
        <h2 class="fw-bold"><i class="bi bi-credit-card text-primary-color me-2"></i> Platform Ledger Transactions</h2>
        <p class="text-muted">Audit transaction logs, verify checkout responses, and track user billing histories.</p>
    </div>
</div>

<!-- Navigation tabs -->
<div class="card glass-card p-2 mb-4">
    <ul class="nav nav-pills d-flex flex-nowrap overflow-x-auto text-nowrap">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-graph-up me-1"></i> Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.students') }}"><i class="bi bi-mortarboard me-1"></i> Manage Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.tutors') }}"><i class="bi bi-briefcase me-1"></i> Verify Tutors</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.subjects') }}"><i class="bi bi-tags me-1"></i> Subject Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.bookings') }}"><i class="bi bi-calendar-event me-1"></i> Booking Logs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.payments') }}"><i class="bi bi-credit-card me-1"></i> Platform Ledgers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reviews') }}"><i class="bi bi-star me-1"></i> Moderation (Reviews)</a>
        </li>
    </ul>
</div>

<div class="card glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h5 class="fw-bold mb-0">Platform Ledgers</h5>
        
        <form action="{{ route('admin.payments') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search Transaction ID or user..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>

    @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted text-xs uppercase">
                        <th>Transaction ID</th>
                        <th>User Name</th>
                        <th>Email Address</th>
                        <th>Tutor / Instructor</th>
                        <th>Subject Category</th>
                        <th>Amount Paid</th>
                        <th>Processed Date</th>
                        <th>Gateway Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $rp)
                        <tr>
                            <td><strong class="font-monospace text-xs text-primary-color">{{ $rp->transaction_id }}</strong></td>
                            <td><strong>{{ $rp->user->name }}</strong></td>
                            <td>{{ $rp->user->email }}</td>
                            <td>
                                @if($rp->booking)
                                    <small>{{ $rp->booking->tutor->user->name }}</small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td>
                                @if($rp->booking)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary text-xxs">{{ $rp->booking->subject->name }}</span>
                                @else
                                    <span class="text-muted text-xxs">N/A</span>
                                @endif
                            </td>
                            <td><strong class="text-sm">${{ number_format($rp->amount, 2) }}</strong></td>
                            <td><small class="text-muted">{{ $rp->created_at->format('Y-m-d H:i') }}</small></td>
                            <td>
                                @if($rp->status === 'success')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs">Success</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1 text-xs">Failed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-credit-card-2-front fs-1 d-block mb-2"></i>
            <small>No ledger payments found.</small>
        </div>
    @endif
</div>
@endsection
