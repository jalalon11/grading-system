<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TeacherAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is a teacher admin
        if (!$user || !$user->is_teacher_admin) {
            abort(403, 'Unauthorized. This area is restricted to Teacher Admins only.');
        }

        // Check if school subscription is expired
        if ($user->school && $user->school->subscription_status === 'expired') {
            // Only allow access to payment pages
            if (!$request->routeIs('teacher-admin.payments.*')) {
                return redirect()->route('teacher-admin.payments.index')
                    ->with('error', 'Your school subscription has expired. Please make a payment to continue using the system.');
            }
        }

        return $next($request);
    }
}
