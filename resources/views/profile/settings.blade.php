@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-lg-8">
        <h2 class="fw-bold mb-1"><i class="bi bi-gear text-primary-color me-2"></i> Profile Settings</h2>
        <p class="text-muted mb-4">Manage your credentials, update your bio, and change your profile image.</p>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Profile Info Card -->
            <div class="card glass-card p-4 mb-4">
                <h5 class="fw-bold mb-4">Basic Information</h5>
                
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        @if($user->profile_picture)
                            <img id="avatar-preview" src="{{ asset('storage/' . $user->profile_picture) }}" class="avatar-circle avatar-lg" alt="{{ $user->name }}" style="width: 80px; height: 80px;">
                        @else
                            <div id="avatar-preview-div" class="avatar-circle avatar-lg" style="width: 80px; height: 80px; font-size: 1.8rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <label for="profile_picture" class="form-label text-sm fw-bold">Upload New Picture</label>
                        <input class="form-control form-control-sm w-50" type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
                        <small class="text-muted text-xs mt-1 d-block">Recommended square image: JPEG, PNG, or GIF up to 2MB.</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label text-sm fw-semibold">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label text-sm fw-semibold">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="phone" class="form-label text-sm fw-semibold">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label for="bio" class="form-label text-sm fw-semibold">Personal Biography / Statement</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Role-Specific Attributes Card -->
            <input type="hidden" name="role" value="{{ $user->role }}">
            
            @if($user->isStudent())
                <div class="card glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-4">Student Profile</h5>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="grade_level" class="form-label text-sm fw-semibold">Academic Level</label>
                            <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                <option value="Middle School" {{ old('grade_level', $user->student->grade_level) == 'Middle School' ? 'selected' : '' }}>Middle School</option>
                                <option value="High School" {{ old('grade_level', $user->student->grade_level) == 'High School' ? 'selected' : '' }}>High School</option>
                                <option value="Undergraduate" {{ old('grade_level', $user->student->grade_level) == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                <option value="Postgraduate" {{ old('grade_level', $user->student->grade_level) == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                                <option value="Professional / Adult" {{ old('grade_level', $user->student->grade_level) == 'Professional / Adult' ? 'selected' : '' }}>Professional / Adult</option>
                            </select>
                            @error('grade_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="learning_goals" class="form-label text-sm fw-semibold">Learning Goals</label>
                            <textarea class="form-control @error('learning_goals') is-invalid @enderror" id="learning_goals" name="learning_goals" rows="3">{{ old('learning_goals', $user->student->learning_goals) }}</textarea>
                            @error('learning_goals') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            @elseif($user->isTutor())
                <div class="card glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-4">Tutor Profile Settings</h5>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label text-sm fw-semibold">Headline Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $user->tutor->title) }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="hourly_rate" class="form-label text-sm fw-semibold">Hourly Rate ($)</label>
                            <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $user->tutor->hourly_rate) }}" required>
                            @error('hourly_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="experience" class="form-label text-sm fw-semibold">Years of Experience</label>
                            <input type="number" class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience" value="{{ old('experience', $user->tutor->experience) }}" required>
                            @error('experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="qualification" class="form-label text-sm fw-semibold">Highest Credentials / Qualifications</label>
                            <input type="text" class="form-control @error('qualification') is-invalid @enderror" id="qualification" name="qualification" value="{{ old('qualification', $user->tutor->qualification) }}" required>
                            @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            @endif

            <!-- Security Credentials Card -->
            <div class="card glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Security & Password</h5>
                <p class="text-muted text-xs mb-3">Leave blank if you do not wish to update your login password.</p>
                
                <div class="row g-3">
                    <div class="col-12">
                        <label for="current_password" class="form-label text-sm fw-medium">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="••••••••">
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="new_password" class="form-label text-sm fw-medium">New Password</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" placeholder="••••••••">
                        @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="new_password_confirmation" class="form-label text-sm fw-medium">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Save Settings</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-preview-div');
            
            if (output) {
                output.src = reader.result;
            } else if (placeholder) {
                // Swap placeholder with img tag
                const newImg = document.createElement('img');
                newImg.id = 'avatar-preview';
                newImg.src = reader.result;
                newImg.className = 'avatar-circle avatar-lg';
                newImg.style.width = '80px';
                newImg.style.height = '80px';
                placeholder.parentNode.replaceChild(newImg, placeholder);
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
