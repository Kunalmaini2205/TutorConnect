@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row align-items-center py-5 g-5">
    <!-- Hero Info -->
    <div class="col-lg-6">
        <span class="badge bg-primary-color bg-opacity-10 text-primary-color mb-3 px-3 py-2 rounded-pill fw-semibold">
            <i class="bi bi-star-fill me-1"></i> Learn from the best
        </span>
        <h1 class="display-4 fw-extrabold mb-3 lh-sm" style="font-weight: 800;">
            Find and Book <span class="text-primary-color">Professional Tutors</span> In Real-Time
        </h1>
        <p class="lead text-muted mb-4">
            Connect with certified tutors for personalized 1-on-1 lessons. Book slots instantly, chat directly, process secured payments, and watch your academic progress soar.
        </p>
        
        <!-- Quick Search -->
        <div class="glass-card p-3 mb-4">
            <form action="{{ route('tutors.index') }}" method="GET" class="row g-2">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 ps-0" placeholder="Search math, chemistry, coding...">
                    </div>
                </div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold">
                        <i class="bi bi-search me-1"></i> Search Tutors
                    </button>
                </div>
            </form>
        </div>

        <div class="d-flex align-items-center gap-4 text-muted">
            <small><i class="bi bi-shield-check text-success me-1 fs-5 align-middle"></i> Verified Instructors</small>
            <small><i class="bi bi-chat-heart text-success me-1 fs-5 align-middle"></i> Interactive Live Chat</small>
            <small><i class="bi bi-clock-history text-success me-1 fs-5 align-middle"></i> Flexible Scheduling</small>
        </div>
    </div>
    
    <!-- Hero Illustration / Image -->
    <div class="col-lg-6 text-center">
        <div class="position-relative d-inline-block">
            <!-- Decorative colored bubbles -->
            <div class="position-absolute top-0 start-0 translate-middle bg-primary-color rounded-circle opacity-10 filter-blur" style="width: 250px; height: 250px; filter: blur(40px);"></div>
            <div class="position-absolute bottom-0 end-0 translate-middle-x bg-warning rounded-circle opacity-10 filter-blur" style="width: 200px; height: 200px; filter: blur(40px);"></div>
            
            <div class="glass-card p-4 text-start position-relative z-1 border border-opacity-10 shadow-lg mx-auto" style="max-width: 480px;">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary-color text-white p-3 rounded-3">
                        <i class="bi bi-quote fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Premium Learning</h6>
                        <small class="text-muted">Direct from MIT, Stanford & Oxford</small>
                    </div>
                </div>
                <blockquote class="blockquote fs-6 text-muted mb-4">
                    "TutorConnect changed how I study. I found a Calculus tutor in 5 minutes, booked a slot, and got a 95% on my final exam. The certificate is a great addition!"
                </blockquote>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle">C</div>
                        <div>
                            <div class="fw-bold text-sm">Charlie Brown</div>
                            <small class="text-muted">High School Student</small>
                        </div>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i class="bi bi-check-circle-fill"></i> Verified Review</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Platform Stats -->
<div class="row text-center py-5 my-5 bg-body-tertiary rounded-4 g-4 border border-opacity-5" style="border: 1px solid rgba(var(--primary-rgb), 0.1);">
    <div class="col-md-4">
        <h2 class="display-5 fw-extrabold text-primary-color">15+</h2>
        <p class="text-muted mb-0 fw-medium">Verified Subject Specialists</p>
    </div>
    <div class="col-md-4 border-start border-end border-opacity-10">
        <h2 class="display-5 fw-extrabold text-primary-color">120+</h2>
        <p class="text-muted mb-0 fw-medium">Learning Sessions Completed</p>
    </div>
    <div class="col-md-4">
        <h2 class="display-5 fw-extrabold text-primary-color">4.9★</h2>
        <p class="text-muted mb-0 fw-medium">Average Lesson Rating</p>
    </div>
</div>

<!-- Top Verified Tutors -->
<div class="py-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold">Meet Our Top Rated Tutors</h2>
            <p class="text-muted">Highly qualified, vetted instructors ready to help you succeed.</p>
        </div>
        <a href="{{ route('tutors.index') }}" class="btn btn-outline-primary fw-medium">
            Explore All Tutors <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        <!-- Tutor 1: John Doe -->
        <div class="col-md-4">
            <div class="card h-100 glass-card">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-circle avatar-lg">J</div>
                        <div>
                            <h5 class="card-title fw-bold mb-1">John Doe</h5>
                            <small class="text-muted d-block">M.Sc. in Physics, MIT</small>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs mt-1.5"><i class="bi bi-patch-check-fill me-1"></i> Verified</span>
                        </div>
                    </div>
                    <h6 class="fw-semibold text-primary-color mb-2">Experienced Math & Physics Professor</h6>
                    <p class="card-text text-muted text-sm flex-grow-1">
                        Over 8 years experience teaching algebra, calculus, classical mechanics, and thermodynamics. Focuses on simple explanations...
                    </p>
                    <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-xs">Hourly Rate</span>
                            <div class="fw-bold text-lg text-primary-color">$45.00/hr</div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted text-xs">Rating</span>
                            <div class="fw-semibold text-warning"><i class="bi bi-star-fill me-1"></i> 4.8</div>
                        </div>
                    </div>
                    <a href="{{ route('tutors.index') }}" class="btn btn-primary w-100 mt-4 py-2 fw-semibold">Book a Session</a>
                </div>
            </div>
        </div>

        <!-- Tutor 2: Jane Smith -->
        <div class="col-md-4">
            <div class="card h-100 glass-card">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-circle avatar-lg">J</div>
                        <div>
                            <h5 class="card-title fw-bold mb-1">Jane Smith</h5>
                            <small class="text-muted d-block">Ph.D. in Chem, Stanford</small>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs mt-1.5"><i class="bi bi-patch-check-fill me-1"></i> Verified</span>
                        </div>
                    </div>
                    <h6 class="fw-semibold text-primary-color mb-2">Chemistry & Biology Specialist</h6>
                    <p class="card-text text-muted text-sm flex-grow-1">
                        Conceptual clarity in organic reaction mechanisms and biology topics. Prep coach for college examinations and MCAT chemistry sections...
                    </p>
                    <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-xs">Hourly Rate</span>
                            <div class="fw-bold text-lg text-primary-color">$50.00/hr</div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted text-xs">Rating</span>
                            <div class="fw-semibold text-warning"><i class="bi bi-star-fill me-1"></i> 5.0</div>
                        </div>
                    </div>
                    <a href="{{ route('tutors.index') }}" class="btn btn-primary w-100 mt-4 py-2 fw-semibold">Book a Session</a>
                </div>
            </div>
        </div>

        <!-- Tutor 3: Bob Johnson -->
        <div class="col-md-4">
            <div class="card h-100 glass-card">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-circle avatar-lg">B</div>
                        <div>
                            <h5 class="card-title fw-bold mb-1">Bob Johnson</h5>
                            <small class="text-muted d-block">B.S. in CS, UC Berkeley</small>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 text-xs mt-1.5"><i class="bi bi-patch-check-fill me-1"></i> Verified</span>
                        </div>
                    </div>
                    <h6 class="fw-semibold text-primary-color mb-2">Software Engineer & CS Instructor</h6>
                    <p class="card-text text-muted text-sm flex-grow-1">
                        Learn Python, PHP, Laravel, and frontend stacks with a hands-on methodology. Perfect for students working on term projects...
                    </p>
                    <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-xs">Hourly Rate</span>
                            <div class="fw-bold text-lg text-primary-color">$60.00/hr</div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted text-xs">Rating</span>
                            <div class="fw-semibold text-warning"><i class="bi bi-star-fill me-1"></i> 4.5</div>
                        </div>
                    </div>
                    <a href="{{ route('tutors.index') }}" class="btn btn-primary w-100 mt-4 py-2 fw-semibold">Book a Session</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="py-5 bg-body-tertiary rounded-4 my-5 px-4 px-md-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">How TutorConnect Works</h2>
        <p class="text-muted">A simple, transparent 4-step path to achieve your learning goals.</p>
    </div>
    
    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div class="bg-primary-color bg-opacity-10 text-primary-color rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-search fs-3"></i>
            </div>
            <h5 class="fw-bold">1. Find Tutors</h5>
            <p class="text-muted text-sm">Search by subject and filter by hourly price, rating, and availability schedules.</p>
        </div>
        <div class="col-md-3">
            <div class="bg-primary-color bg-opacity-10 text-primary-color rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-calendar-event fs-3"></i>
            </div>
            <h5 class="fw-bold">2. Book Slots</h5>
            <p class="text-muted text-sm">Select dates and times from the tutor's calendar. Make secure dummy card checkout.</p>
        </div>
        <div class="col-md-3">
            <div class="bg-primary-color bg-opacity-10 text-primary-color rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-chat-left-dots fs-3"></i>
            </div>
            <h5 class="fw-bold">3. Learn Live</h5>
            <p class="text-muted text-sm">Connect in-app via direct messaging and access mock Zoom classroom links.</p>
        </div>
        <div class="col-md-3">
            <div class="bg-primary-color bg-opacity-10 text-primary-color rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                <i class="bi bi-clipboard-check fs-3"></i>
            </div>
            <h5 class="fw-bold">4. Track Progress</h5>
            <p class="text-muted text-sm">Read tutor session summaries, track performance score gains, and get completion certificates.</p>
        </div>
    </div>
</div>
@endsection
