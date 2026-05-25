<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TutorSearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LearningTrackerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;

// 1. PUBLIC PAGES
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function () {
    return back()->with('success', 'Thank you! Your feedback message has been received (simulated).');
})->name('contact.send');

// 2. TUTOR SEARCH & PROFILES (Publicly accessible)
Route::get('/tutors', [TutorSearchController::class, 'index'])->name('tutors.index');
Route::get('/tutors/{id}', [TutorSearchController::class, 'show'])->name('tutors.show');

// 3. AUTHENTICATION (Guest routes)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register/student', [AuthController::class, 'registerStudent'])->name('register.student');
    Route::post('/register/tutor', [AuthController::class, 'registerTutor'])->name('register.tutor');
    
    Route::get('/otp-verify/{userId}', [AuthController::class, 'showOtpForm'])->name('otp.verify.form');
    Route::post('/otp-verify/{userId}', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.email');
});

// 4. PROTECTED USER SHARED ROUTES (Requires Auth)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile settings
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'update'])->name('profile.update');
    
    // Live Chat system
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/start/{tutorId}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::post('/chat/{chatId}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/{chatId}/fetch', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    
    // Learning materials & tracking shared list
    Route::get('/learning/tracker', [LearningTrackerController::class, 'progressHistory'])->name('learning.tracker');
    Route::get('/materials/download/{id}', [LearningTrackerController::class, 'downloadMaterial'])->name('materials.download');
});

// 5. STUDENT PORTAL ONLY
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', [App\Http\Controllers\DashboardController::class, 'student'])->name('student.dashboard');
    
    // Bookings checkout
    Route::get('/booking/checkout/{slotId}', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/checkout', [BookingController::class, 'processBooking'])->name('booking.process');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('booking.cancel');
    Route::post('/booking/{id}/reschedule', [BookingController::class, 'reschedule'])->name('booking.reschedule');
    
    // Wishlist Toggle
    Route::post('/favorites/toggle/{tutorId}', [App\Http\Controllers\DashboardController::class, 'toggleFavorite'])->name('favorites.toggle');
    
    // Invoice & Certificate downloads
    Route::get('/booking/{id}/invoice', [PaymentController::class, 'downloadInvoice'])->name('payments.invoice');
    Route::get('/booking/{id}/certificate', [LearningTrackerController::class, 'downloadCertificate'])->name('learning.certificate');
    Route::get('/payments/history', [PaymentController::class, 'index'])->name('payments.history');
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// 6. TUTOR PORTAL ONLY
Route::middleware(['auth', 'role:tutor'])->group(function () {
    Route::get('/tutor/dashboard', [App\Http\Controllers\DashboardController::class, 'tutor'])->name('tutor.dashboard');
    
    // Schedule slot adjustments
    Route::post('/slots', [BookingController::class, 'storeSlot'])->name('slots.store');
    Route::delete('/slots/{id}', [BookingController::class, 'destroySlot'])->name('slots.destroy');
    
    // Booking accept/reject controls
    Route::post('/booking/{id}/accept', [BookingController::class, 'acceptBooking'])->name('booking.accept');
    Route::post('/booking/{id}/reject', [BookingController::class, 'rejectBooking'])->name('booking.reject');
    Route::post('/booking/{id}/complete', [BookingController::class, 'completeBooking'])->name('booking.complete');
    
    // Progress logs & document uploads
    Route::post('/progress', [LearningTrackerController::class, 'storeProgress'])->name('progress.store');
    Route::post('/materials', [LearningTrackerController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/payments/earnings', [PaymentController::class, 'earnings'])->name('payments.earnings');
});

// 7. ADMIN PANEL ONLY
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User lists suspension controls
    Route::get('/admin/students', [AdminController::class, 'students'])->name('admin.students');
    Route::get('/admin/tutors', [AdminController::class, 'tutors'])->name('admin.tutors');
    Route::post('/admin/tutors/{id}/toggle-verify', [AdminController::class, 'verifyTutor'])->name('admin.tutors.toggle-verify');
    Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
    
    // Subject CRUD controls
    Route::get('/admin/subjects', [AdminController::class, 'subjects'])->name('admin.subjects');
    Route::post('/admin/subjects', [AdminController::class, 'storeSubject'])->name('admin.subjects.store');
    Route::put('/admin/subjects/{id}', [AdminController::class, 'updateSubject'])->name('admin.subjects.update');
    Route::delete('/admin/subjects/{id}', [AdminController::class, 'destroySubject'])->name('admin.subjects.destroy');
    
    // Booking, Payments & Reviews moderation controls
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::get('/admin/payments', [AdminController::class, 'payments'])->name('admin.payments');
    
    Route::get('/admin/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::post('/admin/reviews/{id}/toggle-visibility', [AdminController::class, 'toggleReviewVisibility'])->name('admin.reviews.toggle-visibility');
    Route::delete('/admin/reviews/{id}', [AdminController::class, 'destroyReview'])->name('admin.reviews.destroy');
    
    // Reports CSV Exports
    Route::get('/admin/reports/users', [AdminController::class, 'exportUsersCsv'])->name('admin.export.users');
    Route::get('/admin/reports/bookings', [AdminController::class, 'exportBookingsCsv'])->name('admin.export.bookings');
});
