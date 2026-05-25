@extends('layouts.app')

@section('title', $tutor->user->name)

@section('content')
<!-- Tutor Header card -->
<div class="card glass-card p-4 mb-4">
    <div class="row align-items-center g-3">
        <div class="col-md-auto text-center">
            @if($tutor->user->profile_picture)
                <img src="{{ asset('storage/' . $tutor->user->profile_picture) }}" class="avatar-circle avatar-lg mx-auto" alt="{{ $tutor->user->name }}" style="width: 120px; height: 120px; font-size: 2.5rem;">
            @else
                <div class="avatar-circle avatar-lg mx-auto" style="width: 120px; height: 120px; font-size: 2.5rem;">
                    {{ substr($tutor->user->name, 0, 1) }}
                </div>
            @endif
        </div>
        
        <div class="col-md">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h3 class="fw-bold mb-1">{{ $tutor->user->name }}</h3>
                    <p class="text-muted mb-2"><i class="bi bi-mortarboard-fill me-1 text-primary-color"></i> {{ $tutor->qualification }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs">
                            <i class="bi bi-patch-check-fill me-1"></i> Vetted Instructor
                        </span>
                        <span class="text-warning fw-semibold text-sm">
                            <i class="bi bi-star-fill me-0.5"></i> {{ $tutor->rating > 0 ? number_format($tutor->rating, 1) : 'New' }}
                        </span>
                        <span class="text-muted text-sm border-start ps-3">
                            <i class="bi bi-briefcase-fill me-1"></i> {{ $tutor->experience }} Years Exp.
                        </span>
                    </div>
                </div>

                <div class="text-md-end">
                    <span class="text-muted text-xs d-block">Hourly Rate</span>
                    <h4 class="fw-extrabold text-primary-color mb-3">${{ number_format($tutor->hourly_rate, 2) }}/hr</h4>
                    
                    <div class="d-flex gap-2">
                        @auth
                            @if(Auth::user()->isStudent())
                                <form action="{{ route('favorites.toggle', $tutor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger fw-semibold px-3 py-2 text-sm d-flex align-items-center gap-1">
                                        @if(Auth::user()->student->favoriteTutors->contains($tutor->id))
                                            <i class="bi bi-heart-fill"></i> Favorited
                                        @else
                                            <i class="bi bi-heart"></i> Favorite
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('chat.start', $tutor->id) }}" class="btn btn-primary fw-semibold px-3 py-2 text-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-chat-dots-fill"></i> Message
                                </a>
                            @else
                                <span class="text-muted text-sm py-2">Logged in as {{ Auth::user()->role }}</span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger fw-semibold px-3 py-2 text-sm"><i class="bi bi-heart"></i> Favorite</a>
                            <a href="{{ route('login') }}" class="btn btn-primary fw-semibold px-3 py-2 text-sm"><i class="bi bi-chat-dots-fill"></i> Message</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Biography & Specialties -->
    <div class="col-lg-7">
        <!-- Bio -->
        <div class="card glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-person-lines-fill text-primary-color me-2"></i> About the Instructor</h5>
            <p class="text-muted" style="line-height: 1.6;">{!! nl2br(e($tutor->bio ?? 'No biography has been added yet.')) !!}</p>
        </div>

        <!-- Subjects -->
        <div class="card glass-card p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-book-half text-primary-color me-2"></i> Teaching Expertise</h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach($tutor->subjects as $subj)
                    <div class="d-flex align-items-center gap-2 border rounded-pill px-3 py-1.5 bg-body-tertiary">
                        <i class="bi bi-journal-bookmark-fill text-primary-color"></i>
                        <span class="fw-semibold text-sm">{{ $subj->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Reviews list -->
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-chat-left-quote text-primary-color me-2"></i> Student Feedback ({{ $tutor->reviews->count() }})</h5>
            
            @if($tutor->reviews->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($tutor->reviews as $rev)
                        @if($rev->is_visible)
                            <div class="border-bottom pb-3 mb-3 last-border-none">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                            {{ substr($rev->student->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-sm">{{ $rev->student->user->name }}</h6>
                                            <small class="text-muted text-xs">{{ $rev->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="text-warning text-sm">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-muted text-sm mb-0">"{{ $rev->comment }}"</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-chat-square-dots fs-1 mb-2 d-block"></i>
                    <small>No reviews left by students yet. Book a session to leave the first review!</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Booking Slots Selection Calendar -->
    <div class="col-lg-5" id="booking-calendar">
        <div class="card glass-card p-4 position-sticky" style="top: 90px;">
            <h5 class="fw-bold mb-2"><i class="bi bi-calendar3 text-primary-color me-2"></i> Schedule an Appointment</h5>
            <p class="text-muted text-sm mb-4">Choose an available date and time slot from the schedule below to initiate a session.</p>

            @if($tutor->availabilitySlots->count() > 0)
                <!-- Group slots by date -->
                @php
                    $groupedSlots = $tutor->availabilitySlots->groupBy(function($slot) {
                        return $slot->date->format('Y-m-d');
                    });
                @endphp

                <div class="accordion accordion-flush" id="slotsAccordion">
                    @foreach($groupedSlots as $dateStr => $slots)
                        @php
                            $dateObj = \Carbon\Carbon::parse($dateStr);
                            $loopIndex = $loop->index;
                        @endphp
                        <div class="accordion-item bg-transparent">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $loopIndex > 0 ? 'collapsed' : '' }} bg-transparent text-reset fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#dateCollapse_{{ $loopIndex }}">
                                    <i class="bi bi-calendar-day me-2 text-primary-color"></i>
                                    {{ $dateObj->format('l, M d, Y') }}
                                    <span class="badge bg-primary-color bg-opacity-10 text-primary-color rounded-pill ms-2 text-xs fw-semibold">{{ $slots->count() }} Open</span>
                                </button>
                            </h2>
                            <div id="dateCollapse_{{ $loopIndex }}" class="accordion-collapse collapse {{ $loopIndex == 0 ? 'show' : '' }}" data-bs-parent="#slotsAccordion">
                                <div class="accordion-body px-0 py-3">
                                    <div class="row g-2">
                                        @foreach($slots as $slot)
                                            <div class="col-6">
                                                <a href="{{ route('booking.checkout', $slot->id) }}" class="btn btn-outline-secondary w-100 text-sm py-2.5 fw-semibold d-flex flex-column align-items-center">
                                                    <span>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</span>
                                                    <span class="text-xs text-muted font-normal mt-0.5">Book Session</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted border border-dashed rounded-3">
                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                    <small class="d-block mb-3">No active availability slots are listed.</small>
                    @auth
                        @if(Auth::user()->isTutor() && Auth::user()->tutor->id == $tutor->id)
                            <a href="{{ route('tutor.dashboard') }}" class="btn btn-primary btn-sm">Add Slots</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
