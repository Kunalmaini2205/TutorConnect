@extends('layouts.app')

@section('title', 'Manage Subject Categories')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md">
        <h2 class="fw-bold"><i class="bi bi-tags text-primary-color me-2"></i> Academic Subjects & Categories</h2>
        <p class="text-muted">Register, edit, or delete teaching categories available for tutor matchmaking.</p>
    </div>
</div>

<!-- Navigation tabs -->
<div class="card glass-card p-2 mb-4">
    <ul class="nav nav-pills d-flex flex-nowrap overflow-x-auto text-nowrap">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-graph-up me-1"></i> Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.students') }}"><i class="bi bi-mortarboard me-1"></i> Manage Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.tutors') }}"><i class="bi bi-briefcase me-1"></i> Verify Tutors</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.subjects') }}"><i class="bi bi-tags me-1"></i> Subject Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.bookings') }}"><i class="bi bi-calendar-event me-1"></i> Booking Logs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.payments') }}"><i class="bi bi-credit-card me-1"></i> Platform Ledgers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reviews') }}"><i class="bi bi-star me-1"></i> Moderation (Reviews)</a>
        </li>
    </ul>
</div>

<div class="row g-4">
    <!-- Add Category Form -->
    <div class="col-lg-4">
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-3">Add New Category</h5>
            
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label text-sm fw-medium">Subject Name</label>
                    <input type="text" class="form-control" name="name" required placeholder="e.g. Linear Algebra">
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label text-sm fw-medium">Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Brief outline of syllabus covered..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Create Category</button>
            </form>
        </div>
    </div>

    <!-- Categories Grid List -->
    <div class="col-lg-8">
        <div class="card glass-card p-4">
            <h5 class="fw-bold mb-3">Active Subjects Registry</h5>
            
            @if($subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted text-xs uppercase">
                                <th>Subject Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th class="text-center">Instructors</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subj)
                                <tr>
                                    <td><strong>{{ $subj->name }}</strong></td>
                                    <td><code class="text-xs">{{ $subj->slug }}</code></td>
                                    <td><small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $subj->description ?? 'No description.' }}</small></td>
                                    <td class="text-center"><span class="badge bg-primary-color bg-opacity-10 text-primary-color">{{ $subj->tutors_count }}</span></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- Edit Trigger Modal -->
                                            <button type="button" class="btn btn-xs btn-outline-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#editSubjectModal_{{ $subj->id }}">
                                                Edit
                                            </button>

                                            <!-- Delete Subject -->
                                            <form action="{{ route('admin.subjects.destroy', $subj->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subject category? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-outline-danger fw-semibold">Delete</button>
                                            </form>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editSubjectModal_{{ $subj->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content glass-card p-2 border text-start">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Edit Subject Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.subjects.update', $subj->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label text-sm fw-medium">Subject Name</label>
                                                                <input type="text" name="name" class="form-control" value="{{ $subj->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label text-sm fw-medium">Description</label>
                                                                <textarea name="description" class="form-control" rows="3">{{ $subj->description }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
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
                <div class="text-center py-4 text-muted">
                    <small>No subjects categories are currently registered in the database.</small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
