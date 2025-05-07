<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check maintenance mode using the SystemSetting model for consistency
        $isMaintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();

        // Log authentication attempt during maintenance
        if ($isMaintenanceMode) {
            Log::info('Authentication middleware during maintenance', [
                'path' => $request->path(),
                'is_authenticated' => Auth::check(),
                'user' => Auth::check() ? Auth::user()->email : 'guest'
            ]);
        }

        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // If user is admin, always allow access
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // If in maintenance mode and not admin, redirect to maintenance page
        if ($isMaintenanceMode) {
            // Explicitly allowed routes
            $allowedRoutes = ['maintenance', 'maintenance/auth', 'logout', 'login'];
            $currentPath = $request->path();

            // Also allow admin routes
            if (strpos($currentPath, 'admin') === 0 || strpos($currentPath, 'admin/') === 0) {
                return $next($request);
            }

            if (!in_array($currentPath, $allowedRoutes)) {
                Log::info('Redirecting authenticated user to maintenance page from Authenticate middleware', [
                    'user' => Auth::user()->email,
                    'role' => Auth::user()->role,
                    'path' => $request->path()
                ]);

                return redirect()->route('maintenance.auth');
            }
        }

        return $next($request);
    }
}
