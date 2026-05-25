@if($tutors->count() > 0)
    <div class="row g-4">
        @foreach($tutors as $tutor)
            <div class="col-md-6">
                <div class="card h-100 glass-card">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($tutor->user->profile_picture)
                                <img src="{{ asset('storage/' . $tutor->user->profile_picture) }}" class="avatar-circle avatar-lg" alt="{{ $tutor->user->name }}">
                            @else
                                <div class="avatar-circle avatar-lg">
                                    {{ substr($tutor->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="card-title fw-bold mb-0 text-reset">{{ $tutor->user->name }}</h5>
                                    @auth
                                        @if(Auth::user()->isStudent())
                                            <form action="{{ route('favorites.toggle', $tutor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 border-0 text-reset" title="Toggle Favorite">
                                                    @if(Auth::user()->student->favoriteTutors->contains($tutor->id))
                                                        <i class="bi bi-heart-fill text-danger fs-5"></i>
                                                    @else
                                                        <i class="bi bi-heart text-muted fs-5"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                                <small class="text-muted d-block mt-0.5">{{ $tutor->qualification }}</small>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-0.5 text-xs mt-1">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified Partner
                                </span>
                            </div>
                        </div>

                        <h6 class="fw-semibold text-primary-color mb-2">{{ $tutor->title }}</h6>
                        <p class="card-text text-muted text-sm flex-grow-1">
                            {{ Str::limit($tutor->bio, 130, '...') }}
                        </p>

                        <div class="mb-3 d-flex flex-wrap gap-1">
                            @foreach($tutor->subjects as $subject)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-15 rounded-pill px-2.5 py-1 text-xs">
                                    {{ $subject->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted text-xs d-block">Hourly Rate</span>
                                <span class="fw-bold text-lg text-primary-color">${{ number_format($tutor->hourly_rate, 2) }}/hr</span>
                            </div>
                            <div class="text-end">
                                <span class="text-muted text-xs d-block">Experience</span>
                                <span class="fw-semibold">{{ $tutor->experience }} years</span>
                            </div>
                            <div class="text-end">
                                <span class="text-muted text-xs d-block font-medium">Student Rating</span>
                                <span class="fw-semibold text-warning">
                                    <i class="bi bi-star-fill me-0.5"></i> {{ $tutor->rating > 0 ? number_format($tutor->rating, 1) : 'New' }}
                                </span>
                            </div>
                        </div>

                        <div class="row g-2 mt-3">
                            <div class="col-6">
                                <a href="{{ route('tutors.show', $tutor->id) }}" class="btn btn-outline-primary w-100 py-2 fw-semibold text-sm">
                                    <i class="bi bi-person-badge-fill me-1"></i> Profile
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('tutors.show', $tutor->id) }}#booking-calendar" class="btn btn-primary w-100 py-2 fw-semibold text-sm">
                                    <i class="bi bi-calendar-check-fill me-1"></i> Book Slot
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- AJAX Pagination Controls -->
    <div class="d-flex justify-content-center mt-5" id="pagination-links">
        {{ $tutors->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="text-center py-5 glass-card">
        <i class="bi bi-people text-muted display-3 mb-3"></i>
        <h4 class="fw-bold">No Tutors Found</h4>
        <p class="text-muted mx-auto" style="max-width: 450px;">We couldn't find any tutors matching your active filters. Try resetting search fields or easing the filters.</p>
        <a href="{{ route('tutors.index') }}" class="btn btn-primary mt-2">Clear All Filters</a>
    </div>
@endif
