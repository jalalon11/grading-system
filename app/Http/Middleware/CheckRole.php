<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check maintenance mode using the SystemSetting model for consistency
        $isMaintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();

        // If user is admin or the requested role is admin, always allow access
        if ($request->user() && ($request->user()->role === 'admin' || $role === 'admin')) {
            return $next($request);
        }

        // If in maintenance mode and not admin, redirect to maintenance page
        if ($isMaintenanceMode && $request->user()) {
            // Explicitly allowed routes
            $allowedRoutes = ['maintenance', 'maintenance/auth', 'logout', 'login'];
            $currentPath = $request->path();

            // Also allow admin routes
            if (strpos($currentPath, 'admin') === 0 || strpos($currentPath, 'admin/') === 0) {
                return $next($request);
            }

            if (!in_array($currentPath, $allowedRoutes)) {
                Log::info('Redirecting from CheckRole middleware during maintenance', [
                    'user' => $request->user()->email,
                    'role' => $request->user()->role,
                    'path' => $request->path(),
                    'requested_role' => $role
                ]);

                return redirect()->route('maintenance.auth');
            }
        }

        // Normal role check
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
