<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // View Profile Settings page
    public function edit()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    // Process Profile updates
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            
            // Password change (optional)
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',

            // Student fields
            'grade_level' => 'nullable|required_if:role,student|string',
            'learning_goals' => 'nullable|string',

            // Tutor fields
            'title' => 'nullable|required_if:role,tutor|string|max:255',
            'hourly_rate' => 'nullable|required_if:role,tutor|numeric|min:0',
            'experience' => 'nullable|required_if:role,tutor|integer|min:0',
            'qualification' => 'nullable|required_if:role,tutor|string|max:255',
        ]);

        // Verify password if changing it
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Handle Profile Picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $file = $request->file('profile_picture');
            $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_pictures', $fileName, 'public');
            $user->profile_picture = $path;
        }

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->bio = $request->bio;
        $user->save();

        // Update role-specific fields
        if ($user->isStudent()) {
            $user->student->update([
                'grade_level' => $request->grade_level,
                'learning_goals' => $request->learning_goals,
            ]);
        } elseif ($user->isTutor()) {
            $user->tutor->update([
                'title' => $request->title,
                'hourly_rate' => $request->hourly_rate,
                'experience' => $request->experience,
                'qualification' => $request->qualification,
            ]);
        }

        return back()->with('success', 'Profile settings updated successfully!');
    }
}
