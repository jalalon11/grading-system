<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Student;
use App\Models\Grade;
use App\Models\GradeApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
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
}
