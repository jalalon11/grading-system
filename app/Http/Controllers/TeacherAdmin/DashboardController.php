<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $school = $user->school;
        
        // Get counts for the dashboard
        $sectionsCount = Section::where('school_id', $user->school_id)->count();
        $subjectsCount = Subject::where('school_id', $user->school_id)->count();
        $teachersCount = User::where('school_id', $user->school_id)
            ->where('role', 'teacher')
            ->count();
        $studentsCount = Student::whereHas('section', function($query) use ($user) {
            $query->where('school_id', $user->school_id);
        })->count();
        
        // Get the most recent sections and subjects
        $recentSections = Section::where('school_id', $user->school_id)
            ->with(['adviser', 'subjects'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $recentSubjects = Subject::where('school_id', $user->school_id)
            ->withCount('sections')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get teachers to assign
        $availableTeachers = User::where('school_id', $user->school_id)
            ->where('role', 'teacher')
            ->orderBy('name')
            ->get();
        
        return view('teacher_admin.dashboard', compact(
            'user',
            'school',
            'sectionsCount',
            'subjectsCount',
            'teachersCount',
            'studentsCount',
            'recentSections',
            'recentSubjects',
            'availableTeachers'
        ));
    }

    /**
     * Update the teacher admin profile
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

        return redirect()->route('teacher-admin.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the teacher admin password
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

        return redirect()->route('teacher-admin.profile')->with('success', 'Password changed successfully.');
    }
}
