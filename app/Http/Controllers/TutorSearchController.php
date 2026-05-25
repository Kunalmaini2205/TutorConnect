<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Subject;
use Illuminate\Http\Request;

class TutorSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Tutor::query()->whereHas('user', function($q) {
            $q->where('status', 'active');
        })->where('is_verified', true);

        // Subject filter
        if ($request->filled('subject')) {
            $query->whereHas('subjects', function ($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }

        // Search text
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('hourly_rate', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('hourly_rate', '<=', $request->max_price);
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        // Sorting
        $sort = $request->get('sort', 'rating_desc');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('hourly_rate', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('hourly_rate', 'desc');
                break;
            case 'experience_desc':
                $query->orderBy('experience', 'desc');
                break;
            case 'rating_desc':
            default:
                $query->orderBy('rating', 'desc');
                break;
        }

        $tutors = $query->with('user', 'subjects')->paginate(6);
        $subjects = Subject::all();

        if ($request->ajax()) {
            return view('tutors.partials.tutor_grid', compact('tutors'))->render();
        }

        return view('tutors.index', compact('tutors', 'subjects'));
    }

    public function show($id)
    {
        $tutor = Tutor::with(['user', 'subjects', 'availabilitySlots' => function($q) {
            $q->where('is_booked', false)->where('date', '>=', now()->toDateString())->orderBy('date')->orderBy('start_time');
        }, 'reviews.student.user'])->findOrFail($id);

        return view('tutors.show', compact('tutor'));
    }
}
