<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            
        // Calculate attendance statistics
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $attendanceStats = [
            'todayCount' => Attendance::whereDate('date', $today)
                ->whereHas('student.section', function($query) use ($user) {
                    $query->where('school_id', $user->school_id);
                })->count(),
            'weeklyAttendance' => $this->getWeeklyAttendanceData($user->school_id),
            'attendanceRate' => $this->getAttendanceRate($user->school_id)
        ];
        
        // Calculate grade distribution
        $gradeDistribution = $this->getGradeDistribution($user->school_id);
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity($user->school_id);
        
        // Get teacher performance metrics
        $teacherPerformance = $this->getTeacherPerformanceMetrics($user->school_id);
        
        // Combine stats for the dashboard
        $stats = [
            'sectionsCount' => $sectionsCount,
            'subjectsCount' => $subjectsCount,
            'teachersCount' => $teachersCount,
            'studentsCount' => $studentsCount,
            'todayAttendance' => $attendanceStats['todayCount'],
        ];
        
        return view('teacher_admin.dashboard', compact(
            'user',
            'school',
            'sectionsCount',
            'subjectsCount',
            'teachersCount',
            'studentsCount',
            'recentSections',
            'recentSubjects',
            'availableTeachers',
            'attendanceStats',
            'gradeDistribution',
            'recentActivity',
            'teacherPerformance',
            'stats'
        ));
    }

    /**
     * Get weekly attendance data for charts
     */
    private function getWeeklyAttendanceData($schoolId)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $attendanceData = Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereHas('student.section', function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->select(DB::raw('DATE(date) as attendance_date'), DB::raw('count(*) as count'), 'status')
            ->groupBy('attendance_date', 'status')
            ->get();
            
        $formattedData = [];
        for ($day = 0; $day < 7; $day++) {
            $date = $startOfWeek->copy()->addDays($day)->format('Y-m-d');
            $present = $attendanceData->where('attendance_date', $date)->where('status', 'present')->first();
            $absent = $attendanceData->where('attendance_date', $date)->where('status', 'absent')->first();
            $late = $attendanceData->where('attendance_date', $date)->where('status', 'late')->first();
            
            $formattedData[] = [
                'date' => $startOfWeek->copy()->addDays($day)->format('D'),
                'present' => $present ? $present->count : 0,
                'absent' => $absent ? $absent->count : 0,
                'late' => $late ? $late->count : 0
            ];
        }
        
        return $formattedData;
    }
    
    /**
     * Get overall attendance rate
     */
    private function getAttendanceRate($schoolId)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        
        $totalAttendances = Attendance::whereDate('date', '>=', $startOfMonth)
            ->whereHas('student.section', function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })->count();
            
        $presentAttendances = Attendance::whereDate('date', '>=', $startOfMonth)
            ->whereHas('student.section', function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->where('status', 'present')
            ->count();
            
        return $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100) : 0;
    }
    
    /**
     * Get grade distribution for the school
     */
    private function getGradeDistribution($schoolId)
    {
        $grades = Grade::whereHas('student.section', function($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })->get();
        
        $distribution = [
            'excellent' => 0, // 90-100
            'veryGood' => 0,  // 85-89
            'good' => 0,      // 80-84
            'satisfactory' => 0, // 75-79
            'needsImprovement' => 0, // Below 75
        ];
        
        foreach ($grades as $grade) {
            $score = $grade->score;
            
            if ($score >= 90) {
                $distribution['excellent']++;
            } elseif ($score >= 85) {
                $distribution['veryGood']++;
            } elseif ($score >= 80) {
                $distribution['good']++;
            } elseif ($score >= 75) {
                $distribution['satisfactory']++;
            } else {
                $distribution['needsImprovement']++;
            }
        }
        
        return $distribution;
    }
    
    /**
     * Get recent activity for the school
     */
    private function getRecentActivity($schoolId)
    {
        // This would be more complex in a real application with a dedicated activity log
        // For now, we'll simulate with recent grades, attendances, etc.
        $recentGrades = Grade::whereHas('student.section', function($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })
        ->with(['student.section.subjects', 'subject', 'teacher'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get()
        ->map(function($grade) {
            // Try to find the correct teacher from the section_subject pivot
            $teacherName = Auth::user()->name; // Default fallback
            
            // Get the section_subject relationship that contains the correct teacher
            $sectionSubject = $grade->student->section->subjects
                ->where('id', $grade->subject_id)
                ->first();
                
            // If we found it, get the teacher name from the pivot
            if ($sectionSubject && isset($sectionSubject->pivot->teacher_id)) {
                $teacher = User::find($sectionSubject->pivot->teacher_id);
                if ($teacher) {
                    $teacherName = $teacher->name;
                }
            }
            
            return [
                'type' => 'grade',
                'description' => "Grade added for {$grade->student->name} in {$grade->subject->name}",
                'user' => $teacherName,
                'date' => $grade->created_at
            ];
        });
        
        $recentAttendance = Attendance::whereHas('student.section', function($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })
        ->with(['student', 'teacher'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get()
        ->map(function($attendance) {
            return [
                'type' => 'attendance',
                'description' => "Attendance marked for {$attendance->student->name} as {$attendance->status}",
                'user' => $attendance->teacher ? $attendance->teacher->name : Auth::user()->name,
                'date' => $attendance->created_at
            ];
        });
        
        return $recentGrades->concat($recentAttendance)->sortByDesc('date')->take(10)->values()->all();
    }
    
    /**
     * Get teacher performance metrics
     */
    private function getTeacherPerformanceMetrics($schoolId)
    {
        $teachers = User::where('school_id', $schoolId)
            ->where('role', 'teacher')
            ->withCount(['subjects'])
            ->orderBy('name')
            ->take(10)
            ->get();
            
        return $teachers->map(function($teacher) {
            // Since we don't have teacher_id in grades table, we'll use a placeholder value
            $averageGrade = 0;
            
            // Count attendance records by this teacher
            $attendanceCount = Attendance::where('teacher_id', $teacher->id)->count();
            
            // Count sections where teacher is adviser
            $sectionsCount = Section::where('adviser_id', $teacher->id)->count();
            
            return [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'subjectsCount' => $teacher->subjects_count,
                'sectionsCount' => $sectionsCount,
                'averageGrade' => $averageGrade,
                'attendanceCount' => $attendanceCount
            ];
        });
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

        // Update user directly in database
        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);

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

        // Update password directly in database
        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'password' => Hash::make($request->password)
            ]);

        return redirect()->route('teacher-admin.profile')->with('success', 'Password changed successfully.');
    }
}
