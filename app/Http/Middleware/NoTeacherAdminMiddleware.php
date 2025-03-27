<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoTeacherAdminMiddleware
{
    /**
     * Prevent teacher admins from accessing regular teacher routes.
     * This is the opposite of TeacherAdminMiddleware.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For now, allow all teachers including teacher admins to access these routes
        // This is a temporary fix until we decide which routes should be restricted
        return $next($request);
        
        /* Original logic - temporarily commented out
        if ($request->user() && $request->user()->is_teacher_admin) {
            // Redirect teacher admins to their dashboard
            return redirect()->route('teacher-admin.dashboard')
                ->with('error', 'You are a Teacher Admin. Please use the Teacher Admin panel for section and subject management.');
        }
        return $next($request);
        */
    }
}
