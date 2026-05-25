@extends('layouts.app')

@section('title', 'Browse Tutors')

@section('content')
<div class="row">
    <!-- Header -->
    <div class="col-12 mb-4">
        <h2 class="fw-bold"><i class="bi bi-search text-primary-color me-2"></i> Find Your Perfect Tutor</h2>
        <p class="text-muted">Connect with verified subject experts for instant 1-on-1 online classes.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Filters Sidebar -->
    <div class="col-lg-4 col-xl-3">
        <div class="card glass-card p-4 sticky-lg-top" style="top: 90px; z-index: 10;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Search Filters</h5>
                <a href="{{ route('tutors.index') }}" class="text-xs text-primary-color text-decoration-none fw-semibold">Reset All</a>
            </div>
            
            <form id="filterForm" action="{{ route('tutors.index') }}" method="GET">
                <!-- Search text -->
                <div class="mb-4">
                    <label for="search" class="form-label text-sm fw-medium">Search Keyword</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" id="search" class="form-control border-start-0 ps-0" placeholder="e.g. John, Algebra, MIT..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Subject Category -->
                <div class="mb-4">
                    <label for="subject" class="form-label text-sm fw-medium">Academic Subject</label>
                    <select name="subject" id="subject" class="form-select">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}" {{ request('subject') == $subj->id ? 'selected' : '' }}>
                                {{ $subj->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="mb-4">
                    <label class="form-label text-sm fw-medium">Hourly Price Range</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" name="min_price" id="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" name="max_price" id="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating -->
                <div class="mb-4">
                    <label class="form-label text-sm fw-medium">Minimum Rating</label>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" id="rating_all" value="" {{ request('rating') === null || request('rating') === '' ? 'checked' : '' }}>
                            <label class="form-check-label text-muted text-sm" for="rating_all">Any rating</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" id="rating_45" value="4.5" {{ request('rating') == '4.5' ? 'checked' : '' }}>
                            <label class="form-check-label text-muted text-sm" for="rating_45"><i class="bi bi-star-fill text-warning me-1"></i> 4.5 ★ & Above</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rating" id="rating_4" value="4.0" {{ request('rating') == '4.0' ? 'checked' : '' }}>
                            <label class="form-check-label text-muted text-sm" for="rating_4"><i class="bi bi-star-fill text-warning me-1"></i> 4.0 ★ & Above</label>
                        </div>
                    </div>
                </div>

                <!-- Sorting -->
                <div class="mb-2">
                    <label for="sort" class="form-label text-sm fw-medium">Sort By</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Highest Rating</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="experience_desc" {{ request('sort') == 'experience_desc' ? 'selected' : '' }}>Teaching Experience</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Tutors Grid List -->
    <div class="col-lg-8 col-xl-9 position-relative">
        <!-- Spinner Overlay -->
        <div id="loader-overlay" class="position-absolute top-0 start-0 w-100 h-100 bg-body bg-opacity-50 d-none justify-content-center pt-5 rounded-3" style="z-index: 100;">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Searching...</span>
            </div>
        </div>

        <div id="tutor-grid-container">
            @include('tutors.partials.tutor_grid')
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('filterForm');
        const gridContainer = document.getElementById('tutor-grid-container');
        const loader = document.getElementById('loader-overlay');

        // Trigger AJAX fetch when filter elements change
        const inputs = form.querySelectorAll('input[type="text"], input[type="number"], select');
        const radios = form.querySelectorAll('input[type="radio"]');

        let timeout = null;
        function performSearch() {
            loader.classList.remove('d-none');
            loader.classList.add('d-flex');

            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            fetch(`{{ route('tutors.index') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                gridContainer.innerHTML = html;
                loader.classList.remove('d-flex');
                loader.classList.add('d-none');
                
                // Update browser URL query params dynamically
                history.pushState(null, '', `{{ route('tutors.index') }}?${params.toString()}`);
                
                // Rebind pagination triggers for AJAX
                bindPaginationTriggers();
            })
            .catch(error => {
                console.error('Error fetching filtered tutors:', error);
                loader.classList.remove('d-flex');
                loader.classList.add('d-none');
            });
        }

        // Apply debounced typing search for keyword
        form.querySelector('#search').addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(performSearch, 500);
        });

        // Other inputs trigger search immediately
        inputs.forEach(input => {
            if (input.id !== 'search') {
                input.addEventListener('change', performSearch);
            }
        });

        radios.forEach(radio => {
            radio.addEventListener('change', performSearch);
        });

        // Intercept pagination clicks for AJAX requests
        function bindPaginationTriggers() {
            const links = document.querySelectorAll('#pagination-links a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    loader.classList.remove('d-none');
                    loader.classList.add('d-flex');

                    const url = this.getAttribute('href');
                    
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        gridContainer.innerHTML = html;
                        loader.classList.remove('d-flex');
                        loader.classList.add('d-none');
                        
                        // Scroll to top of listings
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        
                        // Push history state
                        history.pushState(null, '', url);
                        bindPaginationTriggers();
                    });
                });
            });
        }

        bindPaginationTriggers();
    });
</script>
@endsection
