@extends('layouts.app')

@section('title', 'Admin Command Center')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md">
        <h2 class="fw-bold"><i class="bi bi-speedometer2 text-primary-color me-2"></i> Admin Command Center</h2>
        <p class="text-muted">Manage tutor bookings, verify instructors, moderate user feedback, and compile financial reports.</p>
    </div>
    
    <div class="col-md-auto">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.export.users') }}" class="btn btn-outline-primary fw-semibold text-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Users Report CSV
            </a>
            <a href="{{ route('admin.export.bookings') }}" class="btn btn-primary fw-semibold text-sm">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Bookings Report CSV
            </a>
        </div>
    </div>
</div>

<!-- Admin Management Nav Sub-menu -->
<div class="card glass-card p-2 mb-4 border border-opacity-5">
    <ul class="nav nav-pills d-flex flex-nowrap overflow-x-auto text-nowrap">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.dashboard') }}"><i class="bi bi-graph-up me-1"></i> Overview</a>
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
            <a class="nav-link" href="{{ route('admin.payments') }}"><i class="bi bi-credit-card me-1"></i> Platform Ledgers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reviews') }}"><i class="bi bi-star me-1"></i> Moderation (Reviews)</a>
        </li>
    </ul>
</div>

<!-- KPIs -->
<div class="row g-4 mb-4 text-center">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card glass-card p-3 h-100">
            <h3 class="fw-extrabold text-primary-color mb-1">{{ $totalStudents }}</h3>
            <small class="text-muted text-xs font-semibold uppercase">Students</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card glass-card p-3 h-100">
            <h3 class="fw-extrabold text-primary-color mb-1">{{ $totalTutors }}</h3>
            <small class="text-muted text-xs font-semibold uppercase">Total Tutors</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card glass-card p-3 h-100 border-warning border-opacity-30">
            <h3 class="fw-extrabold text-warning mb-1">{{ $pendingTutors }}</h3>
            <small class="text-muted text-xs font-semibold uppercase">Pending Vetting</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card glass-card p-3 h-100">
            <h3 class="fw-extrabold text-primary-color mb-1">{{ $totalBookings }}</h3>
            <small class="text-muted text-xs font-semibold uppercase">Total Bookings</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card glass-card p-3 h-100 border-success border-opacity-30">
            <h3 class="fw-extrabold text-success mb-1">{{ $completedBookings }}</h3>
            <small class="text-muted text-xs font-semibold uppercase">Completed</small>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card glass-card p-3 h-100 border-primary border-opacity-30">
            <h3 class="fw-extrabold text-primary-color mb-1">${{ number_format($totalRevenue, 2) }}</h3>
            <small class="text-muted text-xs font-semibold uppercase">Platform Revenue</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Bookings Logs -->
    <div class="col-lg-6">
        <div class="card glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent Bookings</h5>
                <a href="{{ route('admin.bookings') }}" class="text-xs text-primary-color text-decoration-none">View All</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sm">
                    <thead>
                        <tr class="text-muted text-xxs uppercase">
                            <th>Booking Info</th>
                            <th>Subject</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $rb)
                            <tr>
                                <td>
                                    <div class="fw-bold text-xs">S: {{ $rb->student->user->name }}</div>
                                    <small class="text-xxs text-muted">T: {{ $rb->tutor->user->name }}</small>
                                </td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary text-xxs">{{ $rb->subject->name }}</span></td>
                                <td><span class="fw-semibold text-xs">${{ $rb->total_price }}</span></td>
                                <td>
                                    @if($rb->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success text-xxs rounded-pill">Completed</span>
                                    @elseif($rb->status === 'accepted')
                                        <span class="badge bg-primary bg-opacity-10 text-primary text-xxs rounded-pill">Accepted</span>
                                    @elseif($rb->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning text-xxs rounded-pill">Pending</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary text-xxs rounded-pill">{{ $rb->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted text-xs">No recent bookings recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Payments Logs -->
    <div class="col-lg-6">
        <div class="card glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent Transactions</h5>
                <a href="{{ route('admin.payments') }}" class="text-xs text-primary-color text-decoration-none">View All</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle table-sm">
                    <thead>
                        <tr class="text-muted text-xxs uppercase">
                            <th>Transaction ID</th>
                            <th>Client</th>
                            <th>Total Price</th>
                            <th>Gateway Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $rp)
                            <tr>
                                <td><strong class="text-xs font-monospace">{{ $rp->transaction_id }}</strong></td>
                                <td>
                                    <div class="fw-bold text-xs">{{ $rp->user->name }}</div>
                                    <small class="text-xxs text-muted">{{ $rp->user->email }}</small>
                                </td>
                                <td><span class="fw-bold text-xs">${{ $rp->amount }}</span></td>
                                <td>
                                    @if($rp->status === 'success')
                                        <span class="badge bg-success bg-opacity-10 text-success text-xxs rounded-pill">Success</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger text-xxs rounded-pill">Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted text-xs">No transaction records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
