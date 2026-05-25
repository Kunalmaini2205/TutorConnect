@extends('layouts.app')

@section('title', 'Monitor Bookings')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md">
        <h2 class="fw-bold"><i class="bi bi-calendar-event text-primary-color me-2"></i> Bookings Registry</h2>
        <p class="text-muted">Monitor scheduling contracts, classroom statuses, and pricing details.</p>
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
            <a class="nav-link active" href="{{ route('admin.bookings') }}"><i class="bi bi-calendar-event me-1"></i> Booking Logs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.payments') }}"><i class="bi bi-credit-card me-1"></i> Platform Ledgers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reviews') }}"><i class="bi bi-star me-1"></i> Moderation (Reviews)</a>
        </li>
    </ul>
</div>

<div class="card glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h5 class="fw-bold mb-0">Booking Log Monitor</h5>
        
        <form action="{{ route('admin.bookings') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search student or tutor..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>

    @if($bookings->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted text-xs uppercase">
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Tutor Name</th>
                        <th>Subject Category</th>
                        <th>Session Date & Time</th>
                        <th>Rate Charge</th>
                        <th>State</th>
                        <th>Billing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                        <tr>
                            <td><small class="text-muted font-monospace">#TC-{{ $b->id }}</small></td>
                            <td><strong>{{ $b->student->user->name }}</strong></td>
                            <td><strong>{{ $b->tutor->user->name }}</strong></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $b->subject->name }}</span></td>
                            <td>
                                <div class="text-xs fw-semibold">{{ $b->date->format('Y-m-d') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                            </td>
                            <td><strong class="text-sm text-primary-color">${{ number_format($b->total_price, 2) }}</strong></td>
                            <td>
                                @if($b->status === 'completed')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs">Completed</span>
                                @elseif($b->status === 'accepted')
                                    <span class="badge bg-primary bg-opacity-10 text-primary text-xs rounded-pill px-2.5 py-1">Accepted</span>
                                @elseif($b->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning text-xs rounded-pill px-2.5 py-1">Pending</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary text-xs rounded-pill px-2.5 py-1">{{ $b->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status === 'paid')
                                    <span class="badge bg-success bg-opacity-10 text-success text-xxs border border-success border-opacity-15">Paid</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary text-xxs border border-secondary border-opacity-15">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
            <small>No booking records match your query.</small>
        </div>
    @endif
</div>
@endsection
