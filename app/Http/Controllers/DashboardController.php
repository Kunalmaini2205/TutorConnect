<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\AvailabilitySlot;
use App\Models\Review;
use App\Models\Chat;
use App\Models\LearningProgress;
use App\Models\UploadedMaterial;
use App\Models\Tutor;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Student Dashboard
    public function student()
    {
        $student = Auth::user()->student;

        $upcomingBookings = Booking::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->with(['tutor.user', 'subject'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $pastBookings = Booking::where('student_id', $student->id)
            ->whereIn('status', ['completed', 'cancelled', 'rejected'])
            ->with(['tutor.user', 'subject', 'review'])
            ->orderBy('date', 'desc')
            ->get();

        $favoriteTutors = $student->favoriteTutors()->with('user', 'subjects')->get();

        $progressLogs = LearningProgress::where('student_id', $student->id)
            ->with('tutor.user', 'subject')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Calculate progress summary (e.g. average progress score)
        $avgProgress = $progressLogs->avg('progress_percentage') ?? 0;

        return view('student.dashboard', compact(
            'upcomingBookings',
            'pastBookings',
            'favoriteTutors',
            'progressLogs',
            'avgProgress'
        ));
    }

    // Tutor Dashboard
    public function tutor()
    {
        $tutor = Auth::user()->tutor;

        // Pending Bookings
        $pendingBookings = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'pending')
            ->with(['student.user', 'subject'])
            ->orderBy('date')
            ->get();

        // Approved Upcoming sessions
        $upcomingSessions = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'accepted')
            ->with(['student.user', 'subject'])
            ->orderBy('date')
            ->get();

        // Availability Slots
        $slots = AvailabilitySlot::where('tutor_id', $tutor->id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Reviews
        $reviews = Review::where('tutor_id', $tutor->id)
            ->where('is_visible', true)
            ->with('student.user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Materials uploaded
        $materials = UploadedMaterial::where('tutor_id', $tutor->id)->get();

        // Earnings metric
        $earnings = Booking::where('tutor_id', $tutor->id)
            ->where('payment_status', 'paid')
            ->whereIn('status', ['accepted', 'completed'])
            ->sum('total_price');

        return view('tutor.dashboard', compact(
            'pendingBookings',
            'upcomingSessions',
            'slots',
            'reviews',
            'materials',
            'earnings'
        ));
    }

    // Toggle Wishlist/Favorite status
    public function toggleFavorite($tutorId)
    {
        $student = Auth::user()->student;
        if (!$student) {
            return back()->with('error', 'Only students can favorite tutors.');
        }

        $isFavorite = $student->favoriteTutors()->where('tutor_id', $tutorId)->exists();

        if ($isFavorite) {
            $student->favoriteTutors()->detach($tutorId);
            $message = 'Tutor removed from favorites.';
        } else {
            $student->favoriteTutors()->attach($tutorId);
            $message = 'Tutor added to favorites!';
        }

        return back()->with('success', $message);
    }
}
