@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md">
        <h2 class="fw-bold"><i class="bi bi-mortarboard text-primary-color me-2"></i> Manage Students</h2>
        <p class="text-muted">Search, monitor, and configure student accounts.</p>
    </div>
</div>

<!-- Navigation tabs -->
<div class="card glass-card p-2 mb-4">
    <ul class="nav nav-pills d-flex flex-nowrap overflow-x-auto text-nowrap">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-graph-up me-1"></i> Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.students') }}"><i class="bi bi-mortarboard me-1"></i> Manage Students</a>
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

<div class="card glass-card p-4">
    <!-- Search Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h5 class="fw-bold mb-0">Student Registry</h5>
        
        <form action="{{ route('admin.students') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>

    @if($students->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted text-xs uppercase">
                        <th>Student Name</th>
                        <th>Email Address</th>
                        <th>Phone</th>
                        <th>Grade Level</th>
                        <th>Registered Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td><strong>{{ $student->user->name }}</strong></td>
                            <td>{{ $student->user->email }}</td>
                            <td>{{ $student->user->phone ?? 'N/A' }}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $student->grade_level }}</span></td>
                            <td><small class="text-muted">{{ $student->user->created_at->format('Y-m-d') }}</small></td>
                            <td>
                                @if($student->user->status === 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1">Active</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1">Suspended</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.users.toggle-status', $student->user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($student->user->status === 'active')
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold text-xs" onclick="return confirm('Suspend this student account?')">Suspend</button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-outline-success fw-semibold text-xs">Unsuspend</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $students->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-mortarboard fs-1 d-block mb-2"></i>
            <small>No student profiles match your search criteria.</small>
        </div>
    @endif
</div>
@endsection
