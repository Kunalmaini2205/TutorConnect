@extends('layouts.app')

@section('title', 'Tutor Learning Logs')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold"><i class="bi bi-graph-up-arrow text-success me-2"></i> Logged Milestones Registry</h2>
        <p class="text-muted">Review the learning tracker logs you have recorded for your students.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Log Form -->
    <div class="col-lg-4">
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-3">Record Progress Log</h5>
            
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
                        <label for="booking_id" class="form-label text-sm fw-medium">Select Student Lesson</label>
                        <select name="booking_id" class="form-select" required>
                            <option value="" selected disabled>Select student...</option>
                            @foreach($completedSessions as $cs)
                                <option value="{{ $cs->id }}">
                                    {{ $cs->student->user->name }} - {{ $cs->subject->name }} ({{ $cs->date->format('m/d') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="progress_percentage" class="form-label text-sm fw-medium">Performance Score (%)</label>
                        <input type="number" name="progress_percentage" class="form-control" required min="0" max="100" placeholder="85">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label text-sm fw-medium">Feedback Summary</label>
                        <textarea name="notes" class="form-control" rows="4" required placeholder="Describe student growth, next steps, syllabus targets..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">Publish Progress Log</button>
                </form>
            @else
                <div class="text-center py-4 text-muted border border-dashed rounded-3">
                    <i class="bi bi-info-circle fs-1 mb-2 d-block text-primary-color"></i>
                    <small class="d-block mb-1">No completed sessions found.</small>
                    <small class="text-xxs">Progress logs require completing booked sessions first.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- History Logs List -->
    <div class="col-lg-8">
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-4">Milestone Logs History</h5>
            
            @if($progressLogs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted text-xs uppercase">
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Score</th>
                                <th>Summary Notes</th>
                                <th>Date Logged</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($progressLogs as $log)
                                <tr>
                                    <td><strong>{{ $log->student->user->name }}</strong></td>
                                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $log->subject->name }}</span></td>
                                    <td><span class="fw-bold text-success">{{ $log->progress_percentage }}%</span></td>
                                    <td><small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $log->notes }}</small></td>
                                    <td><small class="text-muted">{{ $log->created_at->format('Y-m-d') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted border border-dashed rounded-3">
                    <i class="bi bi-graph-down display-4 mb-2 d-block"></i>
                    <small>You have not logged any progress records yet.</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
