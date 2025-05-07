<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Show the maintenance page for non-authenticated users
     */
    public function index()
    {
        $maintenanceMessage = SystemSetting::getMaintenanceMessage();
        $announcements = Announcement::where('is_active', true)->latest()->get();

        return view('maintenance', compact('maintenanceMessage', 'announcements'));
    }

    /**
     * Show the maintenance page for authenticated users
     */
    public function authenticatedIndex()
    {
        $maintenanceMessage = SystemSetting::getMaintenanceMessage();
        $announcements = Announcement::where('is_active', true)->latest()->get();

        return view('maintenance_auth', compact('maintenanceMessage', 'announcements'));
    }

    /**
     * Toggle maintenance mode (admin only)
     */
    public function toggleMaintenanceMode(Request $request)
    {
        // Ensure only admins can toggle maintenance mode
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        $currentStatus = SystemSetting::isMaintenanceMode();
        $newStatus = !$currentStatus;

        // Update maintenance mode status - explicitly use 1 or 0 for clarity
        SystemSetting::setSetting(
            'maintenance_mode',
            $newStatus ? 1 : 0,
            'Controls whether the system is in maintenance mode'
        );

        // Update maintenance message if provided
        if ($request->has('maintenance_message') && !empty($request->maintenance_message)) {
            SystemSetting::setSetting(
                'maintenance_message',
                $request->maintenance_message,
                'Message displayed during maintenance mode'
            );
        }

        // If enabling maintenance mode and duration is provided
        if ($newStatus && $request->has('maintenance_duration') && is_numeric($request->maintenance_duration)) {
            $duration = intval($request->maintenance_duration);
            if ($duration > 0) {
                // Store the duration for later calculations
                SystemSetting::setSetting(
                    'maintenance_duration',
                    $duration,
                    'Duration of maintenance in minutes'
                );

                // Calculate end time based on duration (in minutes)
                $endTime = now()->addMinutes($duration)->toIso8601String();

                SystemSetting::setSetting(
                    'maintenance_end_time',
                    $endTime,
                    'Expected end time for maintenance mode'
                );
            }
        } else if (!$newStatus) {
            // If disabling maintenance mode, clear the end time and duration
            SystemSetting::setSetting(
                'maintenance_end_time',
                null,
                'Expected end time for maintenance mode'
            );

            SystemSetting::setSetting(
                'maintenance_duration',
                null,
                'Duration of maintenance in minutes'
            );
        }

        $statusText = $newStatus ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Maintenance mode {$statusText} successfully.");
    }

    /**
     * Check maintenance mode status (admin only)
     */
    public function checkStatus()
    {
        // Ensure only admins can check maintenance mode status
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $status = SystemSetting::isMaintenanceMode() ? 'enabled' : 'disabled';
        $value = SystemSetting::getSetting('maintenance_mode', 'not set');

        return response()->json([
            'maintenance_mode' => $status,
            'raw_value' => $value,
            'type' => gettype($value)
        ]);
    }

    /**
     * Check maintenance mode status for AJAX requests (any authenticated user)
     */
    public function checkStatusAjax()
    {
        // This endpoint is accessible to any authenticated user
        $isMaintenanceMode = SystemSetting::isMaintenanceMode();

        return response()->json([
            'maintenance_mode' => $isMaintenanceMode,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Get maintenance progress information for AJAX requests
     */
    public function getMaintenanceProgress()
    {
        $endTime = SystemSetting::getMaintenanceEndTime();
        $duration = SystemSetting::getMaintenanceDuration();
        $isPastEndTime = SystemSetting::isMaintenancePastEndTime();
        $remainingMinutes = SystemSetting::getMaintenanceRemainingMinutes();

        $progressPercent = 65; // Default value
        $completionMessage = 'Please check back in a few minutes';

        if ($endTime && $duration) {
            try {
                $endDateTime = new \DateTime($endTime);
                $startTime = clone $endDateTime;
                $startTime->modify('-' . $duration . ' minutes');
                $now = new \DateTime();

                if ($now > $endDateTime) {
                    $progressPercent = 100;
                    $completionMessage = 'System will be completed any moment';
                } else {
                    $totalDuration = $endDateTime->getTimestamp() - $startTime->getTimestamp();
                    $elapsedDuration = $now->getTimestamp() - $startTime->getTimestamp();
                    $progressPercent = min(99, max(1, round(($elapsedDuration / $totalDuration) * 100)));

                    if ($remainingMinutes !== null) {
                        if ($remainingMinutes > 60) {
                            $completionMessage = 'Approximately ' . floor($remainingMinutes / 60) . ' hour(s) and ' . ($remainingMinutes % 60) . ' minute(s)';
                        } else {
                            $completionMessage = 'Approximately ' . $remainingMinutes . ' minute(s)';
                        }
                    }
                }
            } catch (\Exception $e) {
                // Keep default values
            }
        }

        return response()->json([
            'progress_percent' => $progressPercent,
            'completion_message' => $completionMessage,
            'is_past_end_time' => $isPastEndTime,
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
