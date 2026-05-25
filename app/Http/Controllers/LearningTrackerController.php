<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\LearningProgress;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\UploadedMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LearningTrackerController extends Controller
{
    // Tutor: Store student progress log
    public function storeProgress(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'notes' => 'required|string',
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->tutor_id !== Auth::user()->tutor->id) {
            return back()->with('error', 'Unauthorized action.');
        }

        LearningProgress::create([
            'student_id' => $booking->student_id,
            'tutor_id' => $booking->tutor_id,
            'subject_id' => $booking->subject_id,
            'notes' => $request->notes,
            'progress_percentage' => $request->progress_percentage,
            'recorded_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Student progress and notes logged successfully!');
    }

    // Student & Tutor: View progress records
    public function progressHistory()
    {
        $user = Auth::user();
        
        if ($user->isStudent()) {
            $progressLogs = LearningProgress::where('student_id', $user->student->id)
                ->with('tutor.user', 'subject')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $materials = UploadedMaterial::whereHas('tutor.favoritedBy', function($q) use ($user) {
                $q->where('students.id', $user->student->id);
            })->orWhereHas('tutor.bookings', function($q) use ($user) {
                $q->where('bookings.student_id', $user->student->id);
            })->with('tutor.user')->distinct()->get();

            return view('learning.student_tracker', compact('progressLogs', 'materials'));
        } else {
            $tutor = $user->tutor;
            $progressLogs = LearningProgress::where('tutor_id', $tutor->id)
                ->with('student.user', 'subject')
                ->orderBy('created_at', 'desc')
                ->get();

            // Fetch students taught by this tutor
            $students = Student::whereHas('bookings', function($q) use ($tutor) {
                $q->where('tutor_id', $tutor->id);
            })->with('user')->get();

            $subjects = $tutor->subjects;

            return view('learning.tutor_tracker', compact('progressLogs', 'students', 'subjects'));
        }
    }

    // Tutor: Upload material
    public function storeMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material_file' => 'required|file|max:10240', // Max 10MB
        ]);

        $tutor = Auth::user()->tutor;
        $file = $request->file('material_file');

        // Store file physically in local public folder for simplicity and easy link access
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('materials', $fileName, 'public');

        UploadedMaterial::create([
            'tutor_id' => $tutor->id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'downloads' => 0,
        ]);

        return back()->with('success', 'Study material uploaded successfully!');
    }

    // Student: Download material
    public function downloadMaterial($id)
    {
        $material = UploadedMaterial::findOrFail($id);
        $material->increment('downloads');

        $exists = Storage::disk('public')->exists($material->file_path);

        if (!$exists) {
            // Return a dummy mock file content so downloads do not crash if demo files are used
            return response($material->title . " study notes content.\nUploaded by Tutor Connect.", 200, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="' . Str::slug($material->title) . '.txt"',
            ]);
        }

        return Storage::disk('public')->download($material->file_path);
    }

    // Student: Download Completion Certificate
    public function downloadCertificate($bookingId)
    {
        $booking = Booking::with(['student.user', 'tutor.user', 'subject'])->findOrFail($bookingId);

        if ($booking->student_id !== Auth::user()->student->id) {
            abort(403, 'Unauthorized.');
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'Certificates are only generated for completed sessions.');
        }

        // Render PDF of certificate
        $pdf = Pdf::loadView('learning.certificate', compact('booking'))->setPaper('a4', 'landscape');
        
        return $pdf->download('tutorconnect_certificate_' . $booking->id . '.pdf');
    }
}
