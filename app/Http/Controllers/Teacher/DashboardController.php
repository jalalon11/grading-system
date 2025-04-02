<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $sectionIds = $advisedSections->pluck('id')->toArray();
            
            // Get sections where user teaches subjects
            $taughtSectionIds = DB::table('section_subject')
                ->where('teacher_id', $user->id)
                ->pluck('section_id')
                ->unique()
                ->toArray();
                
            // Combine all sections for stats calculation
            $allSectionIds = array_unique(array_merge($sectionIds, $taughtSectionIds));
                
            // Get only the sections where user is adviser for the dashboard dropdown
            $recentSections = Section::where('adviser_id', $user->id)
                ->with('students') // Eager load students
                ->latest()
                ->get();
            
            // Get all subjects taught by the user (through the pivot table)
            $taughtSubjects = Subject::whereHas('teachers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })->get();
            
            // Get students in the advised sections
            $studentsInSections = Student::whereIn('section_id', $allSectionIds)->get();
            $studentIds = $studentsInSections->pluck('id')->toArray();
            
            $stats = [
                'sectionsCount' => count($allSectionIds),
                'subjectsCount' => $taughtSubjects->count(),
                'studentsCount' => $studentsInSections->count(),
                'todayAttendance' => Attendance::where(function($query) use ($user, $allSectionIds) {
                                            $query->where('teacher_id', $user->id)
                                                  ->orWhereIn('section_id', $allSectionIds);
                                        })
                                        ->whereDate('date', now()->toDateString())
                                        ->count()
            ];
            
            // Get recent subjects using the teachers relationship with eager loading
            $recentSubjects = Subject::whereHas('teachers', function($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->with(['teachers', 'sections' => function($query) {
                $query->select('sections.id', 'sections.name', 'sections.grade_level');
            }])
            ->latest()
            ->get(); // Remove the limit to get all assigned subjects
            
            // Get last 7 days attendance data for trends
            $last7Days = collect();
            $attendanceTrends = collect();
            
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $last7Days->push([
                    'date' => now()->subDays($i)->format('D'),
                    'full_date' => $date
                ]);
                
                // Get attendance records for this date (check only for sections where teacher is adviser)
                $attendanceRecords = Attendance::whereIn('section_id', $sectionIds)
                    ->whereDate('date', $date)
                    ->get();
                    
                if ($attendanceRecords->count() > 0) {
                    // Aggregate attendance data from all records
                    $presentCount = 0;
                    $lateCount = 0;
                    $absentCount = 0;
                    $excusedCount = 0;
                    $totalStudents = 0;
                    
                    foreach ($attendanceRecords as $record) {
                        // For new attendance structure with attendance_data
                        if ($record->attendance_data) {
                            $attendanceData = is_array($record->attendance_data) ? $record->attendance_data : json_decode($record->attendance_data, true) ?? [];
                            
                            foreach ($attendanceData as $data) {
                                $totalStudents++;
                                if (isset($data['status'])) {
                                    if ($data['status'] === 'present') {
                                        $presentCount++;
                                    } elseif ($data['status'] === 'late') {
                                        $lateCount++;
                                    } elseif ($data['status'] === 'absent') {
                                        $absentCount++;
                                    } elseif ($data['status'] === 'excused') {
                                        $excusedCount++;
                                    }
                                }
                            }
                        } 
                        // For legacy attendance structure with individual records
                        else if ($record->status) {
                            $totalStudents++;
                            if ($record->status === 'present') {
                                $presentCount++;
                            } elseif ($record->status === 'late') {
                                $lateCount++;
                            } elseif ($record->status === 'absent') {
                                $absentCount++;
                            } elseif ($record->status === 'excused') {
                                $excusedCount++;
                            }
                        }
                    }
                    
                    // Calculate percentages ensuring we don't divide by zero
                    $attendanceTrends->push([
                        'date' => now()->subDays($i)->format('D'),
                        'present' => $presentCount,
                        'late' => $lateCount,
                        'absent' => $absentCount,
                        'excused' => $excusedCount,
                        'total' => $totalStudents
                    ]);
                } else {
                    $attendanceTrends->push([
                        'date' => now()->subDays($i)->format('D'),
                        'present' => 0,
                        'late' => 0,
                        'absent' => 0,
                        'excused' => 0,
                        'total' => 0
                    ]);
                }
            }
            
            // Get grades distribution for taught subjects
            $gradeDistribution = [
                'A' => 0, // 90-100
                'B' => 0, // 80-89
                'C' => 0, // 70-79
                'D' => 0, // 60-69
                'F' => 0  // Below 60
            ];
            
            // Fetch all grades for students in advised sections with better query
            $grades = DB::table('grades')
                ->join('students', 'grades.student_id', '=', 'students.id')
                ->whereIn('students.section_id', $allSectionIds)
                ->whereIn('grades.subject_id', $taughtSubjects->pluck('id')->toArray())
                ->select('grades.score')
                ->get();
            
            $gradesCount = $grades->count();
            
            // Process grades for distribution
            foreach ($grades as $grade) {
                $score = $grade->score;
                
                if ($score >= 90) {
                    $gradeDistribution['A']++;
                } elseif ($score >= 80) {
                    $gradeDistribution['B']++;
                } elseif ($score >= 70) {
                    $gradeDistribution['C']++;
                } elseif ($score >= 60) {
                    $gradeDistribution['D']++;
                } else {
                    $gradeDistribution['F']++;
                }
            }
            
            // Calculate percentages for visualization
            $gradeDistributionPercentage = [];
            foreach ($gradeDistribution as $grade => $count) {
                $gradeDistributionPercentage[$grade] = $gradesCount > 0 ? 
                    round(($count / $gradesCount) * 100) : 0;
            }
            
            // Get top performing students using our new method
            $topStudents = $this->getPerformanceData(new Request(['section_id' => $recentSections->first()->id ?? null]));
            
            return view('teacher.dashboard', compact(
                'stats', 
                'recentSections', 
                'recentSubjects', 
                'attendanceTrends', 
                'gradeDistributionPercentage', 
                'topStudents',
                'last7Days'
            ));
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
            $attendanceTrends = collect();
            $gradeDistributionPercentage = [
                'A' => 0,
                'B' => 0,
                'C' => 0,
                'D' => 0,
                'F' => 0
            ];
            $topStudents = collect();
            $last7Days = collect();
            
            return view('teacher.dashboard', compact(
                'stats', 
                'recentSections', 
                'recentSubjects', 
                'attendanceTrends', 
                'gradeDistributionPercentage', 
                'topStudents',
                'last7Days'
            ))
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

        $userId = Auth::id();
        
        // Use DB facade to update user record
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);

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

        // Use DB facade to update user password
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        return redirect()->route('teacher.profile')->with('success', 'Password changed successfully.');
    }

    /**
     * Helper function to calculate performance rating based on grades and attendance
     */
    private function calculatePerformanceRating($avgGrade, $attendanceRate)
    {
        // Handle null values
        $avgGrade = $avgGrade ?? 0;
        $attendanceRate = $attendanceRate ?? 0;
        
        // Formula: 60% grade + 40% attendance
        return round(($avgGrade * 0.6) + ($attendanceRate * 0.4));
    }

    /**
     * Get attendance data for different periods (AJAX endpoint)
     */
    public function getAttendanceData(Request $request)
    {
        $sectionId = $request->input('section_id');
        $period = $request->input('period', 'week');
        $user = Auth::user();
        
        // Default data structure for all periods
        $data = [
            'labels' => [],
            'present' => [],
            'late' => [],
            'absent' => [],
            'excused' => [],
            'total' => []
        ];
        
        try {
            // Determine which sections to include
            $queryIds = [];
            
            if ($sectionId) {
                // Check if section belongs to this teacher
                $section = Section::where('id', $sectionId)
                    ->where(function($query) use ($user) {
                        $query->where('adviser_id', $user->id)
                            ->orWhereHas('subjects.teachers', function($q) use ($user) {
                                $q->where('users.id', $user->id);
                            });
                    })
                    ->first();
                
                if ($section) {
                    $queryIds = [$section->id];
                } else {
                    return response()->json(['error' => 'Section not found or not authorized'], 404);
                }
            } else {
                // Get only sections where user is adviser (not sections where they just teach)
                $queryIds = Section::where('adviser_id', $user->id)->pluck('id')->toArray();
            }
            
            // Get attendance data based on period type
            if ($period === 'week') {
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i)->toDateString();
                    $data['labels'][] = now()->subDays($i)->format('D');
                    
                    // Get records for this date and selected sections
                    $records = Attendance::where(function($query) use ($user, $queryIds) {
                            $query->where('teacher_id', $user->id)
                                  ->orWhereIn('section_id', $queryIds);
                        })
                        ->whereDate('date', $date)
                        ->get();
                    
                    // Process attendance data
                    $this->processAttendanceRecords($records, $data);
                }
            } elseif ($period === 'month') {
                // Last 4 weeks
                for ($i = 4; $i >= 1; $i--) {
                    $weekStart = now()->subWeeks($i)->startOfWeek();
                    $weekEnd = now()->subWeeks($i)->endOfWeek();
                    
                    $data['labels'][] = 'Week ' . (5-$i);
                    
                    // Get records for this week
                    $records = Attendance::where(function($query) use ($user, $queryIds) {
                            $query->where('teacher_id', $user->id)
                                  ->orWhereIn('section_id', $queryIds);
                        })
                        ->whereBetween('date', [$weekStart, $weekEnd])
                        ->get();
                    
                    // Process attendance data
                    $this->processAttendanceRecords($records, $data);
                }
            } elseif ($period === 'semester') {
                // Last 5 months
                for ($i = 4; $i >= 0; $i--) {
                    $monthStart = now()->subMonths($i)->startOfMonth();
                    $monthEnd = now()->subMonths($i)->endOfMonth();
                    
                    $data['labels'][] = $monthStart->format('M');
                    
                    // Get records for this month
                    $records = Attendance::where(function($query) use ($user, $queryIds) {
                            $query->where('teacher_id', $user->id)
                                  ->orWhereIn('section_id', $queryIds);
                        })
                        ->whereBetween('date', [$monthStart, $monthEnd])
                        ->get();
                    
                    // Process attendance data
                    $this->processAttendanceRecords($records, $data);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error getting attendance data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error fetching attendance data'], 500);
        }
        
        return response()->json($data);
    }
    
    /**
     * Helper method to process attendance records and calculate percentages
     */
    private function processAttendanceRecords($records, &$data)
    {
        $presentCount = 0;
        $lateCount = 0;
        $absentCount = 0;
        $excusedCount = 0;
        $totalStudents = 0;
        
        foreach ($records as $record) {
            // For new attendance structure with attendance_data
            if ($record->attendance_data) {
                $attendanceRecords = is_array($record->attendance_data) ? 
                    $record->attendance_data : json_decode($record->attendance_data, true) ?? [];
                
                foreach ($attendanceRecords as $attendance) {
                    $totalStudents++;
                    if (isset($attendance['status'])) {
                        if ($attendance['status'] === 'present') {
                            $presentCount++;
                        } elseif ($attendance['status'] === 'late') {
                            $lateCount++;
                        } elseif ($attendance['status'] === 'absent') {
                            $absentCount++;
                        } elseif ($attendance['status'] === 'excused') {
                            $excusedCount++;
                        }
                    }
                }
            }
            // For legacy attendance structure
            else if ($record->status) {
                $totalStudents++;
                if ($record->status === 'present') {
                    $presentCount++;
                } elseif ($record->status === 'late') {
                    $lateCount++;
                } elseif ($record->status === 'absent') {
                    $absentCount++;
                } elseif ($record->status === 'excused') {
                    $excusedCount++;
                }
            }
        }
        
        // Use actual counts instead of percentages
        $data['present'][] = $presentCount;
        $data['late'][] = $lateCount;
        $data['absent'][] = $absentCount;
        $data['excused'][] = $excusedCount;
        $data['total'][] = $totalStudents;
    }

    /**
     * Get teacher performance metrics
     */
    public function getPerformanceData(Request $request)
    {
        $user = Auth::user();
        $sectionId = $request->input('section_id');
        $debug = [];
        
        try {
            // Variable to store the selected section
            $section = null;
            
            if ($sectionId) {
                // Find section by ID where user is the adviser only
                $section = Section::where('id', $sectionId)
                    ->where('adviser_id', $user->id)
                    ->first();
                    
                $debug['requested_section_id'] = $sectionId;
                $debug['section_found'] = !is_null($section);
            }
            
            // If no section specified/found, get first available section where user is adviser
            if (!$section) {
                // Get a section where user is the adviser
                $section = Section::where('adviser_id', $user->id)->first();
                
                // If no section is found where user is adviser, return empty response
                if (!$section) {
                    return $request->expectsJson() 
                        ? response()->json([
                            'students' => [],
                            'section' => null,
                            'error' => 'No sections available where you are adviser',
                            'debug' => $debug
                        ]) 
                        : collect();
                }
                
                $debug['fallback_section_id'] = $section->id;
            }
            
            // Get all subjects assigned to this section
            $sectionSubjects = $section->subjects;
            
            $debug['subjects_count'] = $sectionSubjects->count();
            $debug['subject_ids'] = $sectionSubjects->pluck('id')->toArray();
            
            // Get students in this section with their final grades
            $students = Student::where('section_id', $section->id)
                ->with(['grades' => function($query) use ($sectionSubjects) {
                    $query->whereIn('subject_id', $sectionSubjects->pluck('id')->toArray())
                          ->where(function($q) {
                              $q->where('grade_type', 'final') // Try to get final grades first
                                ->orWhereNotExists(function($subquery) {
                                    $subquery->select(DB::raw(1))
                                        ->from('grades as g')
                                        ->whereColumn('g.student_id', 'grades.student_id')
                                        ->whereColumn('g.subject_id', 'grades.subject_id')
                                        ->where('g.grade_type', 'final');
                                });
                          });
                }])
                ->get();
                
            $debug['students_count'] = $students->count();
            
            // Calculate average final grades for each student
            foreach ($students as $student) {
                // Check if student has any final grades first
                $finalGrades = $student->grades->where('grade_type', 'final');
                
                if ($finalGrades->count() > 0) {
                    // If final grades exist, use only those
                    $student->grades_avg_score = $finalGrades->avg('score');
                } else {
                    // Otherwise use regular grades (calculated per subject)
                    $avgGrades = [];
                    
                    foreach ($sectionSubjects as $subject) {
                        $subjectGrades = $student->grades->where('subject_id', $subject->id);
                        
                        if ($subjectGrades->count() > 0) {
                            // Calculate average per subject and add to the list
                            $avgGrades[] = $subjectGrades->avg('score');
                        }
                    }
                    
                    $student->grades_avg_score = count($avgGrades) > 0 ? array_sum($avgGrades) / count($avgGrades) : 0;
                }
                
                // Add attendance data
                $this->addAttendanceData($student, $section->id, $user->id);
            }
            
            // Sort students by their average grade
            $students = $students->sortByDesc('grades_avg_score')->values();
            
            // Take only top 5
            $topStudents = $students->take(5);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'students' => $topStudents,
                    'section' => $section,
                    'debug' => $debug
                ]);
            }
            
            return $topStudents;
        } catch (\Exception $e) {
            Log::error('Error getting performance data: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'section_id' => $sectionId
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Error fetching data: ' . $e->getMessage(),
                    'debug' => $debug
                ], 500);
            }
            
            return collect();
        }
    }
    
    /**
     * Add attendance data to student
     */
    private function addAttendanceData($student, $sectionId, $userId)
    {
        // Get attendance records for this section
        $attendanceRecords = Attendance::where(function($query) use ($userId, $sectionId) {
            $query->where('teacher_id', $userId)
                  ->orWhere('section_id', $sectionId);
        })
        ->get();
            
        $totalDays = $attendanceRecords->count();
        $presentDays = 0;
        $lateDays = 0;
        
        foreach ($attendanceRecords as $record) {
            // Using the new attendance data structure
            if ($record->attendance_data) {
                $attendanceData = is_array($record->attendance_data) ? 
                    $record->attendance_data : json_decode($record->attendance_data, true) ?? [];
                
                foreach ($attendanceData as $data) {
                    if (isset($data['student_id']) && $data['student_id'] == $student->id) {
                        if ($data['status'] === 'present') {
                            $presentDays++;
                        } elseif ($data['status'] === 'late') {
                            $lateDays++;
                        }
                        break;
                    }
                }
            }
            // Using the old attendance structure
            else if ($record->status && $record->student_id == $student->id) {
                if ($record->status === 'present') {
                    $presentDays++;
                } elseif ($record->status === 'late') {
                    $lateDays++;
                }
            }
        }
        
        $student->attendance_rate = $totalDays > 0 ? round((($presentDays + $lateDays) / $totalDays) * 100) : 0;
        $student->performance_rating = $this->calculatePerformanceRating($student->grades_avg_score, $student->attendance_rate);
        
        // Add additional data for display
        $student->present_days = $presentDays;
        $student->late_days = $lateDays;
        $student->total_days = $totalDays;
        $student->grades_count = $student->grades->count();
    }
}
