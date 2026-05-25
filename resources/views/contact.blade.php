@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="py-5 text-center">
    <h1 class="display-5 fw-extrabold mb-3">Get In Touch</h1>
    <p class="lead text-muted mx-auto" style="max-width: 600px;">
        Have questions about booking, payments, or verification? Contact our support staff and we will respond within 24 hours.
    </p>
</div>

<div class="row g-5 py-3">
    <!-- Contact Info -->
    <div class="col-lg-5">
        <div class="card glass-card p-4 h-100">
            <h4 class="fw-bold mb-4">Contact Information</h4>
            
            <div class="d-flex gap-3 mb-4">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="bi bi-geo-alt-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Office Address</h6>
                    <small class="text-muted">123 Education Plaza, Tech Center, San Francisco, CA 94105</small>
                </div>
            </div>

            <div class="d-flex gap-3 mb-4">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="bi bi-telephone-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Helpline Support</h6>
                    <small class="text-muted">+1 (555) 019-2834 (Mon - Fri, 9 AM - 6 PM PST)</small>
                </div>
            </div>

            <div class="d-flex gap-3 mb-4">
                <div class="bg-primary-color bg-opacity-10 text-primary-color rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="bi bi-envelope-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Email Queries</h6>
                    <small class="text-muted">support@tutorconnect.com</small>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(var(--primary-rgb), 0.1);">

            <h5 class="fw-bold mb-3">Frequently Asked Questions</h5>
            <div class="accordion accordion-flush" id="faqAccordion">
                <div class="accordion-item bg-transparent">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-transparent text-reset fw-medium ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How are tutors verified?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted text-sm ps-0">
                            Tutors submit credentials and degrees during registration. Admin audits records before activating their accounts and zoom rooms.
                        </div>
                    </div>
                </div>
                <div class="accordion-item bg-transparent">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed bg-transparent text-reset fw-medium ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Can I reschedule a session?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted text-sm ps-0">
                            Yes, students or tutors can request to reschedule by choosing another open slot, subject to the tutor's acceptance.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Form -->
    <div class="col-lg-7">
        <div class="card glass-card p-4">
            <h4 class="fw-bold mb-4">Send a Message</h4>
            
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-medium">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="John Doe">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-medium">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="john@example.com">
                    </div>
                    <div class="col-12">
                        <label for="subject" class="form-label fw-medium">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required placeholder="e.g. Booking Enquiry">
                    </div>
                    <div class="col-12">
                        <label for="message" class="form-label fw-medium">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Tell us how we can help..."></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4 py-2.5 fw-semibold">
                            <i class="bi bi-send me-1"></i> Submit Message
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
