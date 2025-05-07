<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

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

        // If user is admin, always allow access
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // Check if user is a teacher admin
        if (!$user || !$user->is_teacher_admin) {
            abort(403, 'Unauthorized. This area is restricted to Teacher Admins only.');
        }

        // Check maintenance mode using the SystemSetting model for consistency
        $isMaintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();

        // If in maintenance mode, redirect to maintenance page
        if ($isMaintenanceMode) {
            // Explicitly allowed routes
            $allowedRoutes = ['maintenance', 'maintenance/auth', 'logout', 'login'];
            $currentPath = $request->path();

            // Also allow admin routes
            if (strpos($currentPath, 'admin') === 0 || strpos($currentPath, 'admin/') === 0) {
                return $next($request);
            }

            if (!in_array($currentPath, $allowedRoutes)) {
                Log::info('Redirecting from TeacherAdminMiddleware during maintenance', [
                    'user' => $user->email,
                    'path' => $request->path()
                ]);

                return redirect()->route('maintenance.auth');
            }
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
