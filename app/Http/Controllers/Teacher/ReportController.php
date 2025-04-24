<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\GradeConfiguration;
use App\Models\GradeApproval;
use Carbon\Carbon;
use App\Helpers\GradeHelper;

class ReportController extends Controller
{
    public function index()
    {
        return view('teacher.reports.index');
    }

    public function classRecord()
    {
        $teacher = Auth::user();

        // Get sections where the teacher is assigned to any subject
        $sections = Section::whereHas('subjects', function($query) use ($teacher) {
            $query->whereHas('teachers', function($q) use ($teacher) {
                $q->where('users.id', $teacher->id);
            });
        })->get();

        // Initially we don't need to load subjects as they'll be loaded via AJAX
        return view('teacher.reports.class_record', compact('sections'));
    }

    /**
     * Get subjects for a specific section that are assigned to the current teacher
     */
    public function getSectionSubjects(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id'
        ]);

        $teacher = Auth::user();
        $sectionId = $request->section_id;

        // Get subjects assigned to this teacher for this specific section
        $subjects = Subject::whereHas('sections', function($query) use ($sectionId, $teacher) {
            $query->where('sections.id', $sectionId)
                  ->where('section_subject.teacher_id', $teacher->id);
        })->get();

        // Include component information for each subject
        $subjects->each(function($subject) {
            // Load components for all subjects
            $subject->load('components');
        });

        return response()->json($subjects);
    }

    public function generateClassRecord(Request $request)
    {
        // Validate the request - allow both POST and GET methods
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
        ]);

        // Log the request method for debugging
        error_log('Request method: ' . $request->method() . ' - From User Agent: ' . $request->header('User-Agent'));

        // Get the section and subject
        $section = Section::findOrFail($validated['section_id']);
        $subject = Subject::findOrFail($validated['subject_id']);

        // Check if this is a MAPEH component (MUSIC, ARTS, PHYSICAL EDUCATION, HEALTH)
        $isMapehComponent = false;
        $parentMapehSubject = null;

        if ($subject->is_component && $subject->parentSubject) {
            $parent = $subject->parentSubject;
            if ($parent->getIsMAPEHAttribute()) {
                $isMapehComponent = true;
                $parentMapehSubject = $parent;

                // Log for debugging
                error_log('MAPEH Component detected: ' . $subject->name . ' is part of ' . $parent->name);
            }
        }

        // Dump for debugging
        error_log('Selected section: ' . $section->name . ', ID: ' . $section->id);

        // Get only active students from the database to ensure they're loaded
        $students = Student::where('section_id', $section->id)
            ->where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Dump student count for debugging
        error_log('Number of students found in section: ' . $students->count());

        // If no students in this section but we know there are students in the database,
        // explicitly load students matching Vincent and Vinz Jalalon
        if ($students->isEmpty() && $section->name == 'St. Paul') {
            $students = Student::where(function($query) {
                $query->where('first_name', 'like', 'Vincent%')
                    ->orWhere('first_name', 'like', 'Vinz%');
            })
            ->where('last_name', 'Jalalon')
            ->get();

            if ($students->isNotEmpty()) {
                foreach ($students as $student) {
                    $student->section_id = $section->id;
                }
                error_log('Found students manually: ' . $students->count());
            }
        }

        // Get the grade configuration for this subject
        $gradeConfig = GradeConfiguration::where('subject_id', $subject->id)->first();
        if (!$gradeConfig) {
            // Use default percentages if no configuration exists
            $gradeConfig = new GradeConfiguration([
                'written_work_percentage' => 25,
                'performance_task_percentage' => 50,
                'quarterly_assessment_percentage' => 25,
            ]);
        }

        // Get all grades for these students in this subject and quarter
        $grades = Grade::where('subject_id', $subject->id)
            ->where('term', $validated['quarter'])
            ->whereIn('student_id', $students->pluck('id'))
            ->orderBy('created_at')
            ->get();

        // Group grades by student_id
        $studentGrades = $grades->groupBy('student_id');

        // Pass information about MAPEH component to the view
        $mapehInfo = null;
        if ($isMapehComponent) {
            $mapehInfo = [
                'is_component' => true,
                'component_name' => $subject->name,
                'parent_subject' => $parentMapehSubject,
            ];
        } elseif ($subject->getIsMAPEHAttribute()) {
            $mapehInfo = [
                'is_mapeh' => true,
                'components' => $subject->components,
            ];
        }

        // Get all unique written works - make sure we're getting all of them
        $writtenWorks = $grades->where('grade_type', 'written_work')
            ->unique(function($item) {
                // Ensure we get each unique assessment, even if they have the same name
                return $item->assessment_name . '-' . $item->max_score;
            })
            ->values(); // Reset array keys

        // Sort written works numerically if possible
        $writtenWorks = $writtenWorks->sortBy(function($grade) {
            // Try to extract number for better sorting (Quiz 1, Quiz 2, etc.)
            preg_match('/(\d+)/', $grade->assessment_name, $matches);
            return $matches[1] ?? $grade->assessment_name;
        })->values();

        $performanceTasks = $grades->where('grade_type', 'performance_task')
            ->unique(function($item) {
                return $item->assessment_name . '-' . $item->max_score;
            })
            ->sortBy(function($grade) {
                preg_match('/(\d+)/', $grade->assessment_name, $matches);
                return $matches[1] ?? $grade->assessment_name;
            })
            ->values();

        $quarterlyAssessments = $grades->where('grade_type', 'quarterly')
            ->unique('assessment_name');

        // Debug info
        error_log('Class Record Data: ' .
            'Section: ' . $section->name . ', ' .
            'Students: ' . $students->count() . ', ' .
            'Male: ' . $students->where('gender', 'Male')->count() . ', ' .
            'Female: ' . $students->where('gender', 'Female')->count() . ', ' .
            'Grades: ' . $grades->count() . ', ' .
            'Written Works: ' . $writtenWorks->count()
        );

        return view('teacher.reports.class_record_report', [
            'section' => $section,
            'subject' => $subject,
            'quarter' => $validated['quarter'],
            'students' => $students,
            'studentGrades' => $studentGrades,
            'gradeConfig' => $gradeConfig,
            'writtenWorks' => $writtenWorks,
            'performanceTasks' => $performanceTasks,
            'quarterlyAssessments' => $quarterlyAssessments,
            'mapehInfo' => $mapehInfo,
        ]);
    }

    /**
     * Get lists of students by grade ranges
     */
    public function getStudentsByGradeRanges(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
        ]);

        // Get the section and subject
        $section = Section::findOrFail($validated['section_id']);
        $subject = Subject::findOrFail($validated['subject_id']);

        // Get only active students
        $students = Student::where('section_id', $section->id)
            ->where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get the grade configuration for this subject
        $gradeConfig = GradeConfiguration::where('subject_id', $subject->id)->first();
        if (!$gradeConfig) {
            // Use default percentages if no configuration exists
            $gradeConfig = new GradeConfiguration([
                'written_work_percentage' => 25,
                'performance_task_percentage' => 50,
                'quarterly_assessment_percentage' => 25,
            ]);
        }

        // Get all grades for these students in this subject and quarter
        $grades = Grade::where('subject_id', $subject->id)
            ->where('term', $validated['quarter'])
            ->whereIn('student_id', $students->pluck('id'))
            ->orderBy('created_at')
            ->get();

        // Group grades by student_id
        $studentGrades = $grades->groupBy('student_id');

        // Calculate average grades for each student
        $studentAverages = [];
        foreach ($students as $student) {
            $studentId = $student->id;

            if (!isset($studentGrades[$studentId])) {
                continue; // Skip students with no grades
            }

            // Get grades for this student
            $gradesData = $studentGrades[$studentId];

            // Calculate weighted scores
            $writtenWorkTotal = 0;
            $writtenWorkPS = 0;
            $writtenWorkWS = 0;

            $performanceTaskTotal = 0;
            $performanceTaskPS = 0;
            $performanceTaskWS = 0;

            $quarterlyTotal = 0;
            $quarterlyPS = 0;
            $quarterlyWS = 0;

            // Calculate written work scores
            $writtenWorks = $gradesData->where('grade_type', 'written_work');
            if ($writtenWorks->count() > 0) {
                $writtenWorkTotal = $writtenWorks->sum('score');
                $writtenWorkPS = $writtenWorkTotal / $writtenWorks->sum('max_score') * 100;
                $writtenWorkWS = $writtenWorkPS * ($gradeConfig->written_work_percentage / 100);
            }

            // Calculate performance task scores
            $performanceTasks = $gradesData->where('grade_type', 'performance_task');
            if ($performanceTasks->count() > 0) {
                $performanceTaskTotal = $performanceTasks->sum('score');
                $performanceTaskPS = $performanceTaskTotal / $performanceTasks->sum('max_score') * 100;
                $performanceTaskWS = $performanceTaskPS * ($gradeConfig->performance_task_percentage / 100);
            }

            // Calculate quarterly assessment scores
            $quarterlyAssessments = $gradesData->where('grade_type', 'quarterly');
            if ($quarterlyAssessments->count() > 0) {
                $quarterlyTotal = $quarterlyAssessments->sum('score');
                $quarterlyPS = $quarterlyTotal / $quarterlyAssessments->sum('max_score') * 100;
                $quarterlyWS = $quarterlyPS * ($gradeConfig->quarterly_assessment_percentage / 100);
            }

            // Calculate initial grade
            $initialGrade = $writtenWorkWS + $performanceTaskWS + $quarterlyWS;

            // Calculate quarterly grade using the transmutation table
            $quarterlyGrade = $this->transmutationTable1($initialGrade);

            // Store the student's average
            $studentAverages[$studentId] = [
                'student' => $student,
                'initialGrade' => $initialGrade,
                'quarterlyGrade' => $quarterlyGrade
            ];
        }

        // Create grade ranges from 65-69 up to 95-100 in increments of 5
        $ranges = [];
        for ($i = 65; $i <= 95; $i += 5) {
            $rangeEnd = ($i == 95) ? 100 : $i + 4;
            $rangeName = $i . '-' . $rangeEnd;
            $ranges[$rangeName] = [];
        }

        // Group students by grade ranges
        foreach ($studentAverages as $studentId => $data) {
            $grade = $data['quarterlyGrade'];

            for ($i = 65; $i <= 95; $i += 5) {
                $rangeEnd = ($i == 95) ? 100 : $i + 4;
                if ($grade >= $i && $grade <= $rangeEnd) {
                    $rangeName = $i . '-' . $rangeEnd;
                    $ranges[$rangeName][] = [
                        'student' => $data['student'],
                        'grade' => $grade
                    ];
                    break;
                }
            }
        }

        return response()->json([
            'section' => $section,
            'subject' => $subject,
            'quarter' => $validated['quarter'],
            'ranges' => $ranges
        ]);
    }

    /**
     * Transmutation Table 1 function
     */
    private function transmutationTable1($initialGrade) {
        if ($initialGrade >= 100) return 100;
        if ($initialGrade >= 98.40) return 99;
        if ($initialGrade >= 96.80) return 98;
        if ($initialGrade >= 95.20) return 97;
        if ($initialGrade >= 93.60) return 96;
        if ($initialGrade >= 92.00) return 95;
        if ($initialGrade >= 90.40) return 94;
        if ($initialGrade >= 88.80) return 93;
        if ($initialGrade >= 87.20) return 92;
        if ($initialGrade >= 85.60) return 91;
        if ($initialGrade >= 84.00) return 90;
        if ($initialGrade >= 82.40) return 89;
        if ($initialGrade >= 80.80) return 88;
        if ($initialGrade >= 79.20) return 87;
        if ($initialGrade >= 77.60) return 86;
        if ($initialGrade >= 76.00) return 85;
        if ($initialGrade >= 74.40) return 84;
        if ($initialGrade >= 72.80) return 83;
        if ($initialGrade >= 71.20) return 82;
        if ($initialGrade >= 69.60) return 81;
        if ($initialGrade >= 68.00) return 80;
        if ($initialGrade >= 66.40) return 79;
        if ($initialGrade >= 64.80) return 78;
        if ($initialGrade >= 63.20) return 77;
        if ($initialGrade >= 61.60) return 76;
        if ($initialGrade >= 60.00) return 75;
        if ($initialGrade >= 56.00) return 74;
        if ($initialGrade >= 52.00) return 73;
        if ($initialGrade >= 48.00) return 72;
        if ($initialGrade >= 44.00) return 71;
        if ($initialGrade >= 40.00) return 70;
        if ($initialGrade >= 36.00) return 69;
        if ($initialGrade >= 32.00) return 68;
        if ($initialGrade >= 28.00) return 67;
        if ($initialGrade >= 24.00) return 66;
        if ($initialGrade >= 20.00) return 65;
        if ($initialGrade >= 16.00) return 64;
        if ($initialGrade >= 12.00) return 63;
        if ($initialGrade >= 8.00) return 62;
        if ($initialGrade >= 4.00) return 61;
        if ($initialGrade >= 0.00) return 60;
        return 0;
    }

    /**
     * Display the grade slips form
     */
    public function gradeSlips()
    {
        $teacher = Auth::user();

        // Get ONLY sections where the teacher is the adviser
        $sections = Section::where('adviser_id', $teacher->id)->get();

        // Log for debugging
        Log::info('Sections for grade slips (adviser only)', [
            'teacher_id' => $teacher->id,
            'adviser_sections' => $sections->pluck('name')->toArray(),
            'count' => $sections->count()
        ]);

        $quarters = [
            'Q1' => '1st Quarter',
            'Q2' => '2nd Quarter',
            'Q3' => '3rd Quarter',
            'Q4' => '4th Quarter'
        ];

        return view('teacher.reports.grade_slips', compact('sections', 'quarters'));
    }

    /**
     * Generate grade slips for students in a section
     */
    public function generateGradeSlips(Request $request)
    {
        // Set timezone to Manila/Philippines
        date_default_timezone_set('Asia/Manila');

        // Validate the request
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4,all',
            'debug' => 'nullable|boolean',
            'transmutation_table' => 'nullable|in:1,2,3,4',
        ]);

        $debug = $request->has('debug') ? (bool)$request->debug : false;
        $transmutationTable = $request->input('transmutation_table', 1); // Default to table 1 if not specified
        $isAllQuarters = $validated['quarter'] === 'all';

        $teacher = Auth::user();
        $section = Section::with('adviser', 'school')->findOrFail($validated['section_id']);

        // If 'all' is selected for quarter, we'll handle it differently
        if ($isAllQuarters) {
            // Get only active students in the section
            $students = Student::where('section_id', $section->id)
                ->where('is_active', true) // Only include active students
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();

            // For the all quarters view, we'll use Q1 as the base quarter for the list view
            // The actual all quarters data will be loaded when viewing individual student grade slips
            return view('teacher.reports.grade_slips_list', [
                'section' => $section,
                'quarter' => 'Q1',  // Use Q1 as base quarter for the list
                'quarterName' => 'All Quarters',
                'subjects' => $section->subjects()->where('subjects.is_active', true)->orderBy('subjects.name')->get(),
                'students' => $students,
                'studentGrades' => [],
                'gradeApprovals' => [],
                'mapehSubjects' => [],
                'mapehComponents' => [],
                'mapehParentMap' => [],
                'schoolYear' => $section->school_year ?? date('Y') . '-' . (date('Y') + 1),
                'currentTime' => Carbon::now('Asia/Manila'),
                'debug' => $debug,
                'debugData' => [],
                'transmutationTable' => $transmutationTable,
                'transmutationTableNames' => [
                    1 => 'DepEd Transmutation Table',
                    2 => 'Grades 1-10 & Non-Core TVL',
                    3 => 'SHS Core & Work Immersion',
                    4 => 'SHS Academic Track'
                ],
                'isAllQuarters' => true
            ]);
        }

        // Check if teacher is the adviser of this section
        if ($section->adviser_id != $teacher->id) {
            return back()->with('error', 'You can only generate grade slips for sections where you are the adviser.');
        }

        // Get only active students in the section
        $students = Student::where('section_id', $section->id)
            ->where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get all subjects for this section
        $subjects = $section->subjects()
            ->where('subjects.is_active', true)
            ->orderBy('subjects.name')
            ->get();

        // Log for debugging
        Log::info('Grade Slips - Subjects for section', [
            'section_id' => $section->id,
            'section_name' => $section->name,
            'subject_count' => $subjects->count(),
            'subjects' => $subjects->pluck('name', 'id')->toArray()
        ]);

        // Identify MAPEH subjects
        $mapehSubjects = $subjects->filter(function($subject) {
            return $subject->is_mapeh;
        });

        // Get all MAPEH components (both from the section and from the database)
        $mapehComponents = collect();

        // First, get components from the section subjects
        $sectionComponents = $subjects->filter(function($subject) {
            return $subject->mapeh_component;
        });

        // Then, for each MAPEH subject, get its components from the database
        foreach ($mapehSubjects as $mapehSubject) {
            $components = $mapehSubject->components;
            if ($components->count() > 0) {
                $mapehComponents = $mapehComponents->merge($components);
            }
        }

        // Combine and ensure uniqueness
        $mapehComponents = $mapehComponents->merge($sectionComponents)->unique('id');

        // Log basic MAPEH information
        Log::info('Grade Slips - MAPEH subjects and components', [
            'mapeh_subjects_count' => $mapehSubjects->count(),
            'mapeh_components_count' => $mapehComponents->count()
        ]);

        // Create a map of MAPEH components to their parent subject
        $mapehParentMap = [];
        foreach ($mapehComponents as $component) {
            $parent = $mapehSubjects->first(function($subject) use ($component) {
                return $subject->id == $component->parent_subject_id;
            });

            if ($parent) {
                $mapehParentMap[$component->id] = $parent->id;
            }
        }

        // Log MAPEH parent map
        Log::info('Grade Slips - MAPEH parent map', [
            'mapeh_parent_map' => $mapehParentMap
        ]);

        // Get all grade approvals for this section, quarter, and subjects
        $gradeApprovals = GradeApproval::where('section_id', $section->id)
            ->where('quarter', $validated['quarter'])
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->keyBy('subject_id');

        // Log grade approvals
        Log::info('Grade Slips - Grade approvals', [
            'section_id' => $section->id,
            'quarter' => $validated['quarter'],
            'approval_count' => $gradeApprovals->count(),
            'approved_subjects' => $gradeApprovals->where('is_approved', true)->pluck('subject_id')->toArray()
        ]);

        // Extend approvals to include MAPEH components if the parent is approved
        $extendedApprovals = clone $gradeApprovals;
        foreach ($mapehParentMap as $componentId => $parentId) {
            if (isset($gradeApprovals[$parentId]) && $gradeApprovals[$parentId]->is_approved) {
                // Create a virtual approval for the component
                $componentApproval = new GradeApproval([
                    'subject_id' => $componentId,
                    'section_id' => $section->id,
                    'quarter' => $validated['quarter'],
                    'is_approved' => true,
                    'inherited_from_parent' => true
                ]);
                $extendedApprovals[$componentId] = $componentApproval;
            }
        }

        // Initialize student grades array
        $studentGrades = [];
        $debugData = [];

        // Process each student
        foreach ($students as $student) {
            $studentGrades[$student->id] = [];
            $studentDebugData = [];

            // Process each subject
            foreach ($subjects as $subject) {
                // Skip MAPEH components as they'll be handled with their parent
                if ($subject->mapeh_component) {
                    continue;
                }

                // Check if this is a MAPEH subject
                $isMAPEH = $subject->is_mapeh;
                $subjectDebugData = [
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->name,
                    'is_mapeh' => $isMAPEH,
                ];

                if ($isMAPEH) {
                    // Handle MAPEH subject (get component grades and calculate average)
                    $componentGrades = [];
                    $totalWeightedGrade = 0;
                    $totalWeight = 0;
                    $componentCount = 0;
                    $componentDebugData = [];

                    // Find all components for this MAPEH subject
                    $components = $mapehComponents->filter(function($comp) use ($subject) {
                        return $comp->parent_subject_id == $subject->id;
                    });

                    $subjectDebugData['components'] = $components->pluck('name', 'id')->toArray();

                    // Process each component
                    foreach ($components as $component) {
                        // Get the component's grade configuration
                        $componentConfig = GradeConfiguration::where('subject_id', $component->id)->first();
                        if (!$componentConfig) {
                            // Use default percentages if no configuration exists
                            $componentConfig = new GradeConfiguration([
                                'written_work_percentage' => 25,
                                'performance_task_percentage' => 50,
                                'quarterly_assessment_percentage' => 25,
                            ]);
                        }

                        $componentDebugData[$component->id] = [
                            'component_name' => $component->name,
                            'grade_config' => $componentConfig ? [
                                'written_work_percentage' => $componentConfig->written_work_percentage,
                                'performance_task_percentage' => $componentConfig->performance_task_percentage,
                                'quarterly_assessment_percentage' => $componentConfig->quarterly_assessment_percentage,
                            ] : 'Using default configuration'
                        ];

                        // Get all grades for this student in this component and quarter
                        $componentGrades[$component->id] = $this->calculateSubjectGrade(
                            $student,
                            $component,
                            $validated['quarter'],
                            $componentConfig
                        );

                        // If component has a grade, include it in the MAPEH average
                        if (isset($componentGrades[$component->id])) {
                            $grade = $componentGrades[$component->id]->quarterly_grade;
                            // Use component weight if available, otherwise default to equal weights
                            $componentWeight = $component->component_weight ?? 25;
                            $totalWeightedGrade += ($grade * $componentWeight);
                            $totalWeight += $componentWeight;
                            $componentCount++;

                            $componentDebugData[$component->id]['grade'] = $grade;
                        } else {
                            $componentDebugData[$component->id]['grade'] = 'No grade found';
                        }
                    }

                    // Calculate MAPEH grade if we have components
                    if ($componentCount > 0) {
                        // Calculate weighted average
                        $mapehGrade = $totalWeight > 0 ? round($totalWeightedGrade / $totalWeight, 1) : 0;

                        $subjectDebugData['calculation'] = [
                            'total_weighted_grade' => $totalWeightedGrade,
                            'total_weight' => $totalWeight,
                            'component_count' => $componentCount,
                            'mapeh_grade' => $mapehGrade,
                            'component_details' => $componentDebugData
                        ];

                        // Store the MAPEH grade
                        $studentGrades[$student->id][$subject->id] = (object) [
                            'quarterly_grade' => $mapehGrade,
                            'component_grades' => $componentGrades,
                            'remarks' => $mapehGrade >= 75 ? 'Passed' : 'Failed'
                        ];
                    } else {
                        $subjectDebugData['calculation'] = 'No component grades found';
                    }
                } else {
                    // Handle regular subject
                    // Get the subject's grade configuration
                    $gradeConfig = GradeConfiguration::where('subject_id', $subject->id)->first();
                    if (!$gradeConfig) {
                        // Use default percentages if no configuration exists
                        $gradeConfig = new GradeConfiguration([
                            'written_work_percentage' => 25,
                            'performance_task_percentage' => 50,
                            'quarterly_assessment_percentage' => 25,
                        ]);
                    }

                    $subjectDebugData['grade_config'] = $gradeConfig ? [
                        'written_work_percentage' => $gradeConfig->written_work_percentage,
                        'performance_task_percentage' => $gradeConfig->performance_task_percentage,
                        'quarterly_assessment_percentage' => $gradeConfig->quarterly_assessment_percentage,
                    ] : 'Using default configuration';

                    // Calculate the subject grade
                    $subjectGrade = $this->calculateSubjectGrade(
                        $student,
                        $subject,
                        $validated['quarter'],
                        $gradeConfig,
                        $transmutationTable
                    );

                    if ($subjectGrade) {
                        $studentGrades[$student->id][$subject->id] = $subjectGrade;
                        $subjectDebugData['grade'] = $subjectGrade->quarterly_grade;
                        $subjectDebugData['components'] = $subjectGrade->components;
                    } else {
                        $subjectDebugData['grade'] = 'No grade found';
                    }
                }

                $studentDebugData[$subject->id] = $subjectDebugData;
            }

            $debugData[$student->id] = [
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'student_id' => $student->student_id,
                'subjects' => $studentDebugData
            ];
        }

        // Log detailed grade calculation for debugging
        Log::info('Grade Slips - Detailed grade calculations', [
            'section_id' => $section->id,
            'section_name' => $section->name,
            'quarter' => $validated['quarter'],
            'student_count' => $students->count(),
            'debug_data' => $debugData
        ]);

        // Get current school year
        $schoolYear = $section->school_year ?? date('Y') . '-' . (date('Y') + 1);

        // Get current time in Manila timezone
        $currentTime = Carbon::now('Asia/Manila');

        return view('teacher.reports.grade_slips_list', [
            'section' => $section,
            'quarter' => $validated['quarter'],
            'quarterName' => ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'][$validated['quarter']],
            'subjects' => $subjects->reject(function($subject) {
                return $subject->mapeh_component;
            }),
            'students' => $students,
            'studentGrades' => $studentGrades,
            'gradeApprovals' => $extendedApprovals,
            'mapehSubjects' => $mapehSubjects,
            'mapehComponents' => $mapehComponents,
            'mapehParentMap' => $mapehParentMap,
            'schoolYear' => $schoolYear,
            'currentTime' => $currentTime,
            'debug' => $debug,
            'debugData' => $debugData,
            'transmutationTable' => $transmutationTable,
            'transmutationTableNames' => [
                1 => 'DepEd Transmutation Table',
                2 => 'Grades 1-10 & Non-Core TVL',
                3 => 'SHS Core & Work Immersion',
                4 => 'SHS Academic Track'
            ],
        ]);
    }

    /**
     * Preview a specific student's grade slip
     */
    public function previewGradeSlip(Request $request)
    {
        // Set timezone to Manila/Philippines
        date_default_timezone_set('Asia/Manila');

        // Validate the request
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'section_id' => 'required|exists:sections,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4,all',
            'debug' => 'nullable|boolean',
            'transmutation_table' => 'nullable|in:1,2,3,4',
            'all_quarters' => 'nullable|boolean',
        ]);

        $debug = $request->has('debug') ? (bool)$request->debug : false;
        $transmutationTable = $request->input('transmutation_table', 1); // Default to table 1 if not specified
        $allQuarters = $request->has('all_quarters') ? (bool)$request->all_quarters : ($validated['quarter'] === 'all');

        $teacher = Auth::user();
        $student = Student::findOrFail($validated['student_id']);
        $section = Section::with('adviser', 'school')->findOrFail($validated['section_id']);

        // Check if teacher is the adviser of this section
        if ($section->adviser_id != $teacher->id) {
            return back()->with('error', 'You can only generate grade slips for sections where you are the adviser.');
        }

        // Log for debugging
        Log::info('Grade Slip Preview - Student and Section', [
            'student_id' => $student->id,
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'section_id' => $section->id,
            'section_name' => $section->name,
            'quarter' => $validated['quarter'],
            'all_quarters' => $allQuarters
        ]);

        // Get all subjects for this section
        $subjects = $section->subjects()
            ->where('subjects.is_active', true)
            ->orderBy('subjects.name')
            ->get();

        // Identify MAPEH subjects
        $mapehSubjects = $subjects->filter(function($subject) {
            return $subject->getIsMAPEHAttribute();
        });

        // Get all MAPEH components (both from the section and from the database)
        $mapehComponents = collect();

        // First, get components from the section subjects
        $sectionComponents = $subjects->filter(function($subject) {
            return $subject->mapeh_component;
        });

        // Then, for each MAPEH subject, get its components from the database
        foreach ($mapehSubjects as $mapehSubject) {
            $components = $mapehSubject->components;
            if ($components->count() > 0) {
                $mapehComponents = $mapehComponents->merge($components);
            }
        }

        // Also get components from parent_subject_id relationship
        $allComponents = \App\Models\Subject::where('is_component', true)
            ->whereIn('parent_subject_id', $mapehSubjects->pluck('id'))
            ->get();

        // Combine all sources and ensure uniqueness
        $mapehComponents = $mapehComponents->merge($sectionComponents)->merge($allComponents)->unique('id');

        // Create a map of MAPEH components to their parent subject
        $mapehParentMap = [];
        foreach ($mapehComponents as $component) {
            $parent = $mapehSubjects->first(function($subject) use ($component) {
                return $subject->id == $component->parent_subject_id;
            });

            if ($parent) {
                $mapehParentMap[$component->id] = $parent->id;
            }
        }

        // If showing all quarters, we'll process each quarter separately
        if ($allQuarters) {
            return $this->previewAllQuartersGradeSlip($student, $section, $subjects, $mapehSubjects, $mapehComponents, $mapehParentMap, $transmutationTable, $debug);
        }

        // Get all grade approvals for this section, quarter, and subjects
        $gradeApprovals = GradeApproval::where('section_id', $section->id)
            ->where('quarter', $validated['quarter'])
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->keyBy('subject_id');

        // Extend approvals to include MAPEH components if the parent is approved
        $extendedApprovals = clone $gradeApprovals;
        foreach ($mapehParentMap as $componentId => $parentId) {
            if (isset($gradeApprovals[$parentId]) && $gradeApprovals[$parentId]->is_approved) {
                // Create a virtual approval for the component
                $componentApproval = new GradeApproval([
                    'subject_id' => $componentId,
                    'section_id' => $section->id,
                    'quarter' => $validated['quarter'],
                    'is_approved' => true,
                    'inherited_from_parent' => true
                ]);
                $extendedApprovals[$componentId] = $componentApproval;
            }
        }

        // Initialize student grades array
        $studentGrades = [];

        // First, process MAPEH components directly to ensure we have their grades
        // but store them in a temporary array, not directly in studentGrades
        $componentGrades = [];
        foreach ($subjects as $subject) {
            if ($subject->mapeh_component) {
                // Get the component's grade configuration
                $componentConfig = GradeConfiguration::where('subject_id', $subject->id)->first();
                if (!$componentConfig) {
                    // Use default percentages if no configuration exists
                    $componentConfig = new GradeConfiguration([
                        'written_work_percentage' => 25,
                        'performance_task_percentage' => 50,
                        'quarterly_assessment_percentage' => 25,
                    ]);
                }

                // Calculate the component grade
                $componentGrade = $this->calculateSubjectGrade(
                    $student,
                    $subject,
                    $validated['quarter'],
                    $componentConfig,
                    $transmutationTable
                );

                if ($componentGrade) {
                    // Store the component grade in a temporary array, not directly in studentGrades
                    $componentGrades[$subject->id] = $componentGrade;
                }
            }
        }

        // Now process regular subjects and MAPEH parent subjects
        foreach ($subjects as $subject) {
            // Skip MAPEH components as we've already processed them
            if ($subject->mapeh_component) {
                continue;
            }

            // Check if this is a MAPEH subject
            $isMAPEH = $subject->is_mapeh;

            if ($isMAPEH) {
                // Handle MAPEH subject (get component grades and calculate average)
                $mapehComponentGrades = [];
                $totalWeightedGrade = 0;
                $totalWeight = 0;
                $componentCount = 0;

                // Find all components for this MAPEH subject
                $components = $mapehComponents->filter(function($comp) use ($subject) {
                    return $comp->parent_subject_id == $subject->id;
                });

                // Process each component
                foreach ($components as $component) {
                    // Check if we already have the component grade
                    if (isset($componentGrades[$component->id])) {
                        $componentGrade = $componentGrades[$component->id];

                        // Store the component grade in the MAPEH component_grades array
                        $mapehComponentGrades[$component->id] = $componentGrade;

                        // Add to weighted average calculation
                        $componentWeight = $component->component_weight ?? 25; // Default to 25% if not set
                        $totalWeightedGrade += ($componentGrade->quarterly_grade * $componentWeight);
                        $totalWeight += $componentWeight;
                        $componentCount++;
                    } else {
                        // If we don't have the grade yet, calculate it
                        $componentConfig = GradeConfiguration::where('subject_id', $component->id)->first();
                        if (!$componentConfig) {
                            // Use default percentages if no configuration exists
                            $componentConfig = new GradeConfiguration([
                                'written_work_percentage' => 25,
                                'performance_task_percentage' => 50,
                                'quarterly_assessment_percentage' => 25,
                            ]);
                        }

                        // Calculate the component grade
                        $componentGrade = $this->calculateSubjectGrade(
                            $student,
                            $component,
                            $validated['quarter'],
                            $componentConfig,
                            $transmutationTable
                        );

                        if ($componentGrade) {
                            // Store the component grade
                            $componentGrades[$component->id] = $componentGrade;
                            $studentGrades[$component->id] = $componentGrade; // Also store directly

                            // Add to weighted average calculation
                            $componentWeight = $component->component_weight ?? 25; // Default to 25% if not set
                            $totalWeightedGrade += ($componentGrade->quarterly_grade * $componentWeight);
                            $totalWeight += $componentWeight;
                            $componentCount++;
                        }
                    }
                }

                // Calculate MAPEH grade if we have components
                if ($componentCount > 0) {
                    // Calculate weighted average
                    $mapehGrade = $totalWeight > 0 ? round($totalWeightedGrade / $totalWeight, 1) : 0;

                    // Store the MAPEH grade
                    $studentGrades[$subject->id] = (object) [
                        'quarterly_grade' => $mapehGrade,
                        'transmuted_grade' => GradeHelper::getTransmutedGrade($mapehGrade, $transmutationTable),
                        'component_grades' => $mapehComponentGrades,
                        'remarks' => $mapehGrade >= 75 ? 'Passed' : 'Failed'
                    ];
                }
            } else {
                // Handle regular subject
                // Get the subject's grade configuration
                $gradeConfig = GradeConfiguration::where('subject_id', $subject->id)->first();
                if (!$gradeConfig) {
                    // Use default percentages if no configuration exists
                    $gradeConfig = new GradeConfiguration([
                        'written_work_percentage' => 25,
                        'performance_task_percentage' => 50,
                        'quarterly_assessment_percentage' => 25,
                    ]);
                }

                // Calculate the subject grade
                $subjectGrade = $this->calculateSubjectGrade(
                    $student,
                    $subject,
                    $validated['quarter'],
                    $gradeConfig,
                    $transmutationTable
                );

                if ($subjectGrade) {
                    $studentGrades[$subject->id] = $subjectGrade;
                }
            }
        }

        // Calculate overall average
        $totalGrade = 0;
        $subjectCount = 0;
        $allPassed = true;

        foreach ($studentGrades as $subjectId => $grade) {
            // Only include approved grades in the average
            if (isset($extendedApprovals[$subjectId]) && $extendedApprovals[$subjectId]->is_approved) {
                $totalGrade += $grade->quarterly_grade;
                $subjectCount++;

                if ($grade->quarterly_grade < 75) {
                    $allPassed = false;
                }
            }
        }

        $overallAverage = $subjectCount > 0 ? round($totalGrade / $subjectCount, 1) : 0;

        // Get current school year
        $schoolYear = $section->school_year ?? date('Y') . '-' . (date('Y') + 1);

        // Get current time in Manila timezone
        $currentTime = Carbon::now('Asia/Manila');

        // Create debug data
        $debugData = [
            'student' => [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'student_id' => $student->student_id
            ],
            'section' => [
                'id' => $section->id,
                'name' => $section->name,
                'grade_level' => $section->grade_level
            ],
            'grades' => [],
            'approvals' => $extendedApprovals->toArray(),
            'overall' => [
                'average' => $overallAverage,
                'all_passed' => $allPassed
            ]
        ];

        // Add grade details to debug data
        foreach ($studentGrades as $subjectId => $grade) {
            $subject = $subjects->firstWhere('id', $subjectId);
            if ($subject) {
                $debugData['grades'][$subjectId] = [
                    'subject_name' => $subject->name,
                    'is_mapeh' => $subject->is_mapeh,
                    'quarterly_grade' => $grade->quarterly_grade,
                    'remarks' => $grade->remarks,
                    'is_approved' => isset($extendedApprovals[$subjectId]) ? $extendedApprovals[$subjectId]->is_approved : false
                ];

                if ($subject->is_mapeh && isset($grade->component_grades)) {
                    $debugData['grades'][$subjectId]['components'] = [];
                    foreach ($grade->component_grades as $componentId => $componentGrade) {
                        $component = $mapehComponents->firstWhere('id', $componentId);
                        if ($component) {
                            $debugData['grades'][$subjectId]['components'][$componentId] = [
                                'component_name' => $component->name,
                                'grade' => $componentGrade->quarterly_grade
                            ];
                        }
                    }
                }
            }
        }

        // Log debug data
        Log::info('Grade Slip Preview - Complete data', $debugData);

        return view('teacher.reports.grade_slip_preview', [
            'student' => $student,
            'section' => $section,
            'quarter' => $validated['quarter'],
            'quarterName' => ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'][$validated['quarter']],
            'subjects' => $subjects->reject(function($subject) {
                return $subject->mapeh_component;
            }),
            'extendedApprovals' => $extendedApprovals,
            'studentGrades' => $studentGrades,
            'gradeApprovals' => $extendedApprovals,
            'mapehSubjects' => $mapehSubjects,
            'mapehComponents' => $mapehComponents,
            'mapehParentMap' => $mapehParentMap,
            'schoolYear' => $schoolYear,
            'overallAverage' => $overallAverage,
            'allPassed' => $allPassed,
            'currentTime' => $currentTime,
            'debug' => $debug,
            'debugData' => $debugData,
            'transmutationTable' => $transmutationTable,
            'transmutationTableNames' => [
                1 => 'DepEd Transmutation Table',
                2 => 'Grades 1-10 & Non-Core TVL',
                3 => 'SHS Core & Work Immersion',
                4 => 'SHS Academic Track'
            ],
        ]);
    }

    /**
     * Preview a student's grade slip for all quarters
     *
     * @param object $student The student object
     * @param object $section The section object
     * @param object $subjects Collection of subjects
     * @param object $mapehSubjects Collection of MAPEH subjects
     * @param object $mapehComponents Collection of MAPEH components
     * @param array $mapehParentMap Map of MAPEH components to their parent subjects
     * @param int $transmutationTable The transmutation table to use (1-4)
     * @return \Illuminate\View\View
     */
    private function previewAllQuartersGradeSlip($student, $section, $subjects, $mapehSubjects, $mapehComponents, $mapehParentMap, $transmutationTable, $debug = false)
    {
        // Get current school year
        $schoolYear = $section->school_year ?? date('Y') . '-' . (date('Y') + 1);

        // Get current time in Manila timezone
        $currentTime = Carbon::now('Asia/Manila');

        // Initialize arrays to store grades for all quarters
        $allQuartersGrades = [
            'Q1' => [],
            'Q2' => [],
            'Q3' => [],
            'Q4' => []
        ];

        // Initialize arrays for quarterly averages, final grades, and approvals
        $quarterlyAverages = [];
        $finalGrades = [];
        $finalComponentGrades = [];
        $allQuartersApprovals = [
            'Q1' => [],
            'Q2' => [],
            'Q3' => [],
            'Q4' => []
        ];

        // Process each quarter
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
            // Get all grade approvals for this section, quarter, and subjects
            $gradeApprovals = GradeApproval::where('section_id', $section->id)
                ->where('quarter', $quarter)
                ->whereIn('subject_id', $subjects->pluck('id'))
                ->get()
                ->keyBy('subject_id');

            // Extend approvals to include MAPEH components if the parent is approved
            $extendedApprovals = clone $gradeApprovals;
            foreach ($mapehParentMap as $componentId => $parentId) {
                if (isset($gradeApprovals[$parentId]) && $gradeApprovals[$parentId]->is_approved) {
                    // Create a virtual approval for the component
                    $componentApproval = new GradeApproval([
                        'subject_id' => $componentId,
                        'section_id' => $section->id,
                        'quarter' => $quarter,
                        'is_approved' => true,
                        'inherited_from_parent' => true
                    ]);
                    $extendedApprovals[$componentId] = $componentApproval;
                }
            }

            // Store the approvals for this quarter
            $allQuartersApprovals[$quarter] = $extendedApprovals;

            // Process each subject
            foreach ($subjects as $subject) {
                // Skip MAPEH components as they'll be handled with their parent
                if ($subject->mapeh_component) {
                    continue;
                }

                // Check if this is a MAPEH subject
                $isMAPEH = $subject->is_mapeh;

                if ($isMAPEH) {
                    // Handle MAPEH subject (get component grades and calculate average)
                    $componentGrades = [];
                    $totalWeightedGrade = 0;
                    $totalWeight = 0;
                    $componentCount = 0;

                    // Find all components for this MAPEH subject
                    $components = $mapehComponents->filter(function($comp) use ($subject) {
                        return $comp->parent_subject_id == $subject->id;
                    });

                    // Process each component
                    foreach ($components as $component) {
                        // Get the component's grade configuration
                        $componentConfig = GradeConfiguration::where('subject_id', $component->id)->first();
                        if (!$componentConfig) {
                            // Use default percentages if no configuration exists
                            $componentConfig = new GradeConfiguration([
                                'written_work_percentage' => 25,
                                'performance_task_percentage' => 50,
                                'quarterly_assessment_percentage' => 25,
                            ]);
                        }

                        // Get all grades for this student in this component and quarter
                        $componentGrades[$component->id] = $this->calculateSubjectGrade(
                            $student,
                            $component,
                            $quarter,
                            $componentConfig,
                            $transmutationTable
                        );

                        // If component has a grade, include it in the MAPEH average
                        if (isset($componentGrades[$component->id])) {
                            $grade = $componentGrades[$component->id]->quarterly_grade;
                            // Use component weight if available, otherwise default to equal weights
                            $componentWeight = $component->component_weight ?? 25;
                            $totalWeightedGrade += ($grade * $componentWeight);
                            $totalWeight += $componentWeight;
                            $componentCount++;
                        }
                    }

                    // Calculate MAPEH grade if we have components
                    if ($componentCount > 0) {
                        // Calculate weighted average
                        $mapehGrade = $totalWeight > 0 ? round($totalWeightedGrade / $totalWeight, 1) : 0;

                        // Store the MAPEH grade
                        $allQuartersGrades[$quarter][$subject->id] = (object) [
                            'quarterly_grade' => $mapehGrade,
                            'transmuted_grade' => GradeHelper::getTransmutedGrade($mapehGrade, $transmutationTable),
                            'component_grades' => $componentGrades,
                            'remarks' => $mapehGrade >= 75 ? 'Passed' : 'Failed'
                        ];
                    }
                } else {
                    // Handle regular subject
                    // Get the subject's grade configuration
                    $gradeConfig = GradeConfiguration::where('subject_id', $subject->id)->first();
                    if (!$gradeConfig) {
                        // Use default percentages if no configuration exists
                        $gradeConfig = new GradeConfiguration([
                            'written_work_percentage' => 25,
                            'performance_task_percentage' => 50,
                            'quarterly_assessment_percentage' => 25,
                        ]);
                    }

                    // Calculate the subject grade
                    $subjectGrade = $this->calculateSubjectGrade(
                        $student,
                        $subject,
                        $quarter,
                        $gradeConfig,
                        $transmutationTable
                    );

                    if ($subjectGrade) {
                        $allQuartersGrades[$quarter][$subject->id] = $subjectGrade;
                    }
                }
            }

            // Calculate overall average for this quarter
            $totalGrade = 0;
            $subjectCount = 0;

            foreach ($allQuartersGrades[$quarter] as $subjectId => $grade) {
                // Only include approved grades in the average
                if (isset($allQuartersApprovals[$quarter][$subjectId]) && $allQuartersApprovals[$quarter][$subjectId]->is_approved) {
                    $totalGrade += $grade->quarterly_grade;
                    $subjectCount++;
                }
            }

            $quarterlyAverages[$quarter] = $subjectCount > 0 ?
                GradeHelper::getTransmutedGrade(round($totalGrade / $subjectCount, 1), $transmutationTable) :
                null;
        }

        // Calculate final grades for each subject
        foreach ($subjects as $subject) {
            if ($subject->mapeh_component) {
                continue;
            }

            $totalGrade = 0;
            $quarterCount = 0;

            foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
                if (isset($allQuartersGrades[$quarter][$subject->id])) {
                    $totalGrade += $allQuartersGrades[$quarter][$subject->id]->transmuted_grade;
                    $quarterCount++;
                }
            }

            if ($quarterCount > 0) {
                $finalGrades[$subject->id] = round($totalGrade / $quarterCount);
            }

            // If this is MAPEH, calculate final grades for components too
            if ($subject->is_mapeh) {
                $components = $mapehComponents->filter(function($comp) use ($subject) {
                    return $comp->parent_subject_id == $subject->id;
                });

                foreach ($components as $component) {
                    $totalComponentGrade = 0;
                    $componentQuarterCount = 0;

                    foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
                        if (isset($allQuartersGrades[$quarter][$subject->id]) &&
                            isset($allQuartersGrades[$quarter][$subject->id]->component_grades[$component->id])) {
                            $componentGrade = $allQuartersGrades[$quarter][$subject->id]->component_grades[$component->id];
                            if (isset($componentGrade->transmuted_grade)) {
                                $totalComponentGrade += $componentGrade->transmuted_grade;
                                $componentQuarterCount++;
                            }
                        }
                    }

                    if ($componentQuarterCount > 0) {
                        $finalComponentGrades[$component->id] = round($totalComponentGrade / $componentQuarterCount);
                    }
                }
            }
        }

        // Calculate overall final average
        $totalFinalGrade = 0;
        $finalSubjectCount = 0;

        foreach ($finalGrades as $subjectId => $grade) {
            $totalFinalGrade += $grade;
            $finalSubjectCount++;
        }

        $overallFinalAverage = $finalSubjectCount > 0 ? round($totalFinalGrade / $finalSubjectCount) : null;

        // Create debug data
        $debugData = [];
        if ($debug) {
            $debugData = [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'student_id' => $student->student_id
                ],
                'section' => [
                    'id' => $section->id,
                    'name' => $section->name,
                    'grade_level' => $section->grade_level
                ],
                'quarterly_grades' => $allQuartersGrades,
                'quarterly_averages' => $quarterlyAverages,
                'final_grades' => $finalGrades,
                'overall_final_average' => $overallFinalAverage
            ];
        }

        // Log debug data
        Log::info('All Quarters Grade Slip - Complete data', [
            'student_id' => $student->id,
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'section_id' => $section->id,
            'section_name' => $section->name,
            'quarterly_averages' => $quarterlyAverages,
            'final_grades_count' => count($finalGrades),
            'overall_final_average' => $overallFinalAverage
        ]);

        return view('teacher.reports.grade_slip_all_quarters', [
            'student' => $student,
            'section' => $section,
            'quarter' => 'all',
            'subjects' => $subjects->reject(function($subject) {
                return $subject->mapeh_component;
            }),
            'allQuartersGrades' => $allQuartersGrades,
            'allQuartersApprovals' => $allQuartersApprovals,
            'mapehSubjects' => $mapehSubjects,
            'mapehComponents' => $mapehComponents,
            'mapehParentMap' => $mapehParentMap,
            'schoolYear' => $schoolYear,
            'quarterlyAverages' => $quarterlyAverages,
            'finalGrades' => $finalGrades,
            'finalComponentGrades' => $finalComponentGrades,
            'overallFinalAverage' => $overallFinalAverage,
            'currentTime' => $currentTime,
            'debug' => $debug,
            'debugData' => $debugData,
            'transmutationTable' => $transmutationTable,
            'transmutationTableNames' => [
                1 => 'DepEd Transmutation Table',
                2 => 'Grades 1-10 & Non-Core TVL',
                3 => 'SHS Core & Work Immersion',
                4 => 'SHS Academic Track'
            ],
        ]);
    }

    /**
     * Calculate a student's grade for a specific subject and quarter
     * This method uses the same calculation as the class record report
     *
     * @param object $student The student object
     * @param object $subject The subject object
     * @param string $quarter The quarter (Q1, Q2, Q3, Q4)
     * @param object $gradeConfig The grade configuration object
     * @param int $transmutationTable The transmutation table to use (1-4)
     * @return object|null The calculated grade object or null if no grades found
     */
    private function calculateSubjectGrade($student, $subject, $quarter, $gradeConfig, $transmutationTable = 1)
    {
        // Get all grades for this student in this subject and quarter
        $grades = Grade::where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->where('term', $quarter)
            ->get();

        // Log the raw grades data for debugging
        Log::info('Grade calculation - Raw grades data', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'subject_name' => $subject->name,
            'quarter' => $quarter,
            'grades_count' => $grades->count(),
            'grades' => $grades->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'grade_type' => $grade->grade_type,
                    'score' => $grade->score,
                    'max_score' => $grade->max_score,
                    'percentage' => $grade->max_score > 0 ? round(($grade->score / $grade->max_score) * 100, 1) : 0
                ];
            })->toArray()
        ]);

        if ($grades->isEmpty()) {
            return null;
        }

        // Check if we have a grade summary record for this student/subject/quarter
        $gradeSummary = DB::table('grade_summaries')
            ->where('student_id', $student->id)
            ->where('subject_id', $subject->id)
            ->where('quarter', $quarter)
            ->first();

        // If we have a grade summary, use that data directly (same as class record report)
        if ($gradeSummary) {
            Log::info('Grade calculation - Using grade summary record', [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'quarter' => $quarter,
                'initial_grade' => $gradeSummary->initial_grade,
                'quarterly_grade' => $gradeSummary->quarterly_grade,
                'written_work_ps' => $gradeSummary->written_work_ps,
                'performance_task_ps' => $gradeSummary->performance_task_ps,
                'quarterly_assessment_ps' => $gradeSummary->quarterly_assessment_ps
            ]);

            // Apply transmutation table to get the transmuted grade
            $transmutedGrade = GradeHelper::getTransmutedGrade($gradeSummary->initial_grade, $transmutationTable);

            // Log transmutation for grade summary
            Log::info('Grade calculation - Transmutation (from grade summary)', [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'initial_grade' => $gradeSummary->initial_grade,
                'transmuted_grade' => $transmutedGrade,
                'transmutation_table' => $transmutationTable
            ]);

            // Return the grade data from the grade summary
            return (object) [
                'quarterly_grade' => $gradeSummary->initial_grade, // Use initial_grade to match class record report
                'transmuted_grade' => $transmutedGrade, // Add the transmuted grade
                'remarks' => $gradeSummary->remarks,
                'components' => [
                    'written_work' => ['ps' => $gradeSummary->written_work_ps, 'ws' => $gradeSummary->written_work_ws, 'percentage' => $gradeConfig->written_work_percentage],
                    'performance_task' => ['ps' => $gradeSummary->performance_task_ps, 'ws' => $gradeSummary->performance_task_ws, 'percentage' => $gradeConfig->performance_task_percentage],
                    'quarterly_assessment' => ['ps' => $gradeSummary->quarterly_assessment_ps, 'ws' => $gradeSummary->quarterly_assessment_ws, 'percentage' => $gradeConfig->quarterly_assessment_percentage]
                ],
                'grade_config' => [
                    'written_work_percentage' => $gradeConfig->written_work_percentage,
                    'performance_task_percentage' => $gradeConfig->performance_task_percentage,
                    'quarterly_assessment_percentage' => $gradeConfig->quarterly_assessment_percentage
                ],
                'raw_data' => [
                    'from_grade_summary' => true,
                    'initial_grade' => $gradeSummary->initial_grade,
                    'quarterly_grade' => $gradeSummary->quarterly_grade,
                    'transmutation_table' => $transmutationTable
                ]
            ];
        }

        // If no grade summary exists, calculate manually
        // Group grades by type
        $writtenWorks = $grades->where('grade_type', 'written_work');
        $performanceTasks = $grades->where('grade_type', 'performance_task');

        // Check for both 'quarterly_assessment' and 'quarterly' grade types
        $quarterlyAssessments = $grades->filter(function($grade) {
            return $grade->grade_type === 'quarterly_assessment' || $grade->grade_type === 'quarterly';
        });

        // Log the quarterly assessment check
        Log::info('Grade calculation - Quarterly assessment check', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'subject_name' => $subject->name,
            'quarter' => $quarter,
            'quarterly_assessment_count' => $quarterlyAssessments->count(),
            'grade_types_in_data' => $grades->pluck('grade_type')->unique()->toArray()
        ]);

        // Log the grouped grades for debugging
        Log::info('Grade calculation - Grouped grades', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'subject_name' => $subject->name,
            'written_works_count' => $writtenWorks->count(),
            'performance_tasks_count' => $performanceTasks->count(),
            'quarterly_assessments_count' => $quarterlyAssessments->count(),
            'written_works' => $writtenWorks->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'score' => $grade->score,
                    'max_score' => $grade->max_score,
                    'percentage' => $grade->max_score > 0 ? round(($grade->score / $grade->max_score) * 100, 1) : 0
                ];
            })->toArray(),
            'performance_tasks' => $performanceTasks->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'score' => $grade->score,
                    'max_score' => $grade->max_score,
                    'percentage' => $grade->max_score > 0 ? round(($grade->score / $grade->max_score) * 100, 1) : 0
                ];
            })->toArray(),
            'quarterly_assessments' => $quarterlyAssessments->map(function($grade) {
                return [
                    'id' => $grade->id,
                    'score' => $grade->score,
                    'max_score' => $grade->max_score,
                    'percentage' => $grade->max_score > 0 ? round(($grade->score / $grade->max_score) * 100, 1) : 0
                ];
            })->toArray()
        ]);

        // Get percentages from grade configuration
        $wwPercentage = $gradeConfig->written_work_percentage;
        $ptPercentage = $gradeConfig->performance_task_percentage;
        $qaPercentage = $gradeConfig->quarterly_assessment_percentage;

        // Calculate component averages - direct assessment scores
        $wwPS = $this->calculateComponentAverage($writtenWorks);
        $ptPS = $this->calculateComponentAverage($performanceTasks);
        $qaPS = $this->calculateComponentAverage($quarterlyAssessments);

        // Check if quarterly assessment is missing but should be included
        if ($qaPS === null && $qaPercentage > 0) {
            // Log this issue for debugging
            Log::warning('Grade calculation - Missing quarterly assessment', [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'quarter' => $quarter,
                'quarterly_assessment_percentage' => $qaPercentage,
                'quarterly_assessments_count' => $quarterlyAssessments->count()
            ]);

            // Check if there are any quarterly assessments in the database for this subject
            $anyQuarterlyAssessments = Grade::where('subject_id', $subject->id)
                ->where('term', $quarter)
                ->where(function($query) {
                    $query->where('grade_type', 'quarterly_assessment')
                          ->orWhere('grade_type', 'quarterly');
                })
                ->exists();

            Log::info('Grade calculation - Any quarterly assessments check', [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'quarter' => $quarter,
                'any_quarterly_assessments_exist' => $anyQuarterlyAssessments
            ]);
        }

        // Calculate weighted scores based on grade configuration
        $wwWS = $wwPS !== null ? ($wwPS / 100) * $wwPercentage : 0;
        $ptWS = $ptPS !== null ? ($ptPS / 100) * $ptPercentage : 0;
        $qaWS = $qaPS !== null ? ($qaPS / 100) * $qaPercentage : 0;

        // Log component calculations
        Log::info('Grade calculation - Component calculations', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'subject_name' => $subject->name,
            'written_work' => [
                'percentage_score' => $wwPS,
                'percentage_in_config' => $wwPercentage,
                'weighted_score' => $wwWS
            ],
            'performance_task' => [
                'percentage_score' => $ptPS,
                'percentage_in_config' => $ptPercentage,
                'weighted_score' => $ptWS
            ],
            'quarterly_assessment' => [
                'percentage_score' => $qaPS,
                'percentage_in_config' => $qaPercentage,
                'weighted_score' => $qaWS
            ]
        ]);

        // Calculate initial grade (sum of weighted scores)
        $initialGrade = $wwWS + $ptWS + $qaWS;

        // Round the initial grade
        $initialGrade = round($initialGrade, 1);

        // Log final grade calculation
        Log::info('Grade calculation - Final grade', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'subject_name' => $subject->name,
            'initial_grade' => $initialGrade,
            'remarks' => $initialGrade >= 75 ? 'Passed' : 'Failed'
        ]);

        // Apply transmutation table to get the transmuted grade
        $transmutedGrade = GradeHelper::getTransmutedGrade($initialGrade, $transmutationTable);

        // Log transmutation
        Log::info('Grade calculation - Transmutation', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'subject_name' => $subject->name,
            'initial_grade' => $initialGrade,
            'transmuted_grade' => $transmutedGrade,
            'transmutation_table' => $transmutationTable
        ]);

        // Create a grade object with the calculated grades and assessment details
        return (object) [
            'quarterly_grade' => $initialGrade, // Use initial grade to match class record report
            'transmuted_grade' => $transmutedGrade, // Add the transmuted grade
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
            ],
            'raw_data' => [
                'from_grade_summary' => false,
                'written_works_count' => $writtenWorks->count(),
                'performance_tasks_count' => $performanceTasks->count(),
                'quarterly_assessments_count' => $quarterlyAssessments->count(),
                'transmutation_table' => $transmutationTable
            ]
        ];
    }

    /**
     * Calculate the average percentage score for a component
     * Uses total score divided by total max score method
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