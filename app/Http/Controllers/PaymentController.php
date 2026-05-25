<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    // Student: View payment history
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with(['booking.tutor.user', 'booking.subject'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.student_history', compact('payments'));
    }

    // Tutor: View earnings history
    public function earnings()
    {
        $tutor = Auth::user()->tutor;
        
        $bookings = Booking::where('tutor_id', $tutor->id)
            ->where('payment_status', 'paid')
            ->whereIn('status', ['accepted', 'completed'])
            ->with(['student.user', 'payment', 'subject'])
            ->orderBy('date', 'desc')
            ->get();

        $totalEarnings = $bookings->sum('total_price');
        $completedSessions = $bookings->where('status', 'completed')->count();

        return view('payments.tutor_earnings', compact('bookings', 'totalEarnings', 'completedSessions'));
    }

    // Generate and download PDF Invoice
    public function downloadInvoice($bookingId)
    {
        $booking = Booking::with(['student.user', 'tutor.user', 'subject', 'payment'])->findOrFail($bookingId);
        $user = Auth::user();

        // Ensure user is authorized to download this invoice
        if ($user->role === 'student' && $booking->student_id !== $user->student->id) {
            abort(403, 'Unauthorized.');
        }
        if ($user->role === 'tutor' && $booking->tutor_id !== $user->tutor->id) {
            abort(403, 'Unauthorized.');
        }

        if (!$booking->payment) {
            return back()->with('error', 'No payment has been recorded for this booking yet.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('booking.invoice', compact('booking'));
        
        return $pdf->download('tutorconnect_invoice_' . $booking->id . '_' . $booking->date->format('Y-m-d') . '.pdf');
    }
}
