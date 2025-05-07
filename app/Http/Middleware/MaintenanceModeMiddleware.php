<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // IMPORTANT: Check if user is admin first - always allow admin access
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Check if the route is for admin dashboard or admin-related routes
        if (strpos($request->path(), 'admin') === 0 || strpos($request->path(), 'admin/') === 0) {
            return $next($request);
        }

        // Force check maintenance mode directly from database for reliability
        // Use the SystemSetting model to ensure consistent checking
        $isMaintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();

        // Log the maintenance mode status for debugging
        Log::info('Maintenance mode check', [
            'is_maintenance_mode' => $isMaintenanceMode,
            'raw_value' => \App\Models\SystemSetting::getSetting('maintenance_mode', 'not set'),
            'path' => $request->path(),
            'user' => Auth::check() ? Auth::user()->email : 'guest'
        ]);

        if ($isMaintenanceMode) {

            // Explicitly allowed routes that should always be accessible
            $allowedRoutes = [
                'maintenance',
                'maintenance/auth',
                'logout',
                'login'
            ];

            // Check if the current route is in the allowed routes
            $currentPath = $request->path();
            $isAllowedRoute = false;

            foreach ($allowedRoutes as $route) {
                if ($currentPath === $route || ($route === 'login' && $currentPath === 'login' && $request->isMethod('get'))) {
                    $isAllowedRoute = true;
                    break;
                }
            }

            // If it's an allowed route, proceed
            if ($isAllowedRoute) {
                // For login POST, only allow admin login
                if ($currentPath === 'login' && $request->isMethod('post')) {
                    $email = $request->input('email');
                    $user = \App\Models\User::where('email', $email)->first();

                    if ($user && $user->role === 'admin') {
                        return $next($request);
                    }

                    return redirect()->route('maintenance')
                        ->with('error', 'System is under maintenance. Only administrators can log in at this time.');
                }

                return $next($request);
            }

            // For authenticated non-admin users, redirect to authenticated maintenance page
            if (Auth::check()) {
                Log::info('Redirecting authenticated user to maintenance page', [
                    'user' => Auth::user()->email,
                    'role' => Auth::user()->role,
                    'path' => $request->path()
                ]);

                return redirect()->route('maintenance.auth');
            }

            // For non-authenticated users, redirect to public maintenance page
            Log::info('Redirecting guest to maintenance page', [
                'path' => $request->path()
            ]);

            return redirect()->route('maintenance');
        }

        return $next($request);
    }
}
