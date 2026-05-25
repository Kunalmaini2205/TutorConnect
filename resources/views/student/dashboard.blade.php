@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="text-muted">Manage your tutoring appointments, learning progress, and favorites.</p>
    </div>
</div>

<!-- Quick Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card glass-card p-3 border-start border-primary border-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded p-3">
                    <i class="bi bi-calendar-check fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $upcomingBookings->count() }}</h5>
                    <small class="text-muted">Active Bookings Scheduled</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card glass-card p-3 border-start border-success border-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded p-3">
                    <i class="bi bi-graph-up-arrow fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ number_format($avgProgress, 0) }}%</h5>
                    <small class="text-muted">Average Learning Progress</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card glass-card p-3 border-start border-danger border-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger bg-opacity-10 text-danger rounded p-3">
                    <i class="bi bi-heart-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $favoriteTutors->count() }}</h5>
                    <small class="text-muted">Favorited Instructors</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Panel: Schedules -->
    <div class="col-lg-8">
        <!-- Upcoming Sessions -->
        <div class="card glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-event text-primary-color me-2"></i> Upcoming Sessions</h5>
            
            @if($upcomingBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted text-xs uppercase">
                                <th>Instructor</th>
                                <th>Subject</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingBookings as $b)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $b->tutor->user->name }}</div>
                                        <small class="text-muted">{{ $b->tutor->qualification }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $b->subject->name }}</span></td>
                                    <td>
                                        <div class="fw-semibold">{{ $b->date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($b->start_time)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($b->status === 'accepted')
                                            <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1">Accepted</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill px-2.5 py-1">Pending Approval</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1.5">
                                            @if($b->status === 'accepted' && $b->meet_link)
                                                <a href="{{ $b->meet_link }}" target="_blank" class="btn btn-sm btn-success fw-semibold text-xs" title="Join Classroom">
                                                    <i class="bi bi-camera-video-fill me-1"></i> Join
                                                </a>
                                            @endif
                                            
                                            <!-- Reschedule button triggers modal -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary fw-semibold text-xs" data-bs-toggle="modal" data-bs-target="#rescheduleModal_{{ $b->id }}">
                                                Reschedule
                                            </button>

                                            <!-- Cancel button -->
                                            <form action="{{ route('booking.cancel', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking? This will trigger a simulated refund.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold text-xs">Cancel</button>
                                            </form>
                                        </div>

                                        <!-- Reschedule Modal -->
                                        <div class="modal fade" id="rescheduleModal_{{ $b->id }}" tabindex="-1" aria-labelledby="rescheduleModalLabel_{{ $b->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content glass-card p-2 border">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold" id="rescheduleModalLabel_{{ $b->id }}">Reschedule Session</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('booking.reschedule', $b->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body text-start">
                                                            <p class="text-muted text-sm">Select an alternative slot from the list of the tutor's open schedule:</p>
                                                            
                                                            @php
                                                                $slots = \App\Models\AvailabilitySlot::where('tutor_id', $b->tutor_id)
                                                                    ->where('is_booked', false)
                                                                    ->where('date', '>=', now()->toDateString())
                                                                    ->orderBy('date')->orderBy('start_time')->get();
                                                            @endphp

                                                            @if($slots->count() > 0)
                                                                <div class="mb-3">
                                                                    <label for="new_slot_id" class="form-label text-sm fw-medium">Available Open Slots</label>
                                                                    <select name="new_slot_id" class="form-select" required>
                                                                        <option value="" selected disabled>Select new date/time...</option>
                                                                        @foreach($slots as $slot)
                                                                            <option value="{{ $slot->id }}">
                                                                                {{ $slot->date->format('Y-m-d') }} at {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-warning text-xs mb-0">
                                                                    The tutor has no other availability slots listed. Please contact them via Messenger to request new slots.
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                            @if($slots->count() > 0)
                                                                <button type="submit" class="btn btn-sm btn-primary">Confirm Reschedule</button>
                                                            @endif
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-minus fs-2 mb-2 d-block"></i>
                    <small>No upcoming lessons scheduled. Find tutors to book a slot.</small>
                </div>
            @endif
        </div>

        <!-- Past Sessions & Certificates -->
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary-color me-2"></i> Past Session Logs</h5>
            
            @if($pastBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted text-xs uppercase">
                                <th>Instructor</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Certificate & Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastBookings as $b)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $b->tutor->user->name }}</div>
                                    </td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $b->subject->name }}</span></td>
                                    <td><small>{{ $b->date->format('M d, Y') }}</small></td>
                                    <td>
                                        @if($b->status === 'completed')
                                            <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1">Completed</span>
                                        @elseif($b->status === 'rejected')
                                            <span class="badge bg-danger bg-opacity-15 text-danger rounded-pill px-2.5 py-1" title="{{ $b->status_notes }}">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-15 text-secondary rounded-pill px-2.5 py-1" title="{{ $b->status_notes }}">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1.5 align-items-center">
                                            @if($b->status === 'completed')
                                                <!-- Download Certificate -->
                                                <a href="{{ route('learning.certificate', $b->id) }}" class="btn btn-sm btn-outline-success fw-semibold text-xs" title="Download Completion Certificate">
                                                    <i class="bi bi-patch-check-fill me-1"></i> Certificate
                                                </a>
                                                
                                                <!-- Download Invoice -->
                                                @if($b->payment)
                                                    <a href="{{ route('payments.invoice', $b->id) }}" class="btn btn-sm btn-outline-primary fw-semibold text-xs" title="Download Invoice Statement">
                                                        <i class="bi bi-file-earmark-pdf me-1"></i> Invoice
                                                    </a>
                                                @endif

                                                <!-- Review Form triggers Modal -->
                                                @if(!$b->review)
                                                    <button type="button" class="btn btn-sm btn-primary fw-semibold text-xs" data-bs-toggle="modal" data-bs-target="#reviewModal_{{ $b->id }}">
                                                        Review
                                                    </button>
                                                @else
                                                    <span class="text-xs text-muted fw-semibold py-1 px-2 border rounded bg-body-tertiary"><i class="bi bi-star-fill text-warning"></i> Reviewed</span>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Review Submission Modal -->
                                        @if($b->status === 'completed' && !$b->review)
                                            <div class="modal fade" id="reviewModal_{{ $b->id }}" tabindex="-1" aria-labelledby="reviewModalLabel_{{ $b->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content glass-card p-2 border">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold" id="reviewModalLabel_{{ $b->id }}">Submit Session Review</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('reviews.store') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="booking_id" value="{{ $b->id }}">
                                                            <div class="modal-body text-start">
                                                                <p class="text-muted text-sm">Rate your session experience with {{ $b->tutor->user->name }}:</p>
                                                                
                                                                <!-- Star selection -->
                                                                <div class="mb-3">
                                                                    <label for="rating" class="form-label text-sm fw-medium">Star Rating (1 - 5)</label>
                                                                    <select name="rating" class="form-select" required>
                                                                        <option value="5">5 Stars - Outstanding</option>
                                                                        <option value="4">4 Stars - Very Good</option>
                                                                        <option value="3">3 Stars - Satisfactory</option>
                                                                        <option value="2">2 Stars - Below Average</option>
                                                                        <option value="1">1 Star - Unsatisfactory</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="comment" class="form-label text-sm fw-medium">Written Feedback</label>
                                                                    <textarea name="comment" class="form-control" rows="3" placeholder="Describe the lesson quality, communication, and learning outcomes..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 pt-0">
                                                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-sm btn-primary">Publish Review</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-clock fs-2 mb-2 d-block"></i>
                    <small>No past booking logs recorded.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Panel: Wishlist & Progress Logs -->
    <div class="col-lg-4">
        <!-- Favorites Wishlist -->
        <div class="card glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-heart-fill text-danger me-2"></i> Favorited Tutors</h5>
            
            @if($favoriteTutors->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($favoriteTutors as $fav)
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2.5">
                                @if($fav->user->profile_picture)
                                    <img src="{{ asset('storage/' . $fav->user->profile_picture) }}" class="avatar-circle" alt="{{ $fav->user->name }}" style="width: 36px; height: 36px;">
                                @else
                                    <div class="avatar-circle" style="width: 36px; height: 36px; font-size: 0.95rem;">
                                        {{ substr($fav->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-0 text-sm">{{ $fav->user->name }}</h6>
                                    <small class="text-muted text-xs">${{ $fav->hourly_rate }}/hr &bull; {{ $fav->qualification }}</small>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-1">
                                <a href="{{ route('tutors.show', $fav->id) }}" class="btn btn-xs btn-outline-primary py-1 px-2.5 text-xs fw-semibold" title="View Profile">Profile</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-emoji-neutral fs-2 d-block mb-1"></i>
                    <small>Favorites list is currently empty.</small>
                </div>
            @endif
        </div>

        <!-- Latest Progress Logs -->
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow text-success me-2"></i> Recent Milestones</h5>
            
            @if($progressLogs->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($progressLogs as $log)
                        <div class="border-bottom pb-2 mb-2 last-border-none">
                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary text-xs">{{ $log->subject->name }}</span>
                                <span class="fw-bold text-success text-sm">{{ $log->progress_percentage }}% Score</span>
                            </div>
                            <p class="text-muted text-xs mb-1">"{{ Str::limit($log->notes, 75, '...') }}"</p>
                            <small class="text-muted text-xxs d-block"><i class="bi bi-calendar-event"></i> {{ $log->created_at->format('M d, Y') }} &bull; By {{ $log->tutor->user->name }}</small>
                        </div>
                    @endforeach
                    <a href="{{ route('learning.tracker') }}" class="btn btn-sm btn-outline-primary fw-semibold mt-1">View Learning Tracker</a>
                </div>
            @else
                <div class="text-center py-3 text-muted">
                    <i class="bi bi-graph-down fs-2 d-block mb-1"></i>
                    <small>No progress marks recorded yet.</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
