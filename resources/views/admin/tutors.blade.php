@extends('layouts.app')

@section('title', 'Manage & Verify Tutors')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md">
        <h2 class="fw-bold"><i class="bi bi-briefcase text-primary-color me-2"></i> Manage & Verify Tutors</h2>
        <p class="text-muted">Perform tutor credential vetting, manage accounts, and assign digital classroom keys.</p>
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
            <a class="nav-link active" href="{{ route('admin.tutors') }}"><i class="bi bi-briefcase me-1"></i> Verify Tutors</a>
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
    <!-- Filter Options -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2">
            <h5 class="fw-bold mb-0">Tutor Directory</h5>
            <div class="d-flex gap-1 btn-group border rounded-pill p-0.5 bg-body-tertiary">
                <a href="{{ route('admin.tutors') }}" class="btn btn-xs rounded-pill {{ request('status') === null ? 'btn-primary' : 'btn-link text-reset text-decoration-none' }}">All</a>
                <a href="{{ route('admin.tutors', ['status' => 'pending']) }}" class="btn btn-xs rounded-pill {{ request('status') === 'pending' ? 'btn-primary' : 'btn-link text-reset text-decoration-none' }}">Pending Review</a>
                <a href="{{ route('admin.tutors', ['status' => 'verified']) }}" class="btn btn-xs rounded-pill {{ request('status') === 'verified' ? 'btn-primary' : 'btn-link text-reset text-decoration-none' }}">Vetted</a>
            </div>
        </div>
        
        <form action="{{ route('admin.tutors') }}" method="GET" class="d-flex gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>

    @if($tutors->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted text-xs uppercase">
                        <th>Tutor Info</th>
                        <th>Credentials</th>
                        <th>Subjects</th>
                        <th>Rate & Experience</th>
                        <th>Vetted Status</th>
                        <th>Account Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tutors as $tutor)
                        <tr>
                            <td>
                                <strong>{{ $tutor->user->name }}</strong><br>
                                <small class="text-muted text-xs">{{ $tutor->user->email }}</small>
                            </td>
                            <td>
                                <div class="text-sm fw-medium">{{ $tutor->title }}</div>
                                <small class="text-muted text-xs">{{ $tutor->qualification }}</small>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1" style="max-width: 200px;">
                                    @foreach($tutor->subjects as $subj)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary text-xxs">{{ $subj->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-xs">${{ number_format($tutor->hourly_rate, 2) }}/hr</div>
                                <small class="text-muted text-xxs">{{ $tutor->experience }} yrs exp</small>
                            </td>
                            <td>
                                @if($tutor->is_verified)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 text-xs">Vetted</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1 text-xs">Awaiting audit</span>
                                @endif
                            </td>
                            <td>
                                @if($tutor->user->status === 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs">Active</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1 text-xs">Suspended</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1.5 align-items-center">
                                    <!-- Toggle Verification -->
                                    <form action="{{ route('admin.tutors.toggle-verify', $tutor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($tutor->is_verified)
                                            <button type="submit" class="btn btn-xs btn-outline-warning fw-semibold text-xxs">Revoke Vetting</button>
                                        @else
                                            <button type="submit" class="btn btn-xs btn-success fw-semibold text-xxs"><i class="bi bi-check-circle"></i> Vouch & Verify</button>
                                        @endif
                                    </form>

                                    <!-- Toggle Suspension -->
                                    <form action="{{ route('admin.users.toggle-status', $tutor->user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($tutor->user->status === 'active')
                                            <button type="submit" class="btn btn-xs btn-outline-danger fw-semibold text-xxs" onclick="return confirm('Suspend this tutor account?')">Suspend</button>
                                        @else
                                            <button type="submit" class="btn btn-xs btn-outline-success fw-semibold text-xxs">Unsuspend</button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $tutors->appends(request()->input())->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
            <small>No tutor accounts match your filter criteria.</small>
        </div>
    @endif
</div>
@endsection
