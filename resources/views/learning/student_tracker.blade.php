@extends('layouts.app')

@section('title', 'Learning Progress Tracker')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold"><i class="bi bi-graph-up-arrow text-success me-2"></i> Learning Progress Tracker</h2>
        <p class="text-muted">Track your performance milestones, review tutor notes, and download lesson resource materials.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Progress logs timeline -->
    <div class="col-lg-7">
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-4">Milestone Timeline</h5>
            
            @if($progressLogs->count() > 0)
                <div class="position-relative ps-4 border-start border-primary border-opacity-20" style="margin-left: 10px;">
                    @foreach($progressLogs as $log)
                        <div class="position-relative mb-4">
                            <!-- Bullet marker -->
                            <div class="position-absolute top-0 start-0 translate-middle-x bg-primary-color rounded-circle border border-white" style="width: 14px; height: 14px; left: -21px;"></div>
                            
                            <div class="card bg-body-tertiary bg-opacity-30 border-0 rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-sm">{{ $log->subject->name }}</h6>
                                        <small class="text-muted text-xs">Instructor: {{ $log->tutor->user->name }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs fw-bold">
                                            Score: {{ $log->progress_percentage }}%
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $log->progress_percentage }}%" aria-valuenow="{{ $log->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <p class="text-muted text-sm mb-0">"{!! nl2br(e($log->notes)) !!}"</p>
                                <small class="text-muted text-xxs d-block mt-2"><i class="bi bi-clock-history"></i> Logged: {{ $log->created_at->format('l, F d, Y') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted border border-dashed rounded-3">
                    <i class="bi bi-graph-down display-4 mb-2 d-block text-primary-color bg-opacity-10"></i>
                    <small>No progress logs have been published for you yet.</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Materials list -->
    <div class="col-lg-5">
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-arrow-down text-primary-color me-2"></i> Class Resources</h5>
            <p class="text-muted text-xs mb-4">Academic files uploaded by your class instructors for download and reference.</p>
            
            @if($materials->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($materials as $mat)
                        <div class="p-3 border rounded bg-body-tertiary bg-opacity-40 d-flex justify-content-between align-items-center">
                            <div class="overflow-hidden me-3">
                                <strong class="text-sm text-truncate d-block mb-1">{{ $mat->title }}</strong>
                                <p class="text-muted text-xs mb-1 text-truncate">{{ $mat->description ?? 'Class reference documents.' }}</p>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary text-xxs">{{ strtoupper($mat->file_type) }}</span>
                                    <small class="text-muted text-xxs">Downloads: {{ $mat->downloads }}</small>
                                </div>
                            </div>
                            
                            <a href="{{ route('materials.download', $mat->id) }}" class="btn btn-sm btn-primary py-2 px-3 fw-semibold text-xs d-flex align-items-center gap-1">
                                <i class="bi bi-download"></i> Get File
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted border border-dashed rounded-3">
                    <i class="bi bi-file-x fs-1 d-block mb-2"></i>
                    <small>No resource files are available for download.</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
