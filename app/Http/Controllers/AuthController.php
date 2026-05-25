<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    // Process login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been suspended by the administrator.',
                ]);
            }

            // Check if OTP is still pending
            if ($user->otp_code && $user->email_verified_at === null) {
                return redirect()->route('otp.verify.form', ['userId' => $user->id])
                    ->with('info', 'Please complete OTP verification first.');
            }

            $request->session()->regenerate();

            return $this->redirectUser($user)->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show registration selector & form
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        $subjects = Subject::all();
        return view('auth.register', compact('subjects'));
    }

    // Process Student Registration
    public function registerStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'grade_level' => 'required|string',
            'learning_goals' => 'nullable|string',
        ]);

        // Generate OTP for bonus feature
        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'status' => 'active',
            'phone' => $request->phone,
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Student::create([
            'user_id' => $user->id,
            'grade_level' => $request->grade_level,
            'learning_goals' => $request->learning_goals,
        ]);

        return redirect()->route('otp.verify.form', ['userId' => $user->id])
            ->with('success', 'Registration successful! An OTP code has been generated.')
            ->with('otp_debug', $otp); // Pass to show on screen for easy sandbox testing
    }

    // Process Tutor Registration
    public function registerTutor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'title' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'experience' => 'required|integer|min:0',
            'qualification' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'subjects' => 'required|array|min:1',
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'tutor',
            'status' => 'active', // Active user record, but tutor profile starts unverified
            'phone' => $request->phone,
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $tutor = Tutor::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'hourly_rate' => $request->hourly_rate,
            'bio' => $request->bio,
            'experience' => $request->experience,
            'qualification' => $request->qualification,
            'is_verified' => false, // Requires admin verification
        ]);

        $tutor->subjects()->attach($request->subjects);

        return redirect()->route('otp.verify.form', ['userId' => $user->id])
            ->with('success', 'Registration successful! An OTP code has been generated.')
            ->with('otp_debug', $otp);
    }

    // Show OTP Verify Form
    public function showOtpForm($userId)
    {
        $user = User::findOrFail($userId);
        return view('auth.otp-verify', compact('user'));
    }

    // Process OTP verification
    public function verifyOtp(Request $request, $userId)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $user = User::findOrFail($userId);

        if ($user->otp_code === $request->otp_code && Carbon::now()->isBefore($user->otp_expires_at)) {
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->email_verified_at = Carbon::now();
            $user->save();

            Auth::login($user);

            return $this->redirectUser($user)->with('success', 'Email verified successfully! Welcome to TutorConnect.');
        }

        return back()->withErrors(['otp_code' => 'Invalid or expired OTP code. Please try again.']);
    }

    // Forgot password
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Mock sending password reset link
        return back()->with('success', 'A password reset link has been sent to your email address (simulated).');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }

    // Internal redirect helper
    protected function redirectUser($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTutor()) {
            return redirect()->route('tutor.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }
}
