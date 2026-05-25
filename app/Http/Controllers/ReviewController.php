<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Store student rating and review
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $student = Auth::user()->student;

        // Verify this booking belongs to the student and is completed
        if ($booking->student_id !== $student->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed sessions.');
        }

        // Check if review already exists
        $exists = Review::where('booking_id', $booking->id)->exists();
        if ($exists) {
            return back()->with('error', 'You have already reviewed this session.');
        }

        Review::create([
            'booking_id' => $booking->id,
            'student_id' => $student->id,
            'tutor_id' => $booking->tutor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_visible' => true,
        ]);

        // Recalculate tutor average rating
        $tutor = Tutor::findOrFail($booking->tutor_id);
        $avgRating = Review::where('tutor_id', $tutor->id)
            ->where('is_visible', true)
            ->avg('rating');

        $tutor->update([
            'rating' => $avgRating ? round($avgRating, 2) : 0.00
        ]);

        return back()->with('success', 'Thank you for your feedback! Your review has been published.');
    }
}
