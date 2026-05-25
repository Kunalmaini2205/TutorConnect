@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-7 col-lg-6">
        <div class="card glass-card p-4">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus-fill text-primary-color display-4"></i>
                <h3 class="fw-bold mt-2">Join TutorConnect</h3>
                <p class="text-muted">Create an account to start booking or teaching lessons</p>
            </div>

            <!-- Role Selector Nav Tabs -->
            <ul class="nav nav-pills nav-fill mb-4 p-1 bg-body-tertiary rounded-pill" id="registerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-semibold" id="student-tab" data-bs-toggle="pill" data-bs-target="#student-form-pane" type="button" role="tab" aria-controls="student-form-pane" aria-selected="true">
                        <i class="bi bi-mortarboard me-1"></i> I am a Student
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-semibold" id="tutor-tab" data-bs-toggle="pill" data-bs-target="#tutor-form-pane" type="button" role="tab" aria-controls="tutor-form-pane" aria-selected="false">
                        <i class="bi bi-briefcase me-1"></i> I am a Tutor
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="registerTabsContent">
                <!-- STUDENT REGISTRATION FORM -->
                <div class="tab-pane fade show active" id="student-form-pane" role="tabpanel" aria-labelledby="student-tab" tabindex="0">
                    <form action="{{ route('register.student') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="s_name" class="form-label fw-medium">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="s_name" name="name" value="{{ old('name') }}" required placeholder="Charlie Brown">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="s_email" class="form-label fw-medium">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="s_email" name="email" value="{{ old('email') }}" required placeholder="charlie@example.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="s_phone" class="form-label fw-medium">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="s_phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 012-3456">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="s_password" class="form-label fw-medium">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="s_password" name="password" required placeholder="••••••••">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="s_password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                <input type="password" class="form-control" id="s_password_confirmation" name="password_confirmation" required placeholder="••••••••">
                            </div>

                            <div class="col-12">
                                <label for="grade_level" class="form-label fw-medium">Grade / Academic Level</label>
                                <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                    <option value="" selected disabled>Select your level...</option>
                                    <option value="Middle School">Middle School</option>
                                    <option value="High School">High School</option>
                                    <option value="Undergraduate">Undergraduate</option>
                                    <option value="Postgraduate">Postgraduate</option>
                                    <option value="Professional / Adult">Professional / Adult</option>
                                </select>
                                @error('grade_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="learning_goals" class="form-label fw-medium">Learning Goals (Optional)</label>
                                <textarea class="form-control @error('learning_goals') is-invalid @enderror" id="learning_goals" name="learning_goals" rows="3" placeholder="Tell us what you wish to learn or improve..."></textarea>
                                @error('learning_goals') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold">
                                    <i class="bi bi-mortarboard-fill me-1"></i> Register as Student
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TUTOR REGISTRATION FORM -->
                <div class="tab-pane fade" id="tutor-form-pane" role="tabpanel" aria-labelledby="tutor-tab" tabindex="0">
                    <form action="{{ route('register.tutor') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="t_name" class="form-label fw-medium">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="t_name" name="name" value="{{ old('name') }}" required placeholder="Dr. Alice Smith">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="t_email" class="form-label fw-medium">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="t_email" name="email" value="{{ old('email') }}" required placeholder="alice@tutorconnect.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="t_phone" class="form-label fw-medium">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="t_phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 987-6543">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="t_password" class="form-label fw-medium">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="t_password" name="password" required placeholder="••••••••">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="t_password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                <input type="password" class="form-control" id="t_password_confirmation" name="password_confirmation" required placeholder="••••••••">
                            </div>

                            <div class="col-12">
                                <label for="title" class="form-label fw-medium">Professional Title / Headline</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required placeholder="e.g. Stanford Organic Chemist / Math Coach">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="hourly_rate" class="form-label fw-medium">Hourly Pricing ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" required placeholder="40.00">
                                </div>
                                @error('hourly_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="experience" class="form-label fw-medium">Years of Experience</label>
                                <input type="number" class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience" required placeholder="4">
                                @error('experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="qualification" class="form-label fw-medium">Highest Qualification / Degree</label>
                                <input type="text" class="form-control @error('qualification') is-invalid @enderror" id="qualification" name="qualification" required placeholder="e.g. Ph.D. in Chemistry, Stanford">
                                @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Subjects You Can Teach</label>
                                <div class="row g-2 border rounded-3 p-3 bg-body-tertiary" style="max-height: 180px; overflow-y: auto;">
                                    @foreach($subjects as $subj)
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="subjects[]" value="{{ $subj->id }}" id="subj_{{ $subj->id }}">
                                                <label class="form-check-label text-sm text-reset" for="subj_{{ $subj->id }}">
                                                    {{ $subj->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('subjects') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-12">
                                <label for="t_bio" class="form-label fw-medium">Bio / Profile Introduction</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="t_bio" name="bio" rows="3" placeholder="Share your teaching style, philosophy, and class structure..."></textarea>
                                @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold">
                                    <i class="bi bi-briefcase-fill me-1"></i> Register as Tutor
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center text-muted text-sm mt-4">
                Already have an account? <a href="{{ route('login') }}" class="text-primary-color text-decoration-none fw-semibold">Sign In here</a>
            </div>
        </div>
    </div>
</div>
@endsection
