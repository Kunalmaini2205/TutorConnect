<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TutorConnect') - Tutor Booking & Feedback Platform</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Theme Toggle Script (Runs immediately to prevent flash of light theme) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('tutorconnect_theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <style>
        :root {
            --font-family-sans-serif: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --primary-rgb: 111, 66, 193; /* Indigo/Violet color */
            --accent-rgb: 253, 126, 20;   /* Warm Orange accent */
        }
        
        body {
            font-family: var(--font-family-sans-serif);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.5rem;
            color: rgb(var(--primary-rgb));
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        .btn-primary {
            background-color: rgb(var(--primary-rgb));
            border-color: rgb(var(--primary-rgb));
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: rgba(var(--primary-rgb), 0.85);
            border-color: rgba(var(--primary-rgb), 0.85);
        }

        .text-primary-color {
            color: rgb(var(--primary-rgb)) !important;
        }

        .bg-primary-color {
            background-color: rgb(var(--primary-rgb)) !important;
        }

        .border-primary-color {
            border-color: rgb(var(--primary-rgb)) !important;
        }

        /* Glassmorphism Card Style */
        .glass-card {
            background: rgba(var(--bs-body-bg-rgb), 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(var(--primary-rgb), 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px 0 rgba(var(--primary-rgb), 0.1);
        }

        .nav-link.active {
            color: rgb(var(--primary-rgb)) !important;
            font-weight: 600;
        }

        /* Footer styling */
        footer {
            margin-top: auto;
            border-top: 1px solid rgba(var(--primary-rgb), 0.1);
            background-color: var(--bs-tertiary-bg);
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 1px solid rgba(var(--primary-rgb), 0.2);
            background: transparent;
            color: var(--bs-body-color);
            transition: all 0.3s ease;
        }

        .theme-toggle-btn:hover {
            background: rgba(var(--primary-rgb), 0.1);
            color: rgb(var(--primary-rgb));
        }

        /* Avatar styles */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(var(--primary-rgb), 0.1);
            color: rgb(var(--primary-rgb));
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgb(var(--primary-rgb));
            object-fit: cover;
        }
        
        .avatar-lg {
            width: 100px;
            height: 100px;
            font-size: 2rem;
        }

        /* Badge pill customization */
        .badge-verified {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
        }

        .badge-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header & Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top bg-body-tertiary border-bottom" style="border-color: rgba(var(--primary-rgb), 0.08) !important;">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill text-primary-color"></i>
                <span>Tutor<span class="text-primary-color">Connect</span></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('tutors.index') ? 'active' : '' }}" href="{{ route('tutors.index') }}">Find Tutors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Theme Toggle Button -->
                    <button class="theme-toggle-btn" id="themeToggle" title="Toggle Dark/Light Mode" aria-label="Toggle Dark/Light Mode">
                        <i class="bi bi-sun-fill" id="themeIcon"></i>
                    </button>

                    @auth
                        <!-- Chat Shortcut -->
                        <a href="{{ route('chat.index') }}" class="btn btn-outline-secondary position-relative theme-toggle-btn border-0" title="Messages">
                            <i class="bi bi-chat-dots-fill"></i>
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle gap-2 text-reset" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(Auth::user()->profile_picture)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="avatar-circle" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="avatar-circle">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="d-none d-md-inline fw-medium">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                                <li>
                                    <div class="dropdown-header">
                                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                                        <small class="text-muted text-capitalize">{{ Auth::user()->role }} Account</small>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    @if(Auth::user()->isAdmin())
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary-color"></i>Admin Dashboard</a>
                                    @elseif(Auth::user()->isTutor())
                                        <a class="dropdown-item" href="{{ route('tutor.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary-color"></i>Tutor Dashboard</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary-color"></i>Student Dashboard</a>
                                    @endif
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.settings') }}"><i class="bi bi-gear me-2 text-primary-color"></i>Profile Settings</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('learning.tracker') }}"><i class="bi bi-graph-up-arrow me-2 text-primary-color"></i>Learning Tracker</a>
                                </li>
                                @if(Auth::user()->role === 'student')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('payments.history') }}"><i class="bi bi-credit-card me-2 text-primary-color"></i>Billing History</a>
                                    </li>
                                @elseif(Auth::user()->role === 'tutor')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('payments.earnings') }}"><i class="bi bi-cash-stack me-2 text-primary-color"></i>Earnings Ledger</a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger w-100 border-0 bg-transparent"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary fw-medium px-4">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary fw-medium px-4">Join Now</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-4">
        <div class="container">
            <!-- Toast notification messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show glass-card border-success border-opacity-20 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show glass-card border-danger border-opacity-20 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show glass-card border-info border-opacity-20 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5 text-info"></i>
                        <div>{{ session('info') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show glass-card border-warning border-opacity-20 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-octagon-fill me-2 fs-5 text-warning"></i>
                        <div>{{ session('warning') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer py-5 mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a class="navbar-brand mb-3" href="{{ route('home') }}">
                        <i class="bi bi-mortarboard-fill text-primary-color"></i>
                        <span>Tutor<span class="text-primary-color">Connect</span></span>
                    </a>
                    <p class="text-muted mt-2">A Premium Real-Time Tutor Booking and Feedback Platform. Connecting learners with professional instructors instantly.</p>
                    <div class="d-flex gap-3 fs-5 mt-3 text-muted">
                        <a href="#" class="text-reset"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-reset"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-reset"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-reset"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2 offset-lg-2">
                    <h6 class="fw-bold mb-3">Company</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-reset text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Careers</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Press Kit</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold mb-3">Tutor Network</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="{{ route('tutors.index') }}" class="text-reset text-decoration-none">Find Tutors</a></li>
                        <li class="mb-2"><a href="{{ route('register') }}" class="text-reset text-decoration-none">Apply to Teach</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Code of Conduct</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">FAQs</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-reset text-decoration-none">Contact Us</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Help Center</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-reset text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(var(--primary-rgb), 0.1);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-muted">
                <small>&copy; {{ date('Y') }} TutorConnect. All rights reserved. Built with Laravel 11.</small>
                <small class="mt-2 mt-md-0">Design & Architecture Pair Programmed with Antigravity</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Toggle Javascript Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');

            function updateToggleIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.className = 'bi bi-moon-stars-fill text-warning';
                } else {
                    themeIcon.className = 'bi bi-sun-fill text-muted';
                }
            }

            // Sync icon on load
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            updateToggleIcon(currentTheme);

            themeToggle.addEventListener('click', function () {
                const activeTheme = document.documentElement.getAttribute('data-bs-theme');
                const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
                
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('tutorconnect_theme', newTheme);
                updateToggleIcon(newTheme);
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
