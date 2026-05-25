@extends('layouts.app')

@section('title', 'Moderate Reviews')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md">
        <h2 class="fw-bold"><i class="bi bi-star text-primary-color me-2"></i> Moderate Reviews</h2>
        <p class="text-muted">Monitor student feedback, hide inappropriate or fake reviews, and recalculate ratings.</p>
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
            <a class="nav-link" href="{{ route('admin.payments') }}"><i class="bi bi-credit-card me-1"></i> Platform Ledgers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.reviews') }}"><i class="bi bi-star me-1"></i> Moderation (Reviews)</a>
        </li>
    </ul>
</div>

<div class="card glass-card p-4">
    <h5 class="fw-bold mb-4">Feedback Vetting</h5>

    @if($reviews->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted text-xs uppercase">
                        <th>Student Name</th>
                        <th>Tutor Target</th>
                        <th>Rating Stars</th>
                        <th>Written Comment</th>
                        <th>Submitted Date</th>
                        <th>State</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $rev)
                        <tr>
                            <td><strong>{{ $rev->student->user->name }}</strong></td>
                            <td>
                                <strong>{{ $rev->tutor->user->name }}</strong><br>
                                <small class="text-muted text-xs">{{ $rev->tutor->title }}</small>
                            </td>
                            <td>
                                <div class="text-warning text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <p class="text-muted text-sm mb-0" style="max-width: 250px; white-space: normal;">"{{ $rev->comment }}"</p>
                            </td>
                            <td><small class="text-muted">{{ $rev->created_at->format('Y-m-d H:i') }}</small></td>
                            <td>
                                @if($rev->is_visible)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs">Visible</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1 text-xs">Hidden</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1.5 align-items-center">
                                    <!-- Toggle Visibility -->
                                    <form action="{{ route('admin.reviews.toggle-visibility', $rev->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($rev->is_visible)
                                            <button type="submit" class="btn btn-xs btn-outline-warning fw-semibold text-xxs">Hide</button>
                                        @else
                                            <button type="submit" class="btn btn-xs btn-outline-success fw-semibold text-xxs">Show</button>
                                        @endif
                                    </form>

                                    <!-- Delete Review -->
                                    <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger fw-semibold text-xxs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-chat-square-text fs-1 d-block mb-2"></i>
            <small>No reviews submitted in the database.</small>
        </div>
    @endif
</div>
@endsection
