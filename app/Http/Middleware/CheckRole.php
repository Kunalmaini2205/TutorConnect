<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        // Check if user status is active
        if ($user->status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended by the administrator.');
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on their role if they don't have access
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
            case 'tutor':
                return redirect()->route('tutor.dashboard')->with('error', 'Unauthorized access.');
            default:
                return redirect()->route('student.dashboard')->with('error', 'Unauthorized access.');
        }
    }
}
