<?php

namespace App\Http\Controllers;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Tutor: Store availability slot
    public function storeSlot(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $tutor = Auth::user()->tutor;

        // Check if slot already exists for this time
        $exists = AvailabilitySlot::where('tutor_id', $tutor->id)
            ->where('date', $request->date)
            ->where('start_time', $request->start_time . ':00')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You already have an availability slot at this time.');
        }

        AvailabilitySlot::create([
            'tutor_id' => $tutor->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_booked' => false,
        ]);

        return back()->with('success', 'Availability slot added successfully!');
    }

    // Tutor: Delete availability slot
    public function destroySlot($id)
    {
        $slot = AvailabilitySlot::findOrFail($id);
        
        // Ensure tutor owns this slot
        if ($slot->tutor_id !== Auth::user()->tutor->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($slot->is_booked) {
            return back()->with('error', 'Cannot delete a slot that has already been booked.');
        }

        $slot->delete();
        return back()->with('success', 'Availability slot deleted.');
    }

    // Student: Checkout form for booking
    public function checkout($slotId)
    {
        $slot = AvailabilitySlot::with('tutor.user')->findOrFail($slotId);

        if ($slot->is_booked) {
            return redirect()->route('tutors.index')->with('error', 'This slot is already booked.');
        }

        $student = Auth::user()->student;
        $tutor = $slot->tutor;
        $subjects = $tutor->subjects;

        return view('booking.checkout', compact('slot', 'tutor', 'subjects'));
    }

    // Student: Process booking & dummy payment
    public function processBooking(Request $request)
    {
        $request->validate([
            'slot_id' => ['required', 'exists:availability_slots,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'card_name' => ['required', 'string'],
            'card_number' => ['required', 'numeric', 'digits:16'],
            'card_expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'], // MM/YY
            'card_cvc' => ['required', 'numeric', 'digits:3'],
        ]);

        $slot = AvailabilitySlot::findOrFail($request->slot_id);

        if ($slot->is_booked) {
            return redirect()->route('tutors.index')->with('error', 'This slot has already been booked.');
        }

        $student = Auth::user()->student;
        $tutor = $slot->tutor;

        // Perform booking
        $slot->update(['is_booked' => true]);

        $booking = Booking::create([
            'student_id' => $student->id,
            'tutor_id' => $tutor->id,
            'subject_id' => $request->subject_id,
            'slot_id' => $slot->id,
            'date' => $slot->date,
            'start_time' => $slot->start_time,
            'end_time' => $slot->end_time,
            'status' => 'pending',
            'total_price' => $tutor->hourly_rate,
            'payment_status' => 'paid', // Dummy checkout marks as paid instantly
            'meet_link' => null,
        ]);

        // Record dummy payment
        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
            'amount' => $tutor->hourly_rate,
            'payment_method' => 'card',
            'status' => 'success',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Booking requested and payment processed successfully!');
    }

    // Tutor: Accept Booking request
    public function acceptBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be accepted.');
        }

        // Generate zoom meeting link
        $meetLink = 'https://meet.google.com/' . Str::lower(Str::random(3)) . '-' . Str::lower(Str::random(4)) . '-' . Str::lower(Str::random(3));

        $booking->update([
            'status' => 'accepted',
            'meet_link' => $meetLink,
            'status_notes' => 'Tutor accepted the session. Use the link below at the scheduled time.'
        ]);

        return back()->with('success', 'Booking accepted. Meeting link generated!');
    }

    // Tutor: Reject Booking request
    public function rejectBooking(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $booking = Booking::findOrFail($id);

        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be rejected.');
        }

        // Refund payment (simulated)
        if ($booking->payment) {
            $booking->payment->update(['status' => 'failed']); // Represents refunded/reversed
        }

        // Release availability slot
        if ($booking->availabilitySlot) {
            $booking->availabilitySlot->update(['is_booked' => false]);
        }

        $booking->update([
            'status' => 'rejected',
            'payment_status' => 'unpaid', // marked unpaid upon refund
            'status_notes' => $request->notes ?? 'Rejected by tutor.'
        ]);

        return back()->with('success', 'Booking request rejected. Payment refund simulated.');
    }

    // Cancel Booking (Student or Tutor)
    public function cancelBooking(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $booking = Booking::findOrFail($id);
        $user = Auth::user();

        // Check permission
        if ($user->isTutor() && $booking->tutor_id !== $user->tutor->id) {
            return back()->with('error', 'Unauthorized action.');
        }
        if ($user->isStudent() && $booking->student_id !== $user->student->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if (in_array($booking->status, ['completed', 'cancelled', 'rejected'])) {
            return back()->with('error', 'Cannot cancel this booking in its current state.');
        }

        // Release slot
        if ($booking->availabilitySlot) {
            $booking->availabilitySlot->update(['is_booked' => false]);
        }

        // Refund (simulated)
        if ($booking->payment) {
            $booking->payment->update(['status' => 'failed']);
        }

        $booking->update([
            'status' => 'cancelled',
            'payment_status' => 'unpaid',
            'status_notes' => 'Cancelled by ' . $user->name . '. ' . ($request->notes ?? '')
        ]);

        return back()->with('success', 'Booking cancelled successfully. Slot released and payment refunded.');
    }

    // Reschedule booking
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'new_slot_id' => 'required|exists:availability_slots,id'
        ]);

        $booking = Booking::findOrFail($id);
        $newSlot = AvailabilitySlot::findOrFail($request->new_slot_id);

        if ($newSlot->is_booked) {
            return back()->with('error', 'The selected slot is already booked.');
        }

        $user = Auth::user();
        if ($user->isTutor() && $booking->tutor_id !== $user->tutor->id) {
            return back()->with('error', 'Unauthorized action.');
        }
        if ($user->isStudent() && $booking->student_id !== $user->student->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Free old slot
        if ($booking->availabilitySlot) {
            $booking->availabilitySlot->update(['is_booked' => false]);
        }

        // Claim new slot
        $newSlot->update(['is_booked' => true]);

        $booking->update([
            'slot_id' => $newSlot->id,
            'date' => $newSlot->date,
            'start_time' => $newSlot->start_time,
            'end_time' => $newSlot->end_time,
            'status' => 'pending', // Rescheduling resets to pending for tutor approval
            'meet_link' => null,   // Resets link until approved
            'status_notes' => 'Rescheduled to ' . $newSlot->date->format('Y-m-d') . ' at ' . Carbon::parse($newSlot->start_time)->format('H:i')
        ]);

        return back()->with('success', 'Booking rescheduled. Pending approval from the tutor.');
    }

    // Complete Booking
    public function completeBooking($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Ensure user is tutor of this booking
        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($booking->status !== 'accepted') {
            return back()->with('error', 'Only accepted bookings can be marked as completed.');
        }

        $booking->update([
            'status' => 'completed',
            'status_notes' => 'Session marked as completed by Tutor.'
        ]);

        return back()->with('success', 'Session marked as completed! You can now log progress and notes.');
    }
}
