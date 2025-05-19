<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeApproval;
use App\Models\Attendance;
use App\Services\AttendanceSummaryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * The attendance summary service instance.
     */
    protected $attendanceSummaryService;

    /**
     * Create a new controller instance.
     */
    public function __construct(AttendanceSummaryService $attendanceSummaryService)
    {
        $this->attendanceSummaryService = $attendanceSummaryService;
    }

    /**
     * Display a listing of the reports.
     */
    public function index()
    {
        return view('teacher_admin.reports.index');
    }

    /**
     * Display the consolidated grades report form.
     */
    public function consolidatedGrades(Request $request)
    {
        $teacher = Auth::user();

        // Get all sections in the school, organized by grade level
        $query = Section::where('school_id', $teacher->school_id)
                       ->where('is_active', true);

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Apply grade level filter if provided
        if ($request->has('grade_level') && !empty($request->grade_level)) {
            $query->where('grade_level', $request->grade_level);
        }

        $sections = $query->orderBy('grade_level')
                         ->orderBy('name')
                         ->get();

        // Group sections by grade level for easier navigation
        $sectionsByGradeLevel = $sections->groupBy('grade_level');

        // Get all available grade levels for the filter
        $gradeLevels = Section::where('school_id', $teacher->school_id)
                            ->where('is_active', true)
                            ->distinct()
                            ->pluck('grade_level')
                            ->sort()
                            ->values();

        $quarters = [
            'Q1' => '1st Quarter',
            'Q2' => '2nd Quarter',
            'Q3' => '3rd Quarter',
            'Q4' => '4th Quarter'
        ];

        return view('teacher_admin.reports.consolidated_grades', compact('sections', 'sectionsByGradeLevel', 'gradeLevels', 'quarters'));
    }

    /**
     * Generate the consolidated grades report.
     */
    public function generateConsolidatedGrades(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
            'transmutation_table' => 'sometimes|integer|min:1|max:4',
        ]);

        $teacher = Auth::user();
        $section = Section::with('adviser', 'school')->findOrFail($validated['section_id']);

        // Check if the section belongs to the teacher's school
        if ($section->school_id !== $teacher->school_id) {
            return redirect()->back()->with('error', 'You can only generate reports for sections in your school.');
        }

        // Get all subjects for this section
        $subjects = $section->subjects()
                           ->where('subjects.is_active', true)
                           ->orderBy('subjects.name')
                           ->get();

        // Get only active students in this section
        $students = Student::where('section_id', $section->id)
                          ->where('is_active', true)
                          ->orderBy('gender')
                          ->orderBy('last_name')
                          ->orderBy('first_name')
                          ->get();

        // Group students by gender
        $maleStudents = $students->where('gender', 'Male');
        $femaleStudents = $students->where('gender', 'Female');

        // Get grade approvals for all subjects in this section
        $gradeApprovals = GradeApproval::where('section_id', $section->id)
                                      ->where('quarter', $validated['quarter'])
                                      ->get()
                                      ->keyBy('subject_id');

        // Get all subjects including components
        $allSubjects = $subjects->toBase();
        $mapehParentMap = []; // Map to track which MAPEH subject is the parent of each component

        foreach ($subjects as $subject) {
            if ($subject->getIsMAPEHAttribute()) {
                $components = $subject->components;
                $allSubjects = $allSubjects->merge($components);

                // Map each component to its parent MAPEH subject
                foreach ($components as $component) {
                    $mapehParentMap[$component->id] = $subject->id;
                }
            }
        }

        // Create an extended approvals array that includes component approvals
        // A component is considered approved if its parent MAPEH subject is approved
        $extendedApprovals = clone $gradeApprovals;

        foreach ($mapehParentMap as $componentId => $parentId) {
            if (isset($gradeApprovals[$parentId]) && $gradeApprovals[$parentId]->is_approved) {
                // If parent MAPEH is approved, consider the component approved too
                if (!isset($extendedApprovals[$componentId])) {
                    $extendedApprovals[$componentId] = (object) [
                        'is_approved' => true,
                        'inherited_from_parent' => true,
                        'parent_id' => $parentId
                    ];
                }
            }
        }

        // Debug information
        \Illuminate\Support\Facades\Log::info('Grade Approvals:', [
            'section_id' => $section->id,
            'quarter' => $validated['quarter'],
            'approvals_count' => $gradeApprovals->count(),
            'extended_approvals_count' => count($extendedApprovals),
            'mapeh_parent_map' => $mapehParentMap,
            'approvals' => $gradeApprovals->toArray()
        ]);

        // Log the extended approvals for debugging
        \Illuminate\Support\Facades\Log::info('Extended Approvals:', [
            'inherited_approvals' => collect($extendedApprovals)
                ->filter(function($approval) {
                    return isset($approval->inherited_from_parent) && $approval->inherited_from_parent;
                })
                ->map(function($approval, $componentId) {
                    return [
                        'component_id' => $componentId,
                        'parent_id' => $approval->parent_id ?? 'unknown'
                    ];
                })
                ->values()
                ->toArray()
        ]);

        // Get all grades for these students in this quarter (including component subjects)
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
                      ->whereIn('subject_id', $allSubjects->pluck('id'))
                      ->where('term', $validated['quarter'])
                      ->get();

        // Debug information about subjects
        \Illuminate\Support\Facades\Log::info('Subject IDs being queried:', [
            'all_subject_ids' => $allSubjects->pluck('id')->toArray(),
            'regular_subject_ids' => $subjects->pluck('id')->toArray(),
            'component_ids' => $allSubjects->diff($subjects)->pluck('id')->toArray()
        ]);

        // Debug information
        \Illuminate\Support\Facades\Log::info('Grades:', [
            'grades_count' => $grades->count(),
            'students_count' => $students->count(),
            'subjects_count' => $subjects->count(),
            'quarter' => $validated['quarter']
        ]);

        // Calculate average grades by student and subject
        $studentGrades = [];

        // Debug information
        \Illuminate\Support\Facades\Log::info('Processing subjects:', [
            'total_subjects' => $subjects->count(),
            'mapeh_subjects' => $subjects->filter(function($s) { return $s->getIsMAPEHAttribute(); })->count(),
            'component_subjects' => $subjects->where('is_component', true)->count()
        ]);

        foreach ($students as $student) {
            // Process all subjects including MAPEH components
            foreach ($allSubjects as $subject) {
                $subjectGrades = $grades->where('student_id', $student->id)
                                       ->where('subject_id', $subject->id);

                if ($subjectGrades->count() > 0) {
                    // Get grade configuration for this subject
                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subject->id)->first();

                    if (!$gradeConfig) {
                        // Use default percentages if no configuration exists
                        $wwPercentage = 25;
                        $ptPercentage = 50;
                        $qaPercentage = 25;
                    } else {
                        $wwPercentage = $gradeConfig->written_work_percentage;
                        $ptPercentage = $gradeConfig->performance_task_percentage;
                        $qaPercentage = $gradeConfig->quarterly_assessment_percentage;
                    }

                    // Group grades by type
                    $writtenWorks = $subjectGrades->where('grade_type', 'written_work');
                    $performanceTasks = $subjectGrades->where('grade_type', 'performance_task');

                    // Find quarterly grades using filter
                    $quarterlyAssessments = $subjectGrades->filter(function($grade) {
                        return $grade->grade_type === 'quarterly' ||
                               $grade->grade_type === 'quarterly_assessment' ||
                               $grade->grade_type === 'quarterly_exam';
                    });

                    // Calculate component averages - direct assessment scores
                    $wwPS = $this->calculateComponentAverage($writtenWorks);
                    $ptPS = $this->calculateComponentAverage($performanceTasks);
                    $qaPS = $this->calculateComponentAverage($quarterlyAssessments);

                    // Calculate weighted scores based on grade configuration
                    $wwWS = $wwPS !== null ? ($wwPS / 100) * $wwPercentage : 0;
                    $ptWS = $ptPS !== null ? ($ptPS / 100) * $ptPercentage : 0;
                    $qaWS = $qaPS !== null ? ($qaPS / 100) * $qaPercentage : 0;

                    // Calculate initial grade (sum of weighted scores)
                    $initialGrade = $wwWS + $ptWS + $qaWS;

                    // Normalize if not all components are present
                    $weightSum = 0;
                    if ($wwPS !== null) $weightSum += $wwPercentage;
                    if ($ptPS !== null) $weightSum += $ptPercentage;
                    if ($qaPS !== null) $weightSum += $qaPercentage;

                    if ($weightSum > 0 && $weightSum < 100) {
                        $initialGrade = ($initialGrade / $weightSum) * 100;
                    }

                    // Round the initial grade
                    $initialGrade = round($initialGrade, 1);

                    // Create a grade object with the calculated grades and assessment details
                    $studentGrades[$student->id][$subject->id] = (object) [
                        'quarterly_grade' => $initialGrade,
                        'remarks' => $initialGrade >= 75 ? 'Passed' : 'Failed',
                        'components' => [
                            'written_work' => ['ps' => $wwPS, 'ws' => $wwWS, 'percentage' => $wwPercentage],
                            'performance_task' => ['ps' => $ptPS, 'ws' => $ptWS, 'percentage' => $ptPercentage],
                            'quarterly_assessment' => ['ps' => $qaPS, 'ws' => $qaWS, 'percentage' => $qaPercentage]
                        ],
                        'grade_config' => [
                            'written_work_percentage' => $wwPercentage,
                            'performance_task_percentage' => $ptPercentage,
                            'quarterly_assessment_percentage' => $qaPercentage
                        ]
                    ];

                    // Log component grades for debugging
                    if ($subject->is_component) {
                        \Illuminate\Support\Facades\Log::info('Calculated component grade:', [
                            'student_id' => $student->id,
                            'component_id' => $subject->id,
                            'component_name' => $subject->name,
                            'grade' => $initialGrade,
                            'written_work' => $wwPS,
                            'performance_task' => $ptPS,
                            'quarterly_assessment' => $qaPS
                        ]);
                    }
                }
            }

            // Now process MAPEH subjects by calculating from components
            foreach ($subjects->filter(function($s) { return $s->getIsMAPEHAttribute(); }) as $mapehSubject) {
                // Get all component subjects
                $components = $mapehSubject->components;

                // Calculate MAPEH grade as weighted average of components
                $totalWeightedGrade = 0;
                $totalWeight = 0;
                $componentGrades = [];
                $componentCount = 0;

                foreach ($components as $component) {
                    if (isset($studentGrades[$student->id][$component->id])) {
                        $componentGrade = $studentGrades[$student->id][$component->id]->quarterly_grade;
                        $componentWeight = $component->component_weight ?: 25; // Default to 25% if not set

                        // Add component grade to total with its weight
                        $totalWeightedGrade += ($componentGrade * $componentWeight);
                        $totalWeight += $componentWeight;
                        $componentCount++;

                        // Store for logging
                        $componentGrades[$component->id] = [
                            'grade' => $componentGrade,
                            'weight' => $componentWeight
                        ];
                    }
                }

                if ($componentCount > 0) {
                    // Calculate weighted average
                    $mapehGrade = $totalWeight > 0 ? round($totalWeightedGrade / $totalWeight, 1) : 0;

                    // Store the MAPEH grade
                    $studentGrades[$student->id][$mapehSubject->id] = (object) [
                        'quarterly_grade' => $mapehGrade,
                        'component_grades' => $componentGrades,
                        'remarks' => $mapehGrade >= 75 ? 'Passed' : 'Failed'
                    ];

                    // Log for debugging
                    \Illuminate\Support\Facades\Log::info('Calculated MAPEH grade:', [
                        'student_id' => $student->id,
                        'student_name' => $student->first_name . ' ' . $student->last_name,
                        'mapeh_subject_id' => $mapehSubject->id,
                        'component_count' => $componentCount,
                        'mapeh_grade' => $mapehGrade,
                        'total_weighted_grade' => $totalWeightedGrade,
                        'total_weight' => $totalWeight,
                        'calculation' => 'Weighted average: ' . $totalWeightedGrade . ' / ' . $totalWeight . ' = ' . ($totalWeightedGrade / $totalWeight) . ' rounded to ' . $mapehGrade,
                        'component_grades' => collect($componentGrades)->map(function($gradeInfo, $compId) use ($components) {
                            $component = $components->firstWhere('id', $compId);
                            return [
                                'component_id' => $compId,
                                'component_name' => $component ? $component->name : 'Unknown',
                                'grade' => $gradeInfo['grade'],
                                'weight' => $gradeInfo['weight'],
                                'contribution' => ($gradeInfo['grade'] * $gradeInfo['weight']) / 100 // Weighted contribution
                            ];
                        })->values()->toArray()
                    ]);
                }
            }
        }

        // Get MAPEH subjects and components
        $mapehSubjects = $subjects->filter(function($subject) {
            return $subject->getIsMAPEHAttribute();
        });

        $mapehComponents = collect();
        foreach ($mapehSubjects as $mapeh) {
            $components = $mapeh->components;
            if ($components->count() > 0) {
                $mapehComponents = $mapehComponents->merge($components);
            }
        }

        // Get school year from section
        $schoolYear = $section->school_year;

        // Get the transmutation table from request or use teacher's preference
        $teacherId = Auth::id();

        if ($request->has('transmutation_table')) {
            // Use the table from the request
            $preferredTableId = $request->transmutation_table;
        } else {
            // Get the teacher's transmutation table preference
            $preferredTableId = DB::table('teacher_preferences')
                ->where('teacher_id', $teacherId)
                ->where('preference_key', 'transmutation_table')
                ->value('preference_value');

            // If no preference exists yet, set DepEd Transmutation Table (1) as default
            if (!$preferredTableId) {
                $preferredTableId = 1; // DepEd Transmutation Table is now Table 1
            }
        }

        return view('teacher_admin.reports.consolidated_grades_report', [
            'section' => $section,
            'quarter' => $validated['quarter'],
            'quarterName' => ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'][$validated['quarter']],
            'subjects' => $subjects,
            'maleStudents' => $maleStudents,
            'femaleStudents' => $femaleStudents,
            'studentGrades' => $studentGrades,
            'gradeApprovals' => $extendedApprovals, // Use extended approvals that include inherited component approvals
            'originalApprovals' => $gradeApprovals, // Also pass the original approvals for reference
            'mapehSubjects' => $mapehSubjects,
            'mapehComponents' => $mapehComponents,
            'mapehParentMap' => $mapehParentMap,
            'schoolYear' => $schoolYear,
            'preferredTableId' => $preferredTableId,
            'isPrintView' => true,
        ]);
    }

    /**
     * Calculate the average for a specific grade component
     * Uses total score divided by total max score method
     *
     * @param \Illuminate\Support\Collection $grades
     * @return float|null
     */
    private function calculateComponentAverage($grades)
    {
        if ($grades->isEmpty()) {
            return null;
        }

        $totalScore = 0;
        $totalMaxScore = 0;

        foreach ($grades as $grade) {
            if ($grade->max_score > 0) {
                $totalScore += $grade->score;
                $totalMaxScore += $grade->max_score;
            }
        }

        return $totalMaxScore > 0 ? round(($totalScore / $totalMaxScore) * 100, 1) : 0;
    }

    /**
     * Display the attendance summary report form.
     */
    public function attendanceSummary(Request $request)
    {
        $teacher = Auth::user();

        // Get all grade levels in the school
        $gradeLevels = Section::where('school_id', $teacher->school_id)
                            ->where('is_active', true)
                            ->distinct()
                            ->pluck('grade_level')
                            ->sort()
                            ->values();

        // Get all sections in the school, organized by grade level
        $query = Section::where('school_id', $teacher->school_id)
                       ->where('is_active', true);

        // Apply grade level filter if provided
        if ($request->has('grade_level') && !empty($request->grade_level)) {
            $query->where('grade_level', $request->grade_level);
        }

        $sections = $query->orderBy('grade_level')
                         ->orderBy('name')
                         ->get();

        // Group sections by grade level for easier navigation
        $sectionsByGradeLevel = $sections->groupBy('grade_level');

        // Get available months with attendance records
        $availableMonths = Attendance::join('students', 'attendances.student_id', '=', 'students.id')
            ->join('sections', 'students.section_id', '=', 'sections.id')
            ->where('sections.school_id', $teacher->school_id)
            ->select(DB::raw('DISTINCT DATE_FORMAT(date, "%Y-%m") as month_value, DATE_FORMAT(date, "%M %Y") as month_name'))
            ->orderBy('month_value', 'desc')
            ->get();

        return view('teacher_admin.reports.attendance_summary', compact('gradeLevels', 'sections', 'sectionsByGradeLevel', 'availableMonths'));
    }

    /**
     * Generate the attendance summary report.
     */
    public function generateAttendanceSummary(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'grade_level' => 'nullable|string',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        $teacher = Auth::user();
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        // Get sections based on filters
        $sectionsQuery = Section::where('school_id', $teacher->school_id)
                              ->where('is_active', true);

        if (!empty($validated['grade_level'])) {
            $sectionsQuery->where('grade_level', $validated['grade_level']);
        }

        if (!empty($validated['section_id'])) {
            $sectionsQuery->where('id', $validated['section_id']);
        }

        $sections = $sectionsQuery->orderBy('grade_level')
                                ->orderBy('name')
                                ->get();

        // Get section IDs for attendance query
        $sectionIds = $sections->pluck('id')->toArray();

        // Get all attendance records for the date range and sections
        $attendanceRecords = Attendance::whereIn('section_id', $sectionIds)
                                     ->whereBetween('date', [$startDate, $endDate])
                                     ->with(['student', 'student.section'])
                                     ->get();

        // Get all dates with attendance records in the range
        $attendanceDates = Attendance::whereIn('section_id', $sectionIds)
                                   ->whereBetween('date', [$startDate, $endDate])
                                   ->select(DB::raw('DISTINCT date'))
                                   ->orderBy('date')
                                   ->pluck('date')
                                   ->map(function ($date) {
                                       return Carbon::parse($date);
                                   });

        // Get all active students in these sections
        $students = Student::whereIn('section_id', $sectionIds)
                         ->where('is_active', true)
                         ->with('section')
                         ->get();

        // Initialize summary data structure
        $summary = [
            'date_range' => [
                'start' => $startDate->format('M d, Y'),
                'end' => $endDate->format('M d, Y'),
            ],
            'total_students' => $students->count(),
            'total_school_days' => $attendanceDates->count(),
            'overall_stats' => [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'half_day' => 0,
                'total_attendance_records' => 0,
                'attendance_rate' => 0,
            ],
            'grade_level_stats' => [],
            'section_stats' => [],
            'daily_stats' => [],
            'students_with_concerns' => [],
            'low_attendance_days' => [],
        ];

        // Process attendance by date
        foreach ($attendanceDates as $date) {
            $dateString = $date->toDateString();
            $formattedDate = $date->format('D, M d');

            // Get attendance records for this date
            $dateAttendance = $attendanceRecords->where('date', $dateString);

            // Initialize daily stats
            $summary['daily_stats'][$dateString] = [
                'date' => $formattedDate,
                'present' => $dateAttendance->where('status', 'present')->count(),
                'absent' => $dateAttendance->where('status', 'absent')->count(),
                'late' => $dateAttendance->where('status', 'late')->count(),
                'excused' => $dateAttendance->where('status', 'excused')->count(),
                'half_day' => $dateAttendance->where('status', 'half_day')->count(),
                'total_records' => $dateAttendance->count(),
                'attendance_rate' => 0,
            ];

            // Calculate attendance rate for the day
            $presentCount = $summary['daily_stats'][$dateString]['present'];
            $totalRecords = $summary['daily_stats'][$dateString]['total_records'];

            if ($totalRecords > 0) {
                $summary['daily_stats'][$dateString]['attendance_rate'] = round(($presentCount / $totalRecords) * 100, 1);
            }

            // Check if this is a low attendance day (below 80%)
            if ($totalRecords >= 10 && $summary['daily_stats'][$dateString]['attendance_rate'] < 80) {
                $summary['low_attendance_days'][] = [
                    'date' => $formattedDate,
                    'attendance_rate' => $summary['daily_stats'][$dateString]['attendance_rate'],
                    'present' => $presentCount,
                    'total' => $totalRecords,
                ];
            }
        }

        // Process attendance by grade level
        $gradeAttendance = $attendanceRecords->groupBy(function ($record) {
            return $record->student->section->grade_level;
        });

        foreach ($gradeAttendance as $gradeLevel => $records) {
            $presentCount = $records->where('status', 'present')->count();
            $totalRecords = $records->count();

            $summary['grade_level_stats'][$gradeLevel] = [
                'present' => $presentCount,
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
                'excused' => $records->where('status', 'excused')->count(),
                'half_day' => $records->where('status', 'half_day')->count(),
                'total_records' => $totalRecords,
                'attendance_rate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0,
            ];
        }

        // Process attendance by section
        $sectionAttendance = $attendanceRecords->groupBy('section_id');

        foreach ($sections as $section) {
            $records = $sectionAttendance->get($section->id, collect());
            $presentCount = $records->where('status', 'present')->count();
            $totalRecords = $records->count();

            $summary['section_stats'][$section->id] = [
                'name' => $section->name,
                'grade_level' => $section->grade_level,
                'present' => $presentCount,
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
                'excused' => $records->where('status', 'excused')->count(),
                'half_day' => $records->where('status', 'half_day')->count(),
                'total_records' => $totalRecords,
                'attendance_rate' => $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0,
            ];
        }

        // Process student attendance to identify concerns
        $studentAttendance = $attendanceRecords->groupBy('student_id');

        foreach ($students as $student) {
            $records = $studentAttendance->get($student->id, collect());
            $absentCount = $records->where('status', 'absent')->count();
            $lateCount = $records->where('status', 'late')->count();
            $totalRecords = $records->count();

            // Identify students with attendance concerns (more than 20% absences or more than 30% late)
            $absentRate = $totalRecords > 0 ? ($absentCount / $totalRecords) * 100 : 0;
            $lateRate = $totalRecords > 0 ? ($lateCount / $totalRecords) * 100 : 0;

            if (($absentRate > 20 || $lateRate > 30) && $totalRecords >= 5) {
                $summary['students_with_concerns'][] = [
                    'id' => $student->id,
                    'name' => $student->last_name . ', ' . $student->first_name,
                    'section' => $student->section->name,
                    'grade_level' => $student->section->grade_level,
                    'absent_count' => $absentCount,
                    'late_count' => $lateCount,
                    'total_records' => $totalRecords,
                    'absent_rate' => round($absentRate, 1),
                    'late_rate' => round($lateRate, 1),
                ];
            }
        }

        // Calculate overall statistics
        $summary['overall_stats'] = [
            'present' => $attendanceRecords->where('status', 'present')->count(),
            'absent' => $attendanceRecords->where('status', 'absent')->count(),
            'late' => $attendanceRecords->where('status', 'late')->count(),
            'excused' => $attendanceRecords->where('status', 'excused')->count(),
            'half_day' => $attendanceRecords->where('status', 'half_day')->count(),
            'total_attendance_records' => $attendanceRecords->count(),
        ];

        // Calculate overall attendance rate
        if ($summary['overall_stats']['total_attendance_records'] > 0) {
            $summary['overall_stats']['attendance_rate'] = round(
                ($summary['overall_stats']['present'] / $summary['overall_stats']['total_attendance_records']) * 100,
                1
            );
        }

        // Sort sections by grade level and name
        $summary['section_stats'] = collect($summary['section_stats'])
            ->sortBy([
                ['grade_level', 'asc'],
                ['name', 'asc']
            ])
            ->toArray();

        // Sort grade levels
        ksort($summary['grade_level_stats']);

        // Sort students with concerns by absent rate (descending)
        $summary['students_with_concerns'] = collect($summary['students_with_concerns'])
            ->sortByDesc('absent_rate')
            ->values()
            ->toArray();

        // Sort low attendance days by attendance rate (ascending)
        $summary['low_attendance_days'] = collect($summary['low_attendance_days'])
            ->sortBy('attendance_rate')
            ->values()
            ->toArray();

        return view('teacher_admin.reports.attendance_summary_report', [
            'summary' => $summary,
            'sections' => $sections,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
