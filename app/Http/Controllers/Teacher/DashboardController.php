<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        try {
            // Get sections where the user is the adviser
            $advisedSections = Section::where('adviser_id', $user->id)->get();
            
            // Get all subjects taught by the user (through the pivot table)
            $taughtSubjects = Subject::whereHas('teachers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })->get();
            
            // Get students in the advised sections
            $studentsInSections = Student::whereIn('section_id', $advisedSections->pluck('id'))->get();
            
            $stats = [
                'sectionsCount' => $advisedSections->count(),
                'subjectsCount' => $taughtSubjects->count(),
                'studentsCount' => $studentsInSections->count(),
                'todayAttendance' => Attendance::where('teacher_id', $user->id)
                                            ->whereDate('date', now()->toDateString())
                                            ->count()
            ];
            
            $recentSections = Section::where('adviser_id', $user->id)
                                ->latest()
                                ->take(5)
                                ->get();
                                
            // Get recent subjects using the teachers relationship
            $recentSubjects = Subject::whereHas('teachers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })->latest()
              ->take(5)
              ->get();
            
            return view('teacher.dashboard', compact('stats', 'recentSections', 'recentSubjects'));
        } catch (\Exception $e) {
            Log::error('Error in teacher dashboard: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback with empty data
            $stats = [
                'sectionsCount' => 0,
                'subjectsCount' => 0,
                'studentsCount' => 0,
                'todayAttendance' => 0
            ];
            
            $recentSections = collect();
            $recentSubjects = collect();
            
            return view('teacher.dashboard', compact('stats', 'recentSections', 'recentSubjects'))
                ->with('error', 'There was an error loading your dashboard. Please contact the administrator.');
        }
    }

    /**
     * Update the teacher profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('teacher.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the teacher password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('teacher.profile')->with('success', 'Password changed successfully.');
    }
}
