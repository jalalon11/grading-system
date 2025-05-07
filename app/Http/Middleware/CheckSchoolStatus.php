<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Check maintenance mode using the SystemSetting model for consistency
        $isMaintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();

        // If user is admin, always allow access regardless of maintenance mode
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // If in maintenance mode and not admin, redirect to maintenance page
        if ($isMaintenanceMode && $user) {
            // Explicitly allowed routes
            $allowedRoutes = ['maintenance', 'maintenance/auth', 'logout', 'login'];
            $currentPath = $request->path();

            // Also allow admin routes
            if (strpos($currentPath, 'admin') === 0 || strpos($currentPath, 'admin/') === 0) {
                return $next($request);
            }

            if (!in_array($currentPath, $allowedRoutes)) {
                Log::info('Redirecting from CheckSchoolStatus middleware during maintenance', [
                    'user' => $user->email,
                    'role' => $user->role,
                    'path' => $request->path()
                ]);

                return redirect()->route('maintenance.auth');
            }
        }

        // Admin users are already handled above

        // For teachers, check if their school is active
        if ($user && $user->role === 'teacher' && $user->school_id) {
            $school = $user->school;

            // If the school is inactive or subscription is expired, handle accordingly
            if (!$school || !$school->is_active || $school->subscription_status === 'expired') {
                // If subscription is expired and user is teacher admin, allow access to payment page only
                if ($school && $school->subscription_status === 'expired' && $user->is_teacher_admin) {
                    // Update school status to inactive
                    if ($school->is_active) {
                        $school->is_active = false;
                        $school->save();
                    }

                    // Allow access to payment pages
                    if ($request->routeIs('teacher-admin.payments.*')) {
                        return $next($request);
                    }

                    // Redirect to payment page
                    return redirect()->route('teacher-admin.payments.index')
                        ->with('error', 'Your school subscription has expired. Please make a payment to continue using the system.');
                }

                // For regular teachers or other cases, log out
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your school account has been disabled. Please contact the administrator.');
            }

            // Check if trial has expired
            if ($school->trialExpired() && !$school->hasActiveSubscription()) {
                // Allow teacher admins to access payment page
                if ($user->is_teacher_admin && $request->routeIs('teacher-admin.payments.*')) {
                    return $next($request);
                }

                // For teacher admins, redirect to payment page
                if ($user->is_teacher_admin) {
                    return redirect()->route('teacher-admin.payments.index')
                        ->with('error', 'Your school trial period has expired. Please make a payment to continue using the system.');
                }

                // For regular teachers, log out
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your school trial period has expired. Please contact your school administrator.');
            }

            // Check if subscription has expired but status is not yet updated
            if ($school->subscriptionExpired() && $school->subscription_status !== 'expired') {
                // Update school status to expired
                $school->subscription_status = 'expired';
                $school->is_active = false;
                $school->save();

                // Allow teacher admins to access payment page
                if ($user->is_teacher_admin && $request->routeIs('teacher-admin.payments.*')) {
                    return $next($request);
                }

                // For teacher admins, redirect to payment page
                if ($user->is_teacher_admin) {
                    return redirect()->route('teacher-admin.payments.index')
                        ->with('error', 'Your school subscription has expired. Please make a payment to continue using the system.');
                }

                // For regular teachers, log out
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your school subscription has expired. Please contact your school administrator.');
            }
        }

        return $next($request);
    }
}