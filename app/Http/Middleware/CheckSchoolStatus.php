<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSchoolStatus
{
    /**
     * Handle an incoming request.
     * Check if the user's associated school is active.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Skip this check for admin users as they need to be able to enable/disable schools
        if ($user && $user->role === 'admin') {
            return $next($request);
        }
        
        // For teachers, check if their school is active
        if ($user && $user->role === 'teacher' && $user->school_id) {
            $school = $user->school;
            
            // If the school is inactive, log the user out
            if (!$school || !$school->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('error', 'Your school account has been disabled. Please contact the administrator.');
            }
        }
        
        return $next($request);
    }
} 