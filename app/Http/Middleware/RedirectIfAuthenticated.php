<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        // Check maintenance mode
        $maintenanceMode = DB::table('system_settings')->where('key', 'maintenance_mode')->first();
        $isMaintenanceMode = $maintenanceMode && ($maintenanceMode->value == '1' || $maintenanceMode->value == 1);

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // If in maintenance mode and not admin, redirect to maintenance page
                if ($isMaintenanceMode && $user->role !== 'admin') {
                    return redirect()->route('maintenance.auth');
                }

                // Normal redirects
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                } else {
                    return redirect('/home');
                }
            }
        }

        return $next($request);
    }
}
