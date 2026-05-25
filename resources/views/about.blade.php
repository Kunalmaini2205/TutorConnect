@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="py-5 text-center">
    <h1 class="display-5 fw-extrabold mb-3">Our Mission: <span class="text-primary-color">Empowering Learners</span> Anywhere</h1>
    <p class="lead text-muted mx-auto" style="max-width: 800px;">
        TutorConnect was founded to eliminate the friction in locating qualified academic support. We link motivated students with verified specialists, facilitating flexible scheduling, direct interaction, and structured feedback.
    </p>
</div>

<div class="row g-4 py-5 align-items-center">
    <div class="col-lg-6">
        <h3 class="fw-bold mb-3">Connecting Education and Technology</h3>
        <p class="text-muted">
            Finding a qualified tutor who fits your learning style, matches your budget, and aligns with your schedule should not be a daunting task. TutorConnect uses structured data and real-time scheduling so students can secure lessons in seconds.
        </p>
        <p class="text-muted">
            Beyond bookings, we build learning logs, progress percentages, and lesson review frameworks. This ensures parents, students, and tutors are fully aligned on student growth and key conceptual milestones.
        </p>
        <div class="row g-3 mt-3">
            <div class="col-6">
                <div class="p-3 border rounded-3 bg-body-tertiary">
                    <h5 class="fw-bold text-primary-color mb-1">100% Verified</h5>
                    <small class="text-muted">All credentials vetted by administrator</small>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 border rounded-3 bg-body-tertiary">
                    <h5 class="fw-bold text-primary-color mb-1">Progress Tracking</h5>
                    <small class="text-muted">Dynamic tracking and certificate generation</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-compass-fill text-primary-color me-2"></i> Our Core Values</h5>
            
            <div class="d-flex gap-3 mb-4">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded p-2.5 h-100 align-self-start">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Trust & Vetting</h6>
                    <small class="text-muted">We manually audit credentials, certificates, and qualifications to ensure academic integrity.</small>
                </div>
            </div>

            <div class="d-flex gap-3 mb-4">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded p-2.5 h-100 align-self-start">
                    <i class="bi bi-graph-up fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Learning Outcomes</h6>
                    <small class="text-muted">We provide visual progress metrics, milestones, and certificates of completion to reward efforts.</small>
                </div>
            </div>

            <div class="d-flex gap-3">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded p-2.5 h-100 align-self-start">
                    <i class="bi bi-lightning-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Real-time Agility</h6>
                    <small class="text-muted">No wait-times. Instantly book slots, process payments, and exchange messages with tutors.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team section -->
<div class="py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Meet the Founders</h2>
        <p class="text-muted">The engineering and education minds behind TutorConnect.</p>
    </div>
    
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card glass-card h-100">
                <div class="card-body p-4">
                    <div class="avatar-circle avatar-lg mx-auto mb-3">KM</div>
                    <h5 class="fw-bold mb-1">Kunal Maini</h5>
                    <small class="text-primary-color fw-semibold">Chief Executive & Founder</small>
                    <p class="text-muted text-sm mt-3">Leading project scope, software delivery, and curriculum verification programs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card glass-card h-100">
                <div class="card-body p-4">
                    <div class="avatar-circle avatar-lg mx-auto mb-3">SA</div>
                    <h5 class="fw-bold mb-1">Sahil</h5>
                    <small class="text-primary-color fw-semibold">Co-Founder & COO</small>
                    <p class="text-muted text-sm mt-3">Vetting applicant credentials and managing quality guidelines for lesson deliveries.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card glass-card h-100">
                <div class="card-body p-4">
                    <div class="avatar-circle avatar-lg mx-auto mb-3">GA</div>
                    <h5 class="fw-bold mb-1">Ganesh</h5>
                    <small class="text-primary-color fw-semibold">Co-Founder & CTO</small>
                    <p class="text-muted text-sm mt-3">Providing system engineering, code generation patterns, and responsive UI consulting.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
