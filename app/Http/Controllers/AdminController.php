<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Admin Dashboard Analytics
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalTutors = Tutor::count();
        $pendingTutors = Tutor::where('is_verified', false)->count();
        $totalBookings = Booking::count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');

        $recentBookings = Booking::with(['student.user', 'tutor.user', 'subject'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPayments = Payment::with(['user', 'booking.tutor.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTutors',
            'pendingTutors',
            'totalBookings',
            'completedBookings',
            'totalRevenue',
            'recentBookings',
            'recentPayments'
        ));
    }

    // Manage Students
    public function students(Request $request)
    {
        $query = Student::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(10);
        return view('admin.students', compact('students'));
    }

    // Manage Tutors
    public function tutors(Request $request)
    {
        $query = Tutor::with('user', 'subjects');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_verified', false);
            }
        }

        $tutors = $query->paginate(10);
        return view('admin.tutors', compact('tutors'));
    }

    // Verify Tutor Account
    public function verifyTutor($id)
    {
        $tutor = Tutor::findOrFail($id);
        $tutor->is_verified = !$tutor->is_verified;
        
        // Generate mock zoom link when verified
        if ($tutor->is_verified) {
            $tutor->zoom_link = 'https://zoom.us/mock-classroom-' . Str::slug($tutor->user->name);
        } else {
            $tutor->zoom_link = null;
        }
        
        $tutor->save();

        $status = $tutor->is_verified ? 'verified and Zoom link assigned.' : 'unverified.';
        return back()->with('success', "Tutor account status updated: Tutor is now {$status}");
    }

    // Toggle User Suspension
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot suspend yourself.');
        }

        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        $action = $user->status === 'active' ? 'activated' : 'suspended';
        return back()->with('success', "User account {$user->name} has been {$action}.");
    }

    // Manage Subjects & Categories
    public function subjects()
    {
        $subjects = Subject::withCount('tutors')->get();
        return view('admin.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:subjects,name|max:255',
            'description' => 'nullable|string',
        ]);

        Subject::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Subject category created successfully!');
    }

    public function updateSubject(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'description' => 'nullable|string',
        ]);

        $subject->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Subject category updated successfully!');
    }

    public function destroySubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return back()->with('success', 'Subject category deleted successfully!');
    }

    // Monitor Bookings
    public function bookings(Request $request)
    {
        $query = Booking::with(['student.user', 'tutor.user', 'subject']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student.user', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhereHas('tutor.user', function($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%");
                })->orWhereHas('subject', function($subq) use ($search) {
                    $subq->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.bookings', compact('bookings'));
    }

    // Monitor Payments
    public function payments(Request $request)
    {
        $query = Payment::with(['user', 'booking.tutor.user', 'booking.subject']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.payments', compact('payments'));
    }

    // Manage & Remove Fake Reviews
    public function reviews()
    {
        $reviews = Review::with(['student.user', 'tutor.user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.reviews', compact('reviews'));
    }

    public function toggleReviewVisibility($id)
    {
        $review = Review::findOrFail($id);
        $review->is_visible = !$review->is_visible;
        $review->save();

        // Recalculate tutor rating
        $this->updateTutorRating($review->tutor_id);

        $status = $review->is_visible ? 'now visible.' : 'hidden (moderated).';
        return back()->with('success', "Review visibility updated: Review is {$status}");
    }

    public function destroyReview($id)
    {
        $review = Review::findOrFail($id);
        $tutorId = $review->tutor_id;
        $review->delete();

        // Recalculate tutor rating
        $this->updateTutorRating($tutorId);

        return back()->with('success', 'Review deleted permanently.');
    }

    protected function updateTutorRating($tutorId)
    {
        $tutor = Tutor::findOrFail($tutorId);
        $avgRating = Review::where('tutor_id', $tutorId)
            ->where('is_visible', true)
            ->avg('rating');

        $tutor->rating = $avgRating ? round($avgRating, 2) : 0.00;
        $tutor->save();
    }

    // CSV Exports
    public function exportUsersCsv()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=tutorconnect_users_report_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $users = User::all();

        $callback = function() use($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Phone', 'Created At']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->status,
                    $user->phone ?? 'N/A',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportBookingsCsv()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=tutorconnect_bookings_report_" . date('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $bookings = Booking::with(['student.user', 'tutor.user', 'subject'])->get();

        $callback = function() use($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Booking ID', 'Student Name', 'Tutor Name', 'Subject', 'Date', 'Time Slot', 'Price', 'Booking Status', 'Payment Status']);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->id,
                    $b->student->user->name,
                    $b->tutor->user->name,
                    $b->subject->name,
                    $b->date->format('Y-m-d'),
                    Carbon::parse($b->start_time)->format('H:i') . ' - ' . Carbon::parse($b->end_time)->format('H:i'),
                    $b->total_price,
                    $b->status,
                    $b->payment_status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
