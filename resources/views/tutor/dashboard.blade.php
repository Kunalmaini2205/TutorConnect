@extends('layouts.app')

@section('title', 'Tutor Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold">Instructor Room: {{ Auth::user()->name }}</h2>
                <p class="text-muted">Manage your teaching schedule, accept booking requests, and upload learning resources.</p>
            </div>
            
            @if(!Auth::user()->tutor->is_verified)
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-3 py-2 fs-6 rounded-pill fw-semibold animate-pulse">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Under Verification Review
                </span>
            @else
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-2 fs-6 rounded-pill fw-semibold">
                    <i class="bi bi-patch-check-fill me-1"></i> Verified Account
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Tutor metrics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card glass-card p-3 border-start border-primary border-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded p-3">
                    <i class="bi bi-wallet2 fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">${{ number_format($earnings, 2) }}</h5>
                    <small class="text-muted">Total Ledger Earnings</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card glass-card p-3 border-start border-warning border-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                    <i class="bi bi-calendar3 fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $slots->count() }}</h5>
                    <small class="text-muted">Total Slots Listed</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card glass-card p-3 border-start border-info border-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info bg-opacity-10 text-info rounded p-3">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $pendingBookings->count() }}</h5>
                    <small class="text-muted">Pending Bookings</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card glass-card p-3 border-start border-success border-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded p-3">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $reviews->count() }}</h5>
                    <small class="text-muted">Student Reviews</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Bookings & Active slots -->
    <div class="col-lg-8">
        <!-- Booking Requests (Pending) -->
        <div class="card glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-bell-fill text-warning me-2 animate-pulse"></i> Booking Requests (Pending)</h5>
            
            @if($pendingBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted text-xs uppercase">
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Requested Schedule</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingBookings as $b)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $b->student->user->name }}</div>
                                        <small class="text-muted">{{ $b->student->grade_level }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $b->subject->name }}</span></td>
                                    <td>
                                        <div class="fw-semibold text-sm">{{ $b->date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($b->start_time)->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1.5">
                                            <form action="{{ route('booking.accept', $b->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success fw-semibold text-xs"><i class="bi bi-check-circle-fill"></i> Accept</button>
                                            </form>
                                            
                                            <!-- Reject Trigger -->
                                            <button type="button" class="btn btn-sm btn-outline-danger fw-semibold text-xs" data-bs-toggle="modal" data-bs-target="#rejectModal_{{ $b->id }}">
                                                Reject
                                            </button>
                                        </div>

                                        <!-- Reject Modal with notes -->
                                        <div class="modal fade" id="rejectModal_{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content glass-card border p-2">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Reject Booking Request</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('booking.reject', $b->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body text-start">
                                                            <div class="mb-3">
                                                                <label for="notes" class="form-label text-sm fw-medium">Reason for rejection (Optional)</label>
                                                                <textarea name="notes" class="form-control" rows="3" placeholder="e.g. I have a schedule conflict, please choose another slot..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-danger">Confirm Reject & Refund</button>
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
                <div class="text-center py-4 text-muted border border-dashed rounded-3">
                    <small>No pending booking requests at this time.</small>
                </div>
            @endif
        </div>

        <!-- Approved Upcoming sessions -->
        <div class="card glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check-fill text-primary-color me-2"></i> Upcoming Active Lessons</h5>
            
            @if($upcomingSessions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted text-xs uppercase">
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Schedule</th>
                                <th>Classroom</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingSessions as $s)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $s->student->user->name }}</div>
                                    </td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $s->subject->name }}</span></td>
                                    <td>
                                        <div class="fw-semibold text-xs">{{ $s->date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($s->start_time)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($s->meet_link)
                                            <a href="{{ $s->meet_link }}" target="_blank" class="btn btn-xs btn-success fw-semibold text-xs px-2.5 py-1.5"><i class="bi bi-camera-video-fill me-1"></i> Join Class</a>
                                        @else
                                            <span class="text-muted text-xs">Awaiting Link</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1.5">
                                            <form action="{{ route('booking.complete', $s->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary fw-semibold text-xs"><i class="bi bi-check-lg"></i> Complete</button>
                                            </form>
                                            <a href="{{ route('chat.index', ['chat_id' => \App\Models\Chat::where('student_id', $s->student_id)->where('tutor_id', $s->tutor_id)->first()?->id]) }}" class="btn btn-sm btn-outline-secondary fw-semibold text-xs">Chat</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <small>No upcoming scheduled sessions.</small>
                </div>
            @endif
        </div>

        <!-- Availability Slots Management -->
        <div class="card glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-fill text-primary-color me-2"></i> Manage Schedule Slots</h5>
                <button type="button" class="btn btn-sm btn-primary fw-semibold" data-bs-toggle="collapse" data-bs-target="#addSlotFormCollapse">
                    <i class="bi bi-plus-lg me-1"></i> Add Slot
                </button>
            </div>
            
            <!-- Collapse Form to Add Slot -->
            <div class="collapse mb-4" id="addSlotFormCollapse">
                <div class="card bg-body-tertiary p-3 border-0 rounded-3">
                    <form action="{{ route('slots.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label text-sm fw-medium">Lesson Date</label>
                                <input type="date" class="form-control form-control-sm" name="date" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="start_time" class="form-label text-sm fw-medium">Start Time</label>
                                <input type="time" class="form-control form-control-sm" name="start_time" required>
                            </div>
                            <div class="col-md-4">
                                <label for="end_time" class="form-label text-sm fw-medium">End Time</label>
                                <input type="time" class="form-control form-control-sm" name="end_time" required>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3">Publish Slot</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List of existing slots -->
            @if($slots->count() > 0)
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover align-middle table-sm">
                        <thead>
                            <tr class="text-muted text-xxs uppercase">
                                <th>Date</th>
                                <th>Time Interval</th>
                                <th>Booking Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slots as $slot)
                                <tr>
                                    <td><strong>{{ $slot->date->format('Y-m-d') }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</td>
                                    <td>
                                        @if($slot->is_booked)
                                            <span class="badge bg-success bg-opacity-10 text-success text-xxs border border-success border-opacity-15">Booked</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary text-xxs border border-secondary border-opacity-15">Available</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(!$slot->is_booked)
                                            <form action="{{ route('slots.destroy', $slot->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-link text-danger p-0 border-0 text-decoration-none" title="Delete Slot"><i class="bi bi-trash3-fill"></i></button>
                                            </form>
                                        @else
                                            <span class="text-muted text-xxs font-normal">Active Contract</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <small>No availability slots published. Click "Add Slot" above to define your calendar.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: logs and files uploader -->
    <div class="col-lg-4">
        <!-- Log Progress Note -->
        <div class="card glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-journal-check text-success me-2"></i> Log Student Progress</h5>
            
            @php
                // Fetch completed bookings for this tutor that can have progress logs
                $completedSessions = \App\Models\Booking::where('tutor_id', Auth::user()->tutor->id)
                    ->where('status', 'completed')
                    ->with('student.user')
                    ->orderBy('date', 'desc')
                    ->get();
            @endphp
            
            @if($completedSessions->count() > 0)
                <form action="{{ route('progress.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="booking_id" class="form-label text-xs fw-semibold">Select Session Lesson</label>
                        <select name="booking_id" class="form-select form-select-sm" required>
                            <option value="" selected disabled>Select student...</option>
                            @foreach($completedSessions as $cs)
                                <option value="{{ $cs->id }}">
                                    {{ $cs->student->user->name }} - {{ $cs->subject->name }} ({{ $cs->date->format('m/d') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="progress_percentage" class="form-label text-xs fw-semibold">Performance Score (%)</label>
                        <input type="number" name="progress_percentage" class="form-control form-control-sm" required min="0" max="100" placeholder="85">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label text-xs fw-semibold">Summary Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="3" required placeholder="Write feedback summary, topics learned, homework assigned..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">Record Log Entry</button>
                </form>
            @else
                <div class="text-center py-4 text-muted border border-dashed rounded-3">
                    <small class="d-block mb-1">No completed sessions recorded.</small>
                    <small class="text-xxs">Progress logs require completing booked sessions first.</small>
                </div>
            @endif
        </div>

        <!-- Upload study resources -->
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-arrow-up text-primary-color me-2"></i> Study Resources</h5>
            
            <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="mb-2">
                    <label for="title" class="form-label text-xs fw-semibold">Document Title</label>
                    <input type="text" class="form-control form-control-sm" name="title" required placeholder="Calculus Formula Sheet">
                </div>
                <div class="mb-2">
                    <label for="description" class="form-label text-xs fw-semibold">Short Note</label>
                    <input type="text" class="form-control form-control-sm" name="description" placeholder="Summary or instructions...">
                </div>
                <div class="mb-3">
                    <label for="material_file" class="form-label text-xs fw-semibold">Upload File</label>
                    <input type="file" class="form-control form-control-sm" name="material_file" required>
                </div>
                <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">Upload Resource</button>
            </form>

            <h6 class="fw-bold text-xs text-muted mb-2 uppercase">Uploaded Materials ({{ $materials->count() }})</h6>
            @if($materials->count() > 0)
                <div class="d-flex flex-column gap-2" style="max-height: 180px; overflow-y: auto;">
                    @foreach($materials as $mat)
                        <div class="p-2 border rounded bg-body-tertiary bg-opacity-40 d-flex justify-content-between align-items-center">
                            <div class="overflow-hidden me-2">
                                <strong class="text-xs text-truncate d-block">{{ $mat->title }}</strong>
                                <small class="text-muted text-xxs">{{ strtoupper($mat->file_type) }} &bull; {{ $mat->downloads }} dl</small>
                            </div>
                            
                            <a href="{{ route('materials.download', $mat->id) }}" class="btn btn-xs btn-outline-secondary p-1 border-0" title="Download"><i class="bi bi-download"></i></a>
                        </div>
                    @endforeach
                </div>
            @else
                <small class="text-muted d-block text-center py-2">No resource files published yet.</small>
            @endif
        </div>
    </div>
</div>
@endsection
