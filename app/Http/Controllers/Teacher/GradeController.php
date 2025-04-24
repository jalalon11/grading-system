<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\GradeSummary;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\GradeConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $teacher = Auth::user();
            $teacherId = $teacher->id;

            Log::info('Teacher accessing grades index', [
                'teacher_id' => $teacherId,
                'name' => $teacher->name,
                'role' => $teacher->role
            ]);

            // Get subjects through the pivot table relationship
            $subjectIds = DB::table('section_subject')
                ->where('teacher_id', $teacherId)
                ->pluck('subject_id')
                ->unique()
                ->toArray();

            Log::info('Subject IDs from section_subject', [
                'teacher_id' => $teacherId,
                'subject_ids' => $subjectIds
            ]);

            // Get the teacher's transmutation table preference
            $preferredTableId = DB::table('teacher_preferences')
                ->where('teacher_id', $teacherId)
                ->where('preference_key', 'transmutation_table')
                ->value('preference_value');

            // If no preference exists yet, set DepEd Transmutation Table (1) as default
            if (!$preferredTableId) {
                $preferredTableId = 1; // DepEd Transmutation Table is now Table 1
                // Save this preference
                $this->saveTransmutationPreference($teacherId, $preferredTableId);
            }

            // Handle locking/unlocking the transmutation table
            if ($request->has('locked_transmutation_table')) {
                // Lock the transmutation table with the selected table ID
                $tableIdToLock = $request->transmutation_table ?? $preferredTableId;
                session(['locked_transmutation_table' => true]);
                session(['locked_transmutation_table_id' => $tableIdToLock]);

                Log::info('Transmutation table locked', [
                    'teacher_id' => $teacherId,
                    'table_id' => $tableIdToLock,
                    'via' => 'hidden_input'
                ]);
            } else if ($request->has('lock_table')) {
                // Lock using the checkbox
                $tableIdToLock = $request->transmutation_table ?? $preferredTableId;
                session(['locked_transmutation_table' => true]);
                session(['locked_transmutation_table_id' => $tableIdToLock]);

                Log::info('Transmutation table locked', [
                    'teacher_id' => $teacherId,
                    'table_id' => $tableIdToLock,
                    'via' => 'checkbox'
                ]);
            } else {
                // Clear lock state if no lock inputs are present
                session()->forget('locked_transmutation_table');
            }

            // Store the preferred table ID in the view data
            $preferredTableId = (int) $preferredTableId;

            // Get subjects based on section_subject pivot table
            if (!empty($subjectIds)) {
                $subjects = Subject::whereIn('id', $subjectIds)->get();

                Log::info('Subjects found via pivot table', [
                    'count' => $subjects->count(),
                    'subject_ids' => $subjects->pluck('id')->toArray(),
                    'subject_names' => $subjects->pluck('name')->toArray()
                ]);
            } else {
                // No subjects assigned in section_subject table
                $subjects = collect();

                Log::warning('No subject assignments found in section_subject table', [
                    'teacher_id' => $teacherId
                ]);
            }

            // Get sections for this teacher (as adviser or from section_subject)
            $adviserSections = Section::where('adviser_id', $teacherId)->get();

            $teacherSectionIds = DB::table('section_subject')
                ->where('teacher_id', $teacherId)
                ->pluck('section_id')
                ->unique()
                ->toArray();

            $teacherSections = Section::whereIn('id', $teacherSectionIds)->get();

            // Combine sections
            $sections = $adviserSections->merge($teacherSections)->unique('id');

            Log::info('Sections for teacher', [
                'count' => $sections->count(),
                'section_ids' => $sections->pluck('id')->toArray(),
                'section_names' => $sections->pluck('name')->toArray()
            ]);

            // If teacher is assigned as section adviser but has no subject assignments,
            // add all subjects for those sections
            if ($subjects->isEmpty() && $sections->isNotEmpty()) {
                $sectionIds = $sections->pluck('id')->toArray();

                $allSubjectIds = DB::table('section_subject')
                    ->whereIn('section_id', $sectionIds)
                    ->pluck('subject_id')
                    ->unique()
                    ->toArray();

                if (!empty($allSubjectIds)) {
                    $subjects = Subject::whereIn('id', $allSubjectIds)->get();

                    Log::info('Subjects found via advised sections', [
                        'count' => $subjects->count(),
                        'subject_ids' => $subjects->pluck('id')->toArray()
                    ]);
                }
            }

            // EMERGENCY FALLBACK: If still no subjects, get all subjects as a last resort
            if ($subjects->isEmpty()) {
                Log::warning('No subjects found for teacher', [
                    'teacher_id' => $teacherId
                ]);

                // Remove emergency fallback - security risk
                // We don't want teachers accessing subjects they're not assigned to

                // Log the access attempt
                Log::alert('Teacher attempted to access grades without subject assignments', [
                    'teacher_id' => $teacherId,
                    'name' => $teacher->name
                ]);
            }

            // Remove emergency mode session
            if (session()->has('emergency_mode')) {
                session()->forget('emergency_mode');
            }

            // Get selected subject or use first subject
            $selectedSubject = null;
            $selectedTerm = $request->term ?? 'Q1';
            $selectedSectionId = null;

            if ($request->has('subject_id')) {
                $selectedSubject = Subject::findOrFail($request->subject_id);
                $selectedSubject->load('gradeConfiguration');

                // Get the section for this teacher and subject
                if ($request->has('section_id')) {
                    $selectedSectionId = $request->section_id;
                } else {
                    // Find a section where this teacher teaches this subject
                    $sectionSubject = DB::table('section_subject')
                        ->where('teacher_id', $teacherId)
                        ->where('subject_id', $selectedSubject->id)
                        ->first();

                    if ($sectionSubject) {
                        $selectedSectionId = $sectionSubject->section_id;
                    } elseif ($sections->isNotEmpty()) {
                        $selectedSectionId = $sections->first()->id;
                    }
                }
            } elseif ($subjects->count() > 0) {
                $selectedSubject = $subjects->first();
                $selectedSubject->load('gradeConfiguration');

                // Find a section where this teacher teaches this subject
                $sectionSubject = DB::table('section_subject')
                    ->where('teacher_id', $teacherId)
                    ->where('subject_id', $selectedSubject->id)
                    ->first();

                if ($sectionSubject) {
                    $selectedSectionId = $sectionSubject->section_id;
                } elseif ($sections->isNotEmpty()) {
                    $selectedSectionId = $sections->first()->id;
                }
            }

            if ($selectedSubject) {
                Log::info('Selected subject', [
                    'subject_id' => $selectedSubject->id,
                    'subject_name' => $selectedSubject->name,
                    'section_id' => $selectedSectionId
                ]);
            }

            // Get or create grade configuration for the selected subject
            if ($selectedSubject && !$selectedSubject->gradeConfiguration) {
                $gradeConfig = GradeConfiguration::create([
                    'subject_id' => $selectedSubject->id,
                    'written_work_percentage' => 25.00,
                    'performance_task_percentage' => 50.00,
                    'quarterly_assessment_percentage' => 25.00,
                ]);
                $selectedSubject->load('gradeConfiguration');
                Log::info('Created grade configuration', ['subject_id' => $selectedSubject->id]);
            }

            // Get students and their grades for the selected subject and section
            $students = [];
            if ($selectedSubject && $selectedSectionId) {
                $viewAll = $request->has('view_all') && $request->view_all == 'true';

                if ($viewAll) {
                    // Get all subjects assigned to this section
                    $sectionSubjectIds = DB::table('section_subject')
                        ->where('section_id', $selectedSectionId)
                        ->pluck('subject_id')
                        ->unique()
                        ->toArray();

                    $sectionStudents = Student::where('section_id', $selectedSectionId)
                        ->with(['grades' => function ($query) use ($sectionSubjectIds, $selectedTerm) {
                            $query->whereIn('subject_id', $sectionSubjectIds)
                                  ->where('term', $selectedTerm)
                                  ->with('subject'); // Eager load the subject relation
                        }])
                        ->get();

                    foreach ($sectionStudents as $student) {
                        // Group grades by subject
                        $subjectGrades = [];

                        foreach ($sectionSubjectIds as $subjectId) {
                            $subjectName = Subject::find($subjectId)->name ?? "Unknown Subject";
                            $subjectGrades[$subjectId] = [
                                'subject_name' => $subjectName,
                                'written_works' => $student->grades->where('subject_id', $subjectId)->where('grade_type', 'written_work')->all(),
                                'performance_tasks' => $student->grades->where('subject_id', $subjectId)->where('grade_type', 'performance_task')->all(),
                                'quarterly_assessment' => $student->grades->where('subject_id', $subjectId)->where('grade_type', 'quarterly')->first(),
                            ];
                        }

                        $students[] = [
                            'student' => $student,
                            'view_all' => true,
                            'subject_grades' => $subjectGrades,
                        ];
                    }
                } else {
                    // Original code for viewing a single subject
                    $sectionStudents = Student::where('section_id', $selectedSectionId)
                        ->with(['grades' => function ($query) use ($selectedSubject, $selectedTerm) {
                            $query->where('subject_id', $selectedSubject->id)
                                  ->where('term', $selectedTerm);
                        }])
                        ->get();

                    Log::info('Students found in section', [
                        'section_id' => $selectedSectionId,
                        'count' => $sectionStudents->count()
                    ]);

                    foreach ($sectionStudents as $student) {
                        $writtenWorks = $student->grades->where('grade_type', 'written_work')->all();
                        $performanceTasks = $student->grades->where('grade_type', 'performance_task')->all();
                        $quarterlyAssessment = $student->grades->where('grade_type', 'quarterly')->first();

                        // Check if this is a MAPEH subject with components
                        $isMAPEH = false;
                        $mapehComponents = [];

                        if (isset($selectedSubject->components) && $selectedSubject->components->count() > 0) {
                            $componentNames = $selectedSubject->components->pluck('name')->map(fn($name) => strtolower($name))->toArray();
                            $requiredComponents = ['music', 'arts', 'physical education', 'health'];

                            $matchedComponents = 0;
                            foreach ($requiredComponents as $component) {
                                if (in_array($component, $componentNames) ||
                                    in_array(strtolower(substr($component, 0, 5)), $componentNames)) {
                                    $matchedComponents++;
                                }
                            }

                            $isMAPEH = $matchedComponents == 4;

                            // If MAPEH, load component grades
                            if ($isMAPEH) {
                                $componentSubjectIds = $selectedSubject->components->pluck('id')->toArray();

                                // Fetch component grades for this student
                                $componentGrades = Grade::where('student_id', $student->id)
                                    ->whereIn('subject_id', $componentSubjectIds)
                                    ->where('term', $selectedTerm)
                                    ->get();

                                // Group by component subject and grade type
                                foreach ($selectedSubject->components as $component) {
                                    $componentWrittenWorks = $componentGrades
                                        ->where('subject_id', $component->id)
                                        ->where('grade_type', 'written_work')
                                        ->all();

                                    $componentPerformanceTasks = $componentGrades
                                        ->where('subject_id', $component->id)
                                        ->where('grade_type', 'performance_task')
                                        ->all();

                                    $componentQuarterlyAssessment = $componentGrades
                                        ->where('subject_id', $component->id)
                                        ->where('grade_type', 'quarterly')
                                        ->first();

                                    $mapehComponents[$component->id] = [
                                        'component' => $component,
                                        'written_works' => $componentWrittenWorks,
                                        'performance_tasks' => $componentPerformanceTasks,
                                        'quarterly_assessment' => $componentQuarterlyAssessment,
                                    ];
                                }

                                // Add component written works to the main written works if not already there
                                foreach ($mapehComponents as $componentData) {
                                    foreach ($componentData['written_works'] as $work) {
                                        $writtenWorks[] = $work;
                                    }
                                    foreach ($componentData['performance_tasks'] as $task) {
                                        $performanceTasks[] = $task;
                                    }
                                    // For quarterly assessment, only use if main one is not set
                                    if (!$quarterlyAssessment && $componentData['quarterly_assessment']) {
                                        $quarterlyAssessment = $componentData['quarterly_assessment'];
                                    }
                                }
                            }
                        }

                        $students[] = [
                            'student' => $student,
                            'written_works' => $writtenWorks,
                            'performance_tasks' => $performanceTasks,
                            'quarterly_assessment' => $quarterlyAssessment,
                            'view_all' => false,
                            'is_mapeh' => $isMAPEH,
                            'mapeh_components' => $mapehComponents,
                        ];
                    }
                }
            }

            $terms = ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'];

            return view('teacher.grades.index', compact('subjects', 'selectedSubject', 'students', 'terms', 'selectedTerm', 'sections', 'selectedSectionId', 'preferredTableId'));

        } catch (\Exception $e) {
            Log::error('Error in grades index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('teacher.grades.index', [
                    'subjects' => collect(),
                    'students' => [],
                    'terms' => ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'],
                    'selectedTerm' => 'Q1',
                    'sections' => collect(),
                ])
                ->with('error', 'Error loading grades. Please try again or contact administrator.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $teacher = Auth::user();
            Log::info('Teacher accessing grade creation form', ['teacher_id' => $teacher->id, 'name' => $teacher->name]);

            // Get sections where teacher is adviser or teaches a subject
            $sections = Section::where('adviser_id', $teacher->id)
                ->orWhereHas('subjects', function($query) use ($teacher) {
                    $query->where('section_subject.teacher_id', $teacher->id);
                })
                ->get();

            Log::info('Sections retrieved', [
                'count' => $sections->count(),
                'section_ids' => $sections->pluck('id')->toArray(),
                'section_names' => $sections->pluck('name')->toArray()
            ]);

            // Get the section ID from request or use the first section
            $sectionId = $request->section_id;

            if (!$sectionId && $sections->count() > 0) {
                $sectionId = $sections->first()->id;
            }

            Log::info('Section ID selected', ['section_id' => $sectionId]);

            // Get subjects for this teacher and section directly from section_subject
            $subjects = [];
            if ($sectionId) {
                // Direct query from section_subject table
                $subjectIds = DB::table('section_subject')
                    ->where('section_id', $sectionId)
                    ->where('teacher_id', $teacher->id)
                    ->pluck('subject_id')
                    ->toArray();

                if (!empty($subjectIds)) {
                    $subjects = Subject::whereIn('id', $subjectIds)->get();
                } else {
                    // If teacher is section adviser, get all subjects for the section
                    $isAdviser = Section::where('id', $sectionId)
                        ->where('adviser_id', $teacher->id)
                        ->exists();

                    if ($isAdviser) {
                        $subjectIds = DB::table('section_subject')
                            ->where('section_id', $sectionId)
                            ->pluck('subject_id')
                            ->toArray();

                        $subjects = Subject::whereIn('id', $subjectIds)->get();
                    }
                }

                Log::info('Subjects retrieved for section', [
                    'section_id' => $sectionId,
                    'count' => count($subjects),
                    'subject_ids' => collect($subjects)->pluck('id')->toArray(),
                    'subject_names' => collect($subjects)->pluck('name')->toArray()
                ]);

                // Direct DB query to check section_subject entries
                $sectionSubjectEntries = DB::table('section_subject')
                    ->where('section_id', $sectionId)
                    ->where('teacher_id', $teacher->id)
                    ->get();

                Log::info('Section-Subject pivot entries', [
                    'count' => $sectionSubjectEntries->count(),
                    'entries' => $sectionSubjectEntries->toArray()
                ]);
            }

            // Get students for the selected section
            $students = [];
            if ($sectionId) {
                $students = Student::where('section_id', $sectionId)->get();

                Log::info('Students retrieved for section', [
                    'section_id' => $sectionId,
                    'count' => count($students),
                    'student_ids' => collect($students)->pluck('id')->toArray()
                ]);
            }

            $terms = ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'];
            $gradeTypes = [
                'written_work' => 'Written Work',
                'performance_task' => 'Performance Task',
                'quarterly' => 'Quarterly Exam',
                'quarterly_exam' => 'Quarterly Exam'
            ];

            return view('teacher.grades.create', compact(
                'sections',
                'subjects',
                'students',
                'terms',
                'gradeTypes',
                'sectionId'
            ));

        } catch (\Exception $e) {
            Log::error('Error in grades create: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('teacher.grades.index')
                ->with('error', 'Error loading grade creation form. Please contact administrator.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'term' => 'required|in:Q1,Q2,Q3,Q4',
            'grade_type' => 'required|in:written_work,performance_task,quarterly',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:255',
            'section_id' => 'required|exists:sections,id',
        ]);

        try {
            $teacher = Auth::id();
            Log::info('Teacher recording grade', [
                'teacher_id' => $teacher,
                'subject_id' => $request->subject_id,
                'section_id' => $request->section_id
            ]);

            // Check if the teacher teaches this subject in this section
            $subjectExists = DB::table('section_subject')
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $teacher)
                ->exists();

            // If no explicit assignment, check if teacher is adviser for this section
            if (!$subjectExists) {
                $isAdviser = DB::table('sections')
                    ->where('id', $request->section_id)
                    ->where('adviser_id', $teacher)
                    ->exists();

                Log::info('Teacher access check', [
                    'section_subject_exists' => $subjectExists,
                    'is_section_adviser' => $isAdviser
                ]);

                if (!$isAdviser) {
                    return redirect()->back()->with('error', 'You are not authorized to record grades for this subject in this section.');
                }
            }

            // Check if the student belongs to the section
            $student = Student::where('id', $request->student_id)
                ->where('section_id', $request->section_id)
                ->firstOrFail();

            // Create the grade
            $grade = Grade::create([
                'student_id' => $student->id,
                'subject_id' => $request->subject_id,
                'term' => $request->term,
                'grade_type' => $request->grade_type,
                'assessment_name' => $request->assessment_name,
                'score' => $request->score,
                'max_score' => $request->max_score,
                'remarks' => $request->remarks,
            ]);

            Log::info('Grade recorded successfully', [
                'grade_id' => $grade->id,
                'student_id' => $student->id,
                'subject_id' => $request->subject_id
            ]);

            return redirect()->route('teacher.grades.index', [
                    'subject_id' => $request->subject_id,
                    'section_id' => $request->section_id,
                    'term' => $request->term
                ])
                ->with('success', 'Grade recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Error storing grade: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Error recording grade: ' . $e->getMessage());
        }
    }

    /**
     * Show batch grade form
     */
    public function batchCreate(Request $request)
    {
        try {
            $teacher = Auth::id();
            Log::info('Starting batch grade creation', [
                'teacher_id' => $teacher,
                'request' => $request->all()
            ]);

            // Load the subject
            $subject = Subject::with('components')->findOrFail($request->subject_id);

            // Check if this is a MAPEH subject
            $isMAPEH = $subject->components()->count() >= 4;

            // Log MAPEH-related data for debugging
            if ($isMAPEH) {
                Log::info('MAPEH subject detected', [
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->name,
                    'component_count' => $subject->components->count(),
                    'components' => $subject->components->pluck('name', 'id')->toArray(),
                    'session_data' => [
                        'is_mapeh' => session('is_mapeh', false),
                        'selected_components' => session('selected_components', []),
                        'component_max_score' => session('component_max_score', [])
                    ]
                ]);
            }

            // Define assessment limits
            $assessmentLimits = [
                'written_work' => 7,
                'performance_task' => 8,
                'quarterly' => 1
            ];

            // Check assessment count limits - this prevents backdoor access to batch grade entry
            if ($isMAPEH) {
                // Verify that this is a MAPEH assessment from the setup
                if (!session('is_mapeh') || empty(session('selected_components')) || empty(session('component_max_score'))) {
                    Log::warning('Missing MAPEH session data', [
                        'is_mapeh' => session('is_mapeh', false),
                        'selected_components' => session('selected_components', []),
                        'component_max_score' => session('component_max_score', [])
                    ]);

                    // Redirect back to setup if MAPEH data is missing
                    return redirect()->route('teacher.grades.assessment-setup', [
                        'subject_id' => $request->subject_id,
                        'term' => $request->term,
                        'grade_type' => $request->grade_type,
                        'section_id' => $request->section_id
                    ])->with('error', 'MAPEH component data is missing. Please set up your assessment again.');
                }

                // Check component assessment counts to prevent backdoor access
                foreach (session('selected_components', []) as $componentId) {
                    $componentCount = Grade::where('subject_id', $componentId)
                        ->where('term', $request->term)
                        ->where('grade_type', $request->grade_type)
                        ->distinct('assessment_name')
                        ->count('assessment_name');

                    if (isset($assessmentLimits[$request->grade_type]) && $componentCount >= $assessmentLimits[$request->grade_type]) {
                        $component = Subject::find($componentId);
                        $gradeTypes = [
                            'written_work' => 'Written Work',
                            'performance_task' => 'Performance Task',
                            'quarterly' => 'Quarterly Assessment'
                        ];

                        Log::warning('Attempted to bypass assessment limit', [
                            'teacher_id' => $teacher,
                            'subject_id' => $request->subject_id,
                            'component_id' => $componentId,
                            'component_name' => $component->name,
                            'grade_type' => $request->grade_type,
                            'current_count' => $componentCount,
                            'max_allowed' => $assessmentLimits[$request->grade_type]
                        ]);

                        return redirect()->route('teacher.grades.index', [
                            'subject_id' => $request->subject_id,
                            'term' => $request->term
                        ])->with('error', "Maximum number of {$assessmentLimits[$request->grade_type]} assessments for {$gradeTypes[$request->grade_type]} has been reached for {$component->name}. Cannot create more assessments.");
                    }
                }
            } else {
                // Check regular subject assessment count to prevent backdoor access
                $assessmentCount = Grade::where('subject_id', $request->subject_id)
                    ->where('term', $request->term)
                    ->where('grade_type', $request->grade_type)
                    ->distinct('assessment_name')
                    ->count('assessment_name');

                if (isset($assessmentLimits[$request->grade_type]) && $assessmentCount >= $assessmentLimits[$request->grade_type]) {
                    $gradeTypes = [
                        'written_work' => 'Written Work',
                        'performance_task' => 'Performance Task',
                        'quarterly' => 'Quarterly Assessment'
                    ];

                    Log::warning('Attempted to bypass assessment limit', [
                        'teacher_id' => $teacher,
                        'subject_id' => $request->subject_id,
                        'grade_type' => $request->grade_type,
                        'current_count' => $assessmentCount,
                        'max_allowed' => $assessmentLimits[$request->grade_type]
                    ]);

                    return redirect()->route('teacher.grades.index', [
                        'subject_id' => $request->subject_id,
                        'term' => $request->term
                    ])->with('error', "Maximum number of {$assessmentLimits[$request->grade_type]} assessments for {$gradeTypes[$request->grade_type]} has been reached. Cannot create more assessments.");
                }
            }

            Log::info('Assessment info', [
                'subject_id' => $request->subject_id,
                'term' => $request->term,
                'grade_type' => $request->grade_type,
                'section_id' => $request->section_id,
                'assessment_name' => session('assessment_name'),
                'is_mapeh' => $isMAPEH,
                'max_score' => $isMAPEH ? 'Using component scores' : session('max_score')
            ]);

            // Check if the teacher teaches this subject in this section
            $subjectExists = DB::table('section_subject')
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $teacher)
                ->exists();

            Log::info('Section-Subject relationship check', [
                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $teacher,
                'exists' => $subjectExists
            ]);

            if (!$subjectExists) {
                // Check if the teacher is an adviser for this section as a fallback
                $isAdviser = Section::where('id', $request->section_id)
                    ->where('adviser_id', $teacher)
                    ->exists();

                Log::info('Teacher adviser check', [
                    'section_id' => $request->section_id,
                    'teacher_id' => $teacher,
                    'is_adviser' => $isAdviser
                ]);

                if (!$isAdviser) {
                    return redirect()->back()->with('error', 'You are not authorized to record grades for this subject in this section.');
                }
            }

            // Make sure we have assessment parameters
            if (!session('assessment_name')) {
                Log::warning('Missing assessment name in session', [
                    'assessment_name' => session('assessment_name')
                ]);

                return redirect()->route('teacher.grades.assessment-setup', [
                    'subject_id' => $request->subject_id,
                    'term' => $request->term,
                    'grade_type' => $request->grade_type,
                    'section_id' => $request->section_id
                ])->with('error', 'Assessment name is missing. Please set up the assessment again.');
            }

            // For MAPEH subjects, check if we have the selected components
            if ($isMAPEH) {
                if (!session('selected_components') || !session('component_max_score')) {
                    Log::warning('Missing MAPEH component parameters in session', [
                        'selected_components' => session('selected_components'),
                        'component_max_score' => session('component_max_score')
                    ]);

                    return redirect()->route('teacher.grades.assessment-setup', [
                        'subject_id' => $request->subject_id,
                        'term' => $request->term,
                        'grade_type' => $request->grade_type,
                        'section_id' => $request->section_id
                    ])->with('error', 'MAPEH component parameters are missing. Please set up the assessment again.');
                }

                // Verify all selected components exist in the database
                $componentCount = Subject::whereIn('id', session('selected_components'))->count();
                if ($componentCount != count(session('selected_components'))) {
                    Log::warning('Some selected MAPEH components not found', [
                        'expected' => count(session('selected_components')),
                        'found' => $componentCount
                    ]);

                    return redirect()->route('teacher.grades.assessment-setup', [
                        'subject_id' => $request->subject_id,
                        'term' => $request->term,
                        'grade_type' => $request->grade_type,
                        'section_id' => $request->section_id
                    ])->with('error', 'Some MAPEH components are invalid. Please set up the assessment again.');
                }
            } else {
                // For regular subjects, check if we have the max score
                if (!session('max_score')) {
                    Log::warning('Missing max score in session', [
                        'max_score' => session('max_score')
                    ]);

                    return redirect()->route('teacher.grades.assessment-setup', [
                        'subject_id' => $request->subject_id,
                        'term' => $request->term,
                        'grade_type' => $request->grade_type,
                        'section_id' => $request->section_id
                    ])->with('error', 'Maximum score is missing. Please set up the assessment again.');
                }
            }

            // Load the section
            $section = Section::findOrFail($request->section_id);

            // Get students for the section
            $students = Student::where('section_id', $request->section_id)->get();

            Log::info('Students retrieved for section', [
                'section_id' => $request->section_id,
                'count' => $students->count(),
                'student_ids' => $students->pluck('id')->toArray()
            ]);

            if ($students->isEmpty()) {
                Log::warning('No students found in section', [
                    'section_id' => $request->section_id
                ]);
            }

            $gradeTypes = [
                'written_work' => 'Written Work',
                'performance_task' => 'Performance Task',
                'quarterly' => 'Quarterly Assessment'
            ];

            $terms = ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'];

            return view('teacher.grades.batch', compact(
                'subject',
                'section',
                'students',
                'request',
                'gradeTypes',
                'terms',
                'isMAPEH'
            ));
        } catch (\Exception $e) {
            Log::error('Error in batch grade creation: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return redirect()->route('teacher.grades.index', [
                'subject_id' => $request->subject_id,
                'term' => $request->term
            ])->with('error', 'Error loading batch grade form: ' . $e->getMessage());
        }
    }

    /**
     * Store batch grades
     */
    public function batchStore(Request $request)
    {
        // Check if this is a MAPEH assessment with components
        $isMAPEH = $request->has('is_mapeh') && $request->is_mapeh == 1;

        if ($isMAPEH) {
            // Validate MAPEH component batch
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'section_id' => 'required|exists:sections,id',
                'term' => 'required|in:Q1,Q2,Q3,Q4',
                'grade_type' => 'required|in:written_work,performance_task,quarterly,quarterly_exam',
                'assessment_name' => 'required|string|max:255',
                'component_ids' => 'required|array',
                'component_ids.*' => 'required|exists:subjects,id',
                'component_max_scores' => 'required|array',
                'component_scores' => 'required|array',
                'student_ids' => 'required|array',
                'student_ids.*' => 'required|exists:students,id',
            ]);
        } else {
            // Validate regular subject batch
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'section_id' => 'required|exists:sections,id',
                'term' => 'required|in:Q1,Q2,Q3,Q4',
                'grade_type' => 'required|in:written_work,performance_task,quarterly,quarterly_exam',
                'max_score' => 'required|numeric|min:1',
                'assessment_name' => 'required|string|max:255',
                'scores' => 'required|array',
                'scores.*' => 'required|numeric|min:0',
                'student_ids' => 'required|array',
                'student_ids.*' => 'required|exists:students,id',
            ]);
        }

        try {
            $teacher = Auth::id();
            Log::info('Batch storing grades', [
                'teacher_id' => $teacher,
                'subject_id' => $request->subject_id,
                'section_id' => $request->section_id,
                'assessment_name' => $request->assessment_name,
                'is_mapeh' => $isMAPEH
            ]);

            // Define assessment limits
            $assessmentLimits = [
                'written_work' => 7,
                'performance_task' => 8,
                'quarterly' => 1
            ];

            // Double-check assessment count limits to prevent backdoor access
            if ($isMAPEH) {
                // Check component assessment counts
                foreach ($request->component_ids as $componentId) {
                    $componentCount = Grade::where('subject_id', $componentId)
                        ->where('term', $request->term)
                        ->where('grade_type', $request->grade_type)
                        ->distinct('assessment_name')
                        ->count('assessment_name');

                    if (isset($assessmentLimits[$request->grade_type]) && $componentCount >= $assessmentLimits[$request->grade_type]) {
                        $component = Subject::find($componentId);
                        $gradeTypes = [
                            'written_work' => 'Written Work',
                            'performance_task' => 'Performance Task',
                            'quarterly' => 'Quarterly Assessment'
                        ];

                        Log::warning('Attempted to bypass assessment limit in batch store', [
                            'teacher_id' => $teacher,
                            'subject_id' => $request->subject_id,
                            'component_id' => $componentId,
                            'component_name' => $component->name,
                            'grade_type' => $request->grade_type,
                            'current_count' => $componentCount,
                            'max_allowed' => $assessmentLimits[$request->grade_type]
                        ]);

                        return redirect()->route('teacher.grades.index', [
                            'subject_id' => $request->subject_id,
                            'term' => $request->term
                        ])->with('error', "Security violation: Maximum number of {$assessmentLimits[$request->grade_type]} assessments for {$gradeTypes[$request->grade_type]} has been reached for {$component->name}.");
                    }
                }
            } else {
                // Check regular subject assessment count
                $assessmentCount = Grade::where('subject_id', $request->subject_id)
                    ->where('term', $request->term)
                    ->where('grade_type', $request->grade_type)
                    ->distinct('assessment_name')
                    ->count('assessment_name');

                if (isset($assessmentLimits[$request->grade_type]) && $assessmentCount >= $assessmentLimits[$request->grade_type]) {
                    $gradeTypes = [
                        'written_work' => 'Written Work',
                        'performance_task' => 'Performance Task',
                        'quarterly' => 'Quarterly Assessment'
                    ];

                    Log::warning('Attempted to bypass assessment limit in batch store', [
                        'teacher_id' => $teacher,
                        'subject_id' => $request->subject_id,
                        'grade_type' => $request->grade_type,
                        'current_count' => $assessmentCount,
                        'max_allowed' => $assessmentLimits[$request->grade_type]
                    ]);

                    return redirect()->route('teacher.grades.index', [
                        'subject_id' => $request->subject_id,
                        'term' => $request->term
                    ])->with('error', "Security violation: Maximum number of {$assessmentLimits[$request->grade_type]} assessments for {$gradeTypes[$request->grade_type]} has been reached.");
                }
            }

            // Check if the teacher teaches this subject in this section
            $subjectExists = DB::table('section_subject')
                ->where('section_id', $request->section_id)
                ->where('subject_id', $request->subject_id)
                ->where('teacher_id', $teacher)
                ->exists();

            // If no explicit assignment, check if teacher is adviser for this section
            if (!$subjectExists) {
                $isAdviser = DB::table('sections')
                    ->where('id', $request->section_id)
                    ->where('adviser_id', $teacher)
                    ->exists();

                Log::info('Teacher access check for batch grades', [
                    'section_subject_exists' => $subjectExists,
                    'is_section_adviser' => $isAdviser
                ]);

                if (!$isAdviser) {
                    return redirect()->back()->with('error', 'You are not authorized to record grades for this subject in this section.');
                }
            }

            if ($isMAPEH) {
                // Process MAPEH component grades
                $gradesAdded = 0;

                // Get the components from the form
                $componentIds = $request->component_ids;
                $componentMaxScores = $request->component_max_scores;
                $componentScores = $request->component_scores;

                Log::info('MAPEH grading data received', [
                    'component_ids' => $componentIds,
                    'component_max_scores' => $componentMaxScores,
                    'component_scores_structure' => array_keys($componentScores),
                    'student_ids' => $request->student_ids
                ]);

                // For each component
                foreach ($componentIds as $componentId) {
                    if (!isset($componentMaxScores[$componentId]) || !isset($componentScores[$componentId])) {
                        Log::warning("Missing component data for component $componentId", [
                            'has_max_score' => isset($componentMaxScores[$componentId]),
                            'has_component_scores' => isset($componentScores[$componentId])
                        ]);
                        continue;
                    }

                    $maxScore = $componentMaxScores[$componentId];
                    Log::info("Processing component $componentId with max score $maxScore");

                    // For each student
                    foreach ($request->student_ids as $studentId) {
                        // Check if a score was provided for this student and component
                        if (isset($componentScores[$componentId][$studentId])) {
                            $score = $componentScores[$componentId][$studentId];

                            // Validate the score against max score
                            if ($score < 0 || $score > $maxScore) {
                                Log::warning("Invalid score for student $studentId in component $componentId: $score (max: $maxScore)");
                                continue; // Skip invalid scores
                            }

                            // Create grade for the specific component
                            Grade::create([
                                'student_id' => $studentId,
                                'subject_id' => $componentId, // Component subject ID
                                'term' => $request->term,
                                'grade_type' => $request->grade_type,
                                'score' => $score,
                                'max_score' => $maxScore,
                                'assessment_name' => $request->assessment_name,
                                'remarks' => isset($request->remarks[$studentId]) ? $request->remarks[$studentId] : null
                            ]);

                            $gradesAdded++;
                            Log::info("Grade added for student $studentId in component $componentId: $score/$maxScore");
                        } else {
                            Log::warning("No score found for student $studentId in component $componentId");
                        }
                    }
                }

                if ($gradesAdded === 0) {
                    Log::error('No grades were saved for MAPEH components', [
                        'component_ids' => $componentIds,
                        'student_ids' => $request->student_ids
                    ]);
                    return redirect()->back()->with('error', 'No grades were saved. Please check your inputs and try again.');
                }

                return redirect()->route('teacher.grades.index', [
                    'subject_id' => $request->subject_id,
                    'term' => $request->term
                ])->with('success', $gradesAdded . ' MAPEH component grades have been recorded successfully.');
            } else {
                // Process regular subject grades
                $gradesAdded = 0;
                foreach ($request->student_ids as $index => $studentId) {
                    // Check if the student belongs to the section
                    $student = Student::where('id', $studentId)
                        ->where('section_id', $request->section_id)
                        ->firstOrFail();

                    // Only create grade if score is provided
                    if (isset($request->scores[$index])) {
                        Grade::create([
                            'student_id' => $student->id,
                            'subject_id' => $request->subject_id,
                            'term' => $request->term,
                            'grade_type' => $request->grade_type,
                            'score' => $request->scores[$index],
                            'max_score' => $request->max_score,
                            'assessment_name' => $request->assessment_name,
                            'remarks' => isset($request->remarks[$index]) ? $request->remarks[$index] : null
                        ]);
                        $gradesAdded++;
                    }
                }

                Log::info('Grades added successfully', [
                    'count' => $gradesAdded
                ]);

                return redirect()->route('teacher.grades.index', [
                    'subject_id' => $request->subject_id,
                    'term' => $request->term
                ])->with('success', $gradesAdded . ' grades have been recorded successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error in batch store: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);

            return redirect()->back()->with('error', 'An error occurred while saving grades: ' . $e->getMessage());
        }
    }

    /**
     * Edit a grade
     */
    public function edit(string $id)
    {
        $grade = Grade::findOrFail($id);
        $teacherId = Auth::id();

        // Verify that the grade is for a subject taught by the teacher through section_subject relationship
        $authorized = DB::table('section_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $grade->subject_id)
            ->exists();

        // Check if teacher is section adviser
        if (!$authorized) {
            $student = Student::findOrFail($grade->student_id);
            $authorized = Section::where('id', $student->section_id)
                ->where('adviser_id', $teacherId)
                ->exists();
        }

        if (!$authorized) {
            Log::alert('Unauthorized grade edit attempt', [
                'teacher_id' => $teacherId,
                'grade_id' => $id,
                'subject_id' => $grade->subject_id
            ]);

            abort(403, 'You are not authorized to edit grades for this subject.');
        }

        $subject = Subject::findOrFail($grade->subject_id);

        $gradeTypes = [
            'written_work' => 'Written Work',
            'performance_task' => 'Performance Task',
            'quarterly' => 'Quarterly Assessment'
        ];

        $terms = ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'];

        return view('teacher.grades.edit', compact('grade', 'subject', 'gradeTypes', 'terms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $grade = Grade::findOrFail($id);
        $teacherId = Auth::id();

        // Verify that the grade is for a subject taught by the teacher through section_subject relationship
        $authorized = DB::table('section_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $grade->subject_id)
            ->exists();

        // Check if teacher is section adviser
        if (!$authorized) {
            $student = Student::findOrFail($grade->student_id);
            $authorized = Section::where('id', $student->section_id)
                ->where('adviser_id', $teacherId)
                ->exists();
        }

        if (!$authorized) {
            Log::alert('Unauthorized grade update attempt', [
                'teacher_id' => $teacherId,
                'grade_id' => $id,
                'subject_id' => $grade->subject_id
            ]);

            abort(403, 'You are not authorized to update grades for this subject.');
        }

        $request->validate([
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:255',
        ]);

        $grade->update([
            'score' => $request->score,
            'max_score' => $request->max_score,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('teacher.grades.index', [
                'subject_id' => $grade->subject_id,
                'term' => $grade->term
            ])
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $teacherId = Auth::id();

        // Verify that the grade is for a subject taught by the teacher through section_subject relationship
        $authorized = DB::table('section_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $grade->subject_id)
            ->exists();

        // Check if teacher is section adviser
        if (!$authorized) {
            $student = Student::findOrFail($grade->student_id);
            $authorized = Section::where('id', $student->section_id)
                ->where('adviser_id', $teacherId)
                ->exists();
        }

        if (!$authorized) {
            Log::alert('Unauthorized grade deletion attempt', [
                'teacher_id' => $teacherId,
                'grade_id' => $id,
                'subject_id' => $grade->subject_id
            ]);

            abort(403, 'You are not authorized to delete grades for this subject.');
        }

        $term = $grade->term;
        $subjectId = $grade->subject_id;

        $grade->delete();

        return redirect()->route('teacher.grades.index', [
                'subject_id' => $subjectId,
                'term' => $term
            ])
            ->with('success', 'Grade deleted successfully.');
    }

    /**
     * Show form to set up assessment parameters before batch grading
     */
    public function assessmentSetup(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'term' => 'required|in:Q1,Q2,Q3,Q4',
            'grade_type' => 'required|in:written_work,performance_task,quarterly',
            'section_id' => 'required|exists:sections,id',
        ]);

        $teacherId = Auth::id();

        // Check if the teacher is authorized to access this subject via section_subject
        $authorized = DB::table('section_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $request->subject_id)
            ->where('section_id', $request->section_id)
            ->exists();

        // If not found in section_subject, check if teacher is the adviser
        if (!$authorized) {
            $authorized = Section::where('id', $request->section_id)
                ->where('adviser_id', $teacherId)
                ->exists();
        }

        if (!$authorized) {
            Log::alert('Unauthorized assessment setup attempt', [
                'teacher_id' => $teacherId,
                'subject_id' => $request->subject_id,
                'section_id' => $request->section_id
            ]);

            abort(403, 'You are not authorized to set up assessments for this subject in this section.');
        }

        $subject = Subject::findOrFail($request->subject_id);
        $section = Section::findOrFail($request->section_id);
        $term = $request->term;
        $gradeType = $request->grade_type;

        // Check assessment count limits
        $assessmentCount = Grade::where('subject_id', $request->subject_id)
            ->where('term', $request->term)
            ->where('grade_type', $request->grade_type)
            ->distinct('assessment_name')
            ->count('assessment_name');

        // Define assessment limits
        $assessmentLimits = [
            'written_work' => 7,
            'performance_task' => 8,
            'quarterly' => 1
        ];

        $gradeTypes = [
            'written_work' => 'Written Work',
            'performance_task' => 'Performance Task',
            'quarterly' => 'Quarterly Assessment'
        ];

        // We'll check the limit but not prevent access to the setup page
        // Just pass the assessment count and limits to the view

        // For MAPEH subjects, check component assessment counts
        $isMAPEH = false;
        $componentAssessmentCounts = [];

        if (isset($subject->components) && $subject->components->count() > 0) {
            $componentNames = $subject->components->pluck('name')->map(fn($name) => strtolower($name))->toArray();
            $requiredComponents = ['music', 'arts', 'physical education', 'health'];

            $matchedComponents = 0;
            foreach ($requiredComponents as $component) {
                if (in_array($component, $componentNames) ||
                    in_array(strtolower(substr($component, 0, 5)), $componentNames)) {
                    $matchedComponents++;
                }
            }

            $isMAPEH = $matchedComponents == 4;

            if ($isMAPEH) {
                foreach ($subject->components as $component) {
                    $componentCount = Grade::where('subject_id', $component->id)
                        ->where('term', $request->term)
                        ->where('grade_type', $request->grade_type)
                        ->distinct('assessment_name')
                        ->count('assessment_name');

                    $componentAssessmentCounts[$component->id] = $componentCount;
                }
            }
        }

        // Get existing assessment names for indicators
        $existingAssessments = Grade::where('subject_id', $request->subject_id)
            ->where('term', $request->term)
            ->where('grade_type', $request->grade_type)
            ->distinct('assessment_name')
            ->pluck('assessment_name')
            ->toArray();

        // Get existing component assessment names
        $componentExistingAssessments = [];
        if ($isMAPEH) {
            foreach ($subject->components as $component) {
                $componentExistingAssessments[$component->id] = Grade::where('subject_id', $component->id)
                    ->where('term', $request->term)
                    ->where('grade_type', $request->grade_type)
                    ->distinct('assessment_name')
                    ->pluck('assessment_name')
                    ->toArray();
            }
        }

        return view('teacher.grades.assessment-setup', compact(
            'subject',
            'section',
            'term',
            'gradeType',
            'gradeTypes',
            'assessmentCount',
            'assessmentLimits',
            'existingAssessments',
            'isMAPEH',
            'componentAssessmentCounts',
            'componentExistingAssessments'
        ));
    }

    /**
     * Store assessment parameters and proceed to batch grade entry
     */
    public function storeAssessmentSetup(Request $request)
    {
        // Check if this is a MAPEH subject with components
        $isMAPEH = $request->has('is_mapeh') && $request->is_mapeh == 1;

        if ($isMAPEH) {
            // Validate MAPEH component setup
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'term' => 'required|in:Q1,Q2,Q3,Q4',
                'grade_type' => 'required|in:written_work,performance_task,quarterly',
                'assessment_name' => 'required|string|max:255',
                'section_id' => 'required|exists:sections,id',
                'selected_components' => 'required|array|min:1',
                'selected_components.*' => 'exists:subjects,id',
                'component_max_score' => 'required|array|min:1',
                'component_max_score.*' => 'required|numeric|min:1',
            ], [
                'selected_components.required' => 'Please select at least one MAPEH component.',
                'selected_components.min' => 'Please select at least one MAPEH component.',
                'component_max_score.*.required' => 'Maximum score is required for each selected component.',
                'component_max_score.*.min' => 'Maximum score must be at least 1 for each component.'
            ]);

            // Define assessment limits
            $assessmentLimits = [
                'written_work' => 7,
                'performance_task' => 8,
                'quarterly' => 1
            ];

            // Check component assessment counts
            foreach ($request->selected_components as $componentId) {
                $componentCount = Grade::where('subject_id', $componentId)
                    ->where('term', $request->term)
                    ->where('grade_type', $request->grade_type)
                    ->distinct('assessment_name')
                    ->count('assessment_name');

                if (isset($assessmentLimits[$request->grade_type]) && $componentCount >= $assessmentLimits[$request->grade_type]) {
                    $component = Subject::find($componentId);
                    $gradeTypes = [
                        'written_work' => 'Written Work',
                        'performance_task' => 'Performance Task',
                        'quarterly' => 'Quarterly Assessment'
                    ];

                    // Log the attempt but don't prevent access to setup page
                    Log::warning('Maximum assessment limit reached', [
                        'teacher_id' => Auth::id(),
                        'subject_id' => $request->subject_id,
                        'component_id' => $componentId,
                        'component_name' => $component->name,
                        'grade_type' => $request->grade_type,
                        'current_count' => $componentCount,
                        'max_allowed' => $assessmentLimits[$request->grade_type]
                    ]);

                    // Redirect back to the assessment setup page with an error message
                    return redirect()->route('teacher.grades.assessment-setup', [
                        'subject_id' => $request->subject_id,
                        'term' => $request->term,
                        'grade_type' => $request->grade_type,
                        'section_id' => $request->section_id
                    ])->with('error', "Cannot proceed to grade entry: Maximum number of {$assessmentLimits[$request->grade_type]} assessments for {$gradeTypes[$request->grade_type]} has been reached for {$component->name}.");
                }
            }

            // Log the selected components and max scores for debugging
            Log::info('MAPEH assessment setup', [
                'teacher_id' => Auth::id(),
                'subject_id' => $request->subject_id,
                'selected_components' => $request->selected_components,
                'component_max_scores' => $request->component_max_score
            ]);
        } else {
            // Validate regular subject setup
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'term' => 'required|in:Q1,Q2,Q3,Q4',
                'grade_type' => 'required|in:written_work,performance_task,quarterly',
                'assessment_name' => 'required|string|max:255',
                'max_score' => 'required|numeric|min:1',
                'section_id' => 'required|exists:sections,id',
            ]);

            // Define assessment limits
            $assessmentLimits = [
                'written_work' => 7,
                'performance_task' => 8,
                'quarterly' => 1
            ];

            // Check assessment count
            $assessmentCount = Grade::where('subject_id', $request->subject_id)
                ->where('term', $request->term)
                ->where('grade_type', $request->grade_type)
                ->distinct('assessment_name')
                ->count('assessment_name');

            if (isset($assessmentLimits[$request->grade_type]) && $assessmentCount >= $assessmentLimits[$request->grade_type]) {
                $gradeTypes = [
                    'written_work' => 'Written Work',
                    'performance_task' => 'Performance Task',
                    'quarterly' => 'Quarterly Assessment'
                ];

                // Log the attempt but don't prevent access to setup page
                Log::warning('Maximum assessment limit reached', [
                    'teacher_id' => Auth::id(),
                    'subject_id' => $request->subject_id,
                    'grade_type' => $request->grade_type,
                    'current_count' => $assessmentCount,
                    'max_allowed' => $assessmentLimits[$request->grade_type]
                ]);

                // Redirect back to the assessment setup page with an error message
                return redirect()->route('teacher.grades.assessment-setup', [
                    'subject_id' => $request->subject_id,
                    'term' => $request->term,
                    'grade_type' => $request->grade_type,
                    'section_id' => $request->section_id
                ])->with('error', "Cannot proceed to grade entry: Maximum number of {$assessmentLimits[$request->grade_type]} assessments for {$gradeTypes[$request->grade_type]} has been reached.");
            }
        }

        $teacherId = Auth::id();

        // Verify authorization
        $authorized = DB::table('section_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $request->subject_id)
            ->where('section_id', $request->section_id)
            ->exists();

        if (!$authorized) {
            $authorized = Section::where('id', $request->section_id)
                ->where('adviser_id', $teacherId)
                ->exists();
        }

        if (!$authorized) {
            abort(403, 'You are not authorized to create assessments for this subject.');
        }

        // Store assessment parameters in session
        session([
            'assessment_name' => $request->assessment_name
        ]);

        if ($isMAPEH) {
            // For MAPEH, store the selected components and their max scores
            session([
                'is_mapeh' => true,
                'selected_components' => $request->selected_components,
                'component_max_score' => $request->component_max_score
            ]);

            // Verify that all components exist in the database
            $components = Subject::whereIn('id', $request->selected_components)->get();
            if ($components->count() != count($request->selected_components)) {
                Log::warning('Some MAPEH components not found in database', [
                    'requested' => $request->selected_components,
                    'found' => $components->pluck('id')->toArray()
                ]);
                return redirect()->back()->with('error', 'Some selected MAPEH components are invalid. Please try again.');
            }

            // Log before redirect for debugging
            Log::info('MAPEH session data before redirect', [
                'is_mapeh' => session('is_mapeh'),
                'selected_components' => session('selected_components'),
                'component_max_score' => session('component_max_score'),
                'assessment_name' => session('assessment_name')
            ]);
        } else {
            // For regular subjects, store the max score
            session([
                'is_mapeh' => false,
                'max_score' => $request->max_score
            ]);
        }

        // Redirect to batch grade entry form with the parameters
        return redirect()->route('teacher.grades.batch-create', [
            'subject_id' => $request->subject_id,
            'term' => $request->term,
            'grade_type' => $request->grade_type,
            'section_id' => $request->section_id,
        ]);
    }

    /**
     * Configure grade percentages for a subject
     */
    public function configureGrades(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'written_work_percentage' => 'required|numeric|min:0|max:100',
            'performance_task_percentage' => 'required|numeric|min:0|max:100',
            'quarterly_assessment_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Verify the total adds up to 100%
        $total = $request->written_work_percentage +
                 $request->performance_task_percentage +
                 $request->quarterly_assessment_percentage;

        if (abs($total - 100) > 0.01) {
            return redirect()->back()
                ->with('error', 'The total percentage must equal 100%. Current total: ' . $total . '%');
        }

        $teacherId = Auth::id();

        // Check if teacher is authorized to configure this subject
        $authorized = DB::table('section_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $request->subject_id)
            ->exists();

        // Also allow section advisers to configure subjects for their section
        if (!$authorized) {
            $sectionIds = Section::where('adviser_id', $teacherId)->pluck('id')->toArray();
            if (!empty($sectionIds)) {
                $authorized = DB::table('section_subject')
                    ->where('subject_id', $request->subject_id)
                    ->whereIn('section_id', $sectionIds)
                    ->exists();
            }
        }

        if (!$authorized) {
            Log::alert('Unauthorized grade configuration attempt', [
                'teacher_id' => $teacherId,
                'subject_id' => $request->subject_id
            ]);

            abort(403, 'You are not authorized to configure grades for this subject.');
        }

        // Update or create configuration
        $config = GradeConfiguration::updateOrCreate(
            ['subject_id' => $request->subject_id],
            [
                'written_work_percentage' => $request->written_work_percentage,
                'performance_task_percentage' => $request->performance_task_percentage,
                'quarterly_assessment_percentage' => $request->quarterly_assessment_percentage,
            ]
        );

        Log::info('Grade configuration updated', [
            'subject_id' => $request->subject_id,
            'teacher_id' => $teacherId,
            'configuration' => $config->toArray()
        ]);

        return redirect()->route('teacher.grades.index', ['subject_id' => $request->subject_id])
            ->with('success', 'Grade configuration updated successfully.');
    }

    /**
     * Display form to configure grade percentages
     */
    public function showConfigureForm(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $teacherId = Auth::id();
        $subject = Subject::with('gradeConfiguration')->findOrFail($request->subject_id);

        // Check if teacher is authorized to configure this subject
        $authorized = DB::table('section_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subject->id)
            ->exists();

        // Also allow section advisers to configure subjects for their section
        if (!$authorized) {
            $sectionIds = Section::where('adviser_id', $teacherId)->pluck('id')->toArray();
            if (!empty($sectionIds)) {
                $authorized = DB::table('section_subject')
                    ->where('subject_id', $subject->id)
                    ->whereIn('section_id', $sectionIds)
                    ->exists();
            }
        }

        if (!$authorized) {
            abort(403, 'You are not authorized to configure grades for this subject.');
        }

        // Create default configuration if none exists
        if (!$subject->gradeConfiguration) {
            $subject->gradeConfiguration = GradeConfiguration::create([
                'subject_id' => $subject->id,
                'written_work_percentage' => 30.00,
                'performance_task_percentage' => 50.00,
                'quarterly_assessment_percentage' => 20.00,
            ]);
        }

        return view('teacher.grades.configure', compact('subject'));
    }

    /**
     * Display an improved listing of grades.
     */
    public function newIndex(Request $request)
    {
        try {
            $teacher = Auth::user();
            Log::info('Teacher accessing new grades index', ['teacher_id' => $teacher->id, 'name' => $teacher->name]);

            // Get teacher's sections (as adviser or subject teacher)
            $sections = Section::where('adviser_id', $teacher->id)
                ->orWhereHas('subjects', function($query) use ($teacher) {
                    $query->where('section_subject.teacher_id', $teacher->id);
                })
                ->get();

            // Get subject assignments for this teacher
            $subjects = Subject::whereHas('teachers', function($query) use ($teacher) {
                $query->where('users.id', $teacher->id);
            })->with(['sections' => function($query) use ($teacher) {
                $query->where('section_subject.teacher_id', $teacher->id);
            }])->get();

            Log::info('Teacher subjects retrieved', [
                'count' => $subjects->count(),
                'subject_ids' => $subjects->pluck('id')->toArray(),
                'subject_names' => $subjects->pluck('name')->toArray()
            ]);

            // Include debug info in logs to help troubleshoot
            if ($subjects->isEmpty()) {
                // Check if there are any subject-section assignments for this teacher
                $assignments = DB::table('section_subject')
                    ->where('teacher_id', $teacher->id)
                    ->get();

                Log::info('Direct section_subject query results:', [
                    'count' => $assignments->count(),
                    'assignments' => $assignments->toArray()
                ]);
            }

            // Get selected subject or use first subject
            $selectedSubject = null;
            $selectedTerm = $request->term ?? 'Q1';
            $selectedSectionId = null;

            if ($request->has('subject_id')) {
                $selectedSubject = Subject::with('gradeConfiguration')
                    ->findOrFail($request->subject_id);

                if ($request->has('section_id')) {
                    $selectedSectionId = $request->section_id;
                } elseif ($subjects->isNotEmpty() && $selectedSubject->sections->isNotEmpty()) {
                    $selectedSectionId = $selectedSubject->sections->first()->id;
                } elseif ($sections->isNotEmpty()) {
                    $selectedSectionId = $sections->first()->id;
                }
            } elseif ($subjects->isNotEmpty()) {
                $selectedSubject = $subjects->first();
                $selectedSubject->load('gradeConfiguration');

                if ($selectedSubject->sections->isNotEmpty()) {
                    $selectedSectionId = $selectedSubject->sections->first()->id;
                } elseif ($sections->isNotEmpty()) {
                    $selectedSectionId = $sections->first()->id;
                }
            }

            // Create grade configuration if it doesn't exist
            if ($selectedSubject && !$selectedSubject->gradeConfiguration) {
                GradeConfiguration::create([
                    'subject_id' => $selectedSubject->id,
                    'written_work_percentage' => 30.00,
                    'performance_task_percentage' => 50.00,
                    'quarterly_assessment_percentage' => 20.00,
                ]);
                $selectedSubject->load('gradeConfiguration');
            }

            // Get students and their grades
            $students = [];
            if ($selectedSubject && $selectedSectionId) {
                $sectionStudents = Student::where('section_id', $selectedSectionId)
                    ->with(['grades' => function($query) use ($selectedSubject, $selectedTerm) {
                        $query->where('subject_id', $selectedSubject->id)
                              ->where('term', $selectedTerm);
                    }])
                    ->get();

                foreach ($sectionStudents as $student) {
                    $writtenWorks = $student->grades->where('grade_type', 'written_work')->all();
                    $performanceTasks = $student->grades->where('grade_type', 'performance_task')->all();
                    $quarterlyAssessment = $student->grades->where('grade_type', 'quarterly')->first();

                    // Check if this is a MAPEH subject with components
                    $isMAPEH = false;
                    $mapehComponents = [];

                    if (isset($selectedSubject->components) && $selectedSubject->components->count() > 0) {
                        $componentNames = $selectedSubject->components->pluck('name')->map(fn($name) => strtolower($name))->toArray();
                        $requiredComponents = ['music', 'arts', 'physical education', 'health'];

                        $matchedComponents = 0;
                        foreach ($requiredComponents as $component) {
                            if (in_array($component, $componentNames) ||
                                in_array(strtolower(substr($component, 0, 5)), $componentNames)) {
                                $matchedComponents++;
                            }
                        }

                        $isMAPEH = $matchedComponents == 4;

                        // If MAPEH, load component grades
                        if ($isMAPEH) {
                            $componentSubjectIds = $selectedSubject->components->pluck('id')->toArray();

                            // Fetch component grades for this student
                            $componentGrades = Grade::where('student_id', $student->id)
                                ->whereIn('subject_id', $componentSubjectIds)
                                ->where('term', $selectedTerm)
                                ->get();

                            // Group by component subject and grade type
                            foreach ($selectedSubject->components as $component) {
                                $componentWrittenWorks = $componentGrades
                                    ->where('subject_id', $component->id)
                                    ->where('grade_type', 'written_work')
                                    ->all();

                                $componentPerformanceTasks = $componentGrades
                                    ->where('subject_id', $component->id)
                                    ->where('grade_type', 'performance_task')
                                    ->all();

                                $componentQuarterlyAssessment = $componentGrades
                                    ->where('subject_id', $component->id)
                                    ->where('grade_type', 'quarterly')
                                    ->first();

                                $mapehComponents[$component->id] = [
                                    'component' => $component,
                                    'written_works' => $componentWrittenWorks,
                                    'performance_tasks' => $componentPerformanceTasks,
                                    'quarterly_assessment' => $componentQuarterlyAssessment,
                                ];
                            }

                            // Add component written works to the main written works if not already there
                            foreach ($mapehComponents as $componentData) {
                                foreach ($componentData['written_works'] as $work) {
                                    $writtenWorks[] = $work;
                                }
                                foreach ($componentData['performance_tasks'] as $task) {
                                    $performanceTasks[] = $task;
                                }
                                // For quarterly assessment, only use if main one is not set
                                if (!$quarterlyAssessment && $componentData['quarterly_assessment']) {
                                    $quarterlyAssessment = $componentData['quarterly_assessment'];
                                }
                            }
                        }
                    }

                    $students[] = [
                        'student' => $student,
                        'written_works' => $writtenWorks,
                        'performance_tasks' => $performanceTasks,
                        'quarterly_assessment' => $quarterlyAssessment,
                    ];
                }
            }

            $terms = ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'];

            return view('teacher.grades.new_index', compact(
                'subjects',
                'selectedSubject',
                'students',
                'terms',
                'selectedTerm',
                'sections',
                'selectedSectionId'
            ));

        } catch (\Exception $e) {
            Log::error('Error in new grades index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('teacher.grades.new_index', [
                    'subjects' => collect(),
                    'students' => [],
                    'terms' => ['Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'],
                    'selectedTerm' => 'Q1',
                    'sections' => collect(),
                ])
                ->with('error', 'Error loading grades. Please try again or contact administrator. Details: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Redirect to the grades index page
        return redirect()->route('teacher.grades.index');
    }

    /**
     * Lock or unlock a transmutation table for consistent grading
     * Only section advisers can lock a transmutation table
     */
    public function lockTransmutationTable(Request $request)
    {
        $teacher = Auth::user();
        $locked = $request->input('locked', false);

        // Check if the table should be locked or unlocked
        if ($locked) {
            $tableId = $request->input('table_id', 1); // Default to DepEd table (now Table 1)

            // Store the locked state in the session only when explicitly requested
            session(['locked_transmutation_table' => true]);
            session(['locked_transmutation_table_id' => $tableId]);

            return response()->json([
                'success' => true,
                'message' => 'Transmutation table has been locked',
                'table_id' => $tableId
            ]);
        } else {
            // Remove the locked state from the session
            session()->forget('locked_transmutation_table');
            session()->forget('locked_transmutation_table_id');

            return response()->json([
                'success' => true,
                'message' => 'Transmutation table has been unlocked'
            ]);
        }
    }

    /**
     * Save the teacher's transmutation table preference
     */
    public function saveTransmutationPreference($teacherId, $tableId)
    {
        try {
            // Check if a preference already exists
            $exists = DB::table('teacher_preferences')
                ->where('teacher_id', $teacherId)
                ->where('preference_key', 'transmutation_table')
                ->exists();

            if ($exists) {
                // Update existing preference
                DB::table('teacher_preferences')
                    ->where('teacher_id', $teacherId)
                    ->where('preference_key', 'transmutation_table')
                    ->update([
                        'preference_value' => $tableId,
                        'updated_at' => now()
                    ]);
            } else {
                // Create new preference
                DB::table('teacher_preferences')->insert([
                    'teacher_id' => $teacherId,
                    'preference_key' => 'transmutation_table',
                    'preference_value' => $tableId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error saving transmutation preference: ' . $e->getMessage(), [
                'teacher_id' => $teacherId,
                'table_id' => $tableId
            ]);

            return false;
        }
    }

    /**
     * Update the teacher's transmutation table preference
     */
    public function updateTransmutationPreference(Request $request)
    {
        $tableId = $request->input('transmutation_table', 1); // Default to DepEd table (now Table 1)
        $teacherId = Auth::id();

        $success = $this->saveTransmutationPreference($teacherId, $tableId);

        if ($success) {
            return redirect()->back()->with('success', 'Transmutation table preference saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to save transmutation table preference.');
        }
    }

    /**
     * Bulk update grades from class record report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdate(Request $request)
    {
        $gradesData = $request->input('grades', []);
        $updatedCount = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($gradesData as $studentData) {
                $studentId = $studentData['student_id'];
                $sectionId = $studentData['section_id'];
                $subjectId = $studentData['subject_id'];
                $quarter = $studentData['quarter'];

                // Update individual assessment grades
                if (isset($studentData['grades']) && is_array($studentData['grades'])) {
                    foreach ($studentData['grades'] as $grade) {
                        $gradeModel = Grade::where([
                            'student_id' => $studentId,
                            'assessment_id' => $grade['assessment_id'],
                            'grade_type' => $grade['grade_type']
                        ])->first();

                        if ($gradeModel) {
                            // Update existing grade
                            $gradeModel->score = $grade['score'];
                            $gradeModel->save();

                            \Illuminate\Support\Facades\Log::info('Updated existing grade', [
                                'grade_id' => $gradeModel->id,
                                'old_score' => $gradeModel->score,
                                'new_score' => $grade['score']
                            ]);
                        } else {
                            // Create new grade
                            Grade::create([
                                'student_id' => $studentId,
                                'assessment_id' => $grade['assessment_id'],
                                'section_id' => $sectionId,
                                'subject_id' => $subjectId,
                                'quarter' => $quarter,
                                'grade_type' => $grade['grade_type'],
                                'score' => $grade['score'],
                                'max_score' => $grade['max_score'],
                                'assessment_name' => 'Assessment ' . $grade['assessment_id']
                            ]);

                            \Illuminate\Support\Facades\Log::info('Created new grade', [
                                'grade_id' => $gradeModel->id,
                                'score' => $grade['score']
                            ]);
                        }
                        $updatedCount++;
                    }
                }

                // Update grade summary if it exists
                $gradeSummary = GradeSummary::where([
                    'student_id' => $studentId,
                    'section_id' => $sectionId,
                    'subject_id' => $subjectId,
                    'quarter' => $quarter
                ])->first();

                $summaryData = [
                    'written_work_ps' => $studentData['written_work']['ps'],
                    'written_work_ws' => $studentData['written_work']['ws'],
                    'performance_task_ps' => $studentData['performance_task']['ps'],
                    'performance_task_ws' => $studentData['performance_task']['ws'],
                    'quarterly_assessment_ps' => $studentData['quarterly_assessment']['ps'],
                    'quarterly_assessment_ws' => $studentData['quarterly_assessment']['ws'],
                    'initial_grade' => $studentData['initial_grade'],
                    'quarterly_grade' => $studentData['quarterly_grade'],
                    'remarks' => $studentData['quarterly_grade'] >= 75 ? 'Passed' : 'Failed'
                ];

                if ($gradeSummary) {
                    $gradeSummary->update($summaryData);
                } else {
                    GradeSummary::create(array_merge([
                        'student_id' => $studentId,
                        'section_id' => $sectionId,
                        'subject_id' => $subjectId,
                        'quarter' => $quarter,
                    ], $summaryData));
                }

                $updatedCount++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "$updatedCount grades successfully updated.",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating grades: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Edit assessment score for a student
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Edit a student's assessment score
     */
    public function editAssessment(Request $request)
    {
        // Log the request method for debugging
        \Illuminate\Support\Facades\Log::info('Edit Assessment Request', [
            'method' => $request->method(),
            'user_agent' => $request->header('User-Agent'),
            'params' => $request->all()
        ]);

        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'quarter' => 'required|string|in:Q1,Q2,Q3,Q4',
            'assessment_type' => 'required|string|in:written_work,performance_task,quarterly,quarterly_exam',
            'assessment_name' => 'required|string',
            'score' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Log the request for debugging
            \Illuminate\Support\Facades\Log::info('Editing assessment', [
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'quarter' => $request->quarter,
                'assessment_type' => $request->assessment_type,
                'assessment_name' => $request->assessment_name,
                'assessment_index' => $request->assessment_index,
                'score' => $request->score,
                'max_score' => $request->max_score
            ]);

            // Get the student
            $student = \App\Models\Student::findOrFail($request->student_id);

            // Delete any existing quarterly assessment grades first to avoid duplicates
            // This ensures we have a clean state before saving the new score
            if ($request->assessment_type === 'quarterly') {
                Grade::where([
                    'student_id' => $request->student_id,
                    'subject_id' => $request->subject_id,
                    'term' => $request->quarter,
                ])->where(function($query) {
                    $query->where('grade_type', 'quarterly')
                          ->orWhere('grade_type', 'quarterly_assessment')
                          ->orWhere('grade_type', 'quarterly_exam');
                })->delete();

                // Now create a new grade record with the updated score
                $maxScore = $request->max_score ?? 100;
                $grade = Grade::create([
                    'student_id' => $request->student_id,
                    'subject_id' => $request->subject_id,
                    'term' => $request->quarter,
                    'grade_type' => 'quarterly',
                    'score' => $request->score,
                    'max_score' => $maxScore,
                    'assessment_name' => $request->assessment_name
                ]);

                $oldScore = 0;
                \Illuminate\Support\Facades\Log::info('Created new quarterly assessment grade', [
                    'grade_id' => $grade->id,
                    'score' => $request->score
                ]);
            } else {
                // For non-quarterly assessments, use the existing logic
                $grade = Grade::where([
                    'student_id' => $request->student_id,
                    'subject_id' => $request->subject_id,
                    'term' => $request->quarter,
                    'grade_type' => $request->assessment_type,
                    'assessment_name' => $request->assessment_name
                ])->first();

                // If grade exists, update it
                if ($grade) {
                    $oldScore = $grade->score;
                    $grade->score = $request->score;
                    $grade->save();

                    \Illuminate\Support\Facades\Log::info('Updated existing grade', [
                        'grade_id' => $grade->id,
                        'old_score' => $oldScore,
                        'new_score' => $request->score
                    ]);
                } else {
                    // Create new grade if doesn't exist
                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $request->subject_id)->first();
                    $maxScore = $request->max_score ?? 100;

                    // Create the grade
                    $grade = Grade::create([
                        'student_id' => $request->student_id,
                        'subject_id' => $request->subject_id,
                        'term' => $request->quarter,
                        'grade_type' => $request->assessment_type,
                        'score' => $request->score,
                        'max_score' => $maxScore,
                        'assessment_name' => $request->assessment_name
                    ]);

                    \Illuminate\Support\Facades\Log::info('Created new grade', [
                        'grade_id' => $grade->id,
                        'score' => $request->score
                    ]);

                    $oldScore = 0;
                }
            }

            // Now update the grade summary
            $gradeSummary = \App\Models\GradeSummary::where([
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'quarter' => $request->quarter,
            ])->first();

            if ($gradeSummary) {
                // Get the subject's grading configurations
                $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $request->subject_id)->firstOrFail();

                // Recalculate all components based on current grades

                // 1. Written Works
                $writtenWorks = Grade::where([
                    'student_id' => $request->student_id,
                    'subject_id' => $request->subject_id,
                    'term' => $request->quarter,
                    'grade_type' => 'written_work'
                ])->get();

                $wwPercentage = 0;
                if ($writtenWorks->count() > 0) {
                    $wwTotalPercentage = 0;
                    foreach ($writtenWorks as $ww) {
                        $wwTotalPercentage += ($ww->score / $ww->max_score) * 100;
                    }
                    $wwPercentage = $wwTotalPercentage / $writtenWorks->count();
                }
                $wwWeighted = ($wwPercentage / 100) * $gradeConfig->written_work_percentage;

                // 2. Performance Tasks
                $performanceTasks = Grade::where([
                    'student_id' => $request->student_id,
                    'subject_id' => $request->subject_id,
                    'term' => $request->quarter,
                    'grade_type' => 'performance_task'
                ])->get();

                $ptPercentage = 0;
                if ($performanceTasks->count() > 0) {
                    $ptTotalPercentage = 0;
                    foreach ($performanceTasks as $pt) {
                        $ptTotalPercentage += ($pt->score / $pt->max_score) * 100;
                    }
                    $ptPercentage = $ptTotalPercentage / $performanceTasks->count();
                }
                $ptWeighted = ($ptPercentage / 100) * $gradeConfig->performance_task_percentage;

                // 3. Quarterly Assessment
                $qaPercentage = 0;

                // If this is a quarterly assessment update, use the current data directly
                if ($request->assessment_type === 'quarterly') {
                    // Make sure we're using the correct max_score value
                    $maxScore = floatval($request->max_score ?? 100);
                    $score = floatval($request->score);

                    // Ensure we don't divide by zero
                    if ($maxScore > 0) {
                        $qaPercentage = ($score / $maxScore) * 100;

                        // Log the calculation for debugging
                        \Illuminate\Support\Facades\Log::info('Quarterly Assessment calculation', [
                            'score' => $score,
                            'max_score' => $maxScore,
                            'percentage' => $qaPercentage
                        ]);
                    } else {
                        $qaPercentage = 0;
                        \Illuminate\Support\Facades\Log::warning('Invalid max_score for quarterly assessment', [
                            'max_score' => $maxScore,
                            'score' => $score
                        ]);
                    }
                } else {
                    // Otherwise, fetch from database
                    $quarterlyAssessment = Grade::where([
                        'student_id' => $request->student_id,
                        'subject_id' => $request->subject_id,
                        'term' => $request->quarter,
                    ])->where(function($query) {
                        $query->where('grade_type', 'quarterly')
                              ->orWhere('grade_type', 'quarterly_assessment')
                              ->orWhere('grade_type', 'quarterly_exam');
                    })->first();

                    if ($quarterlyAssessment) {
                        // Ensure we don't divide by zero
                        if ($quarterlyAssessment->max_score > 0) {
                            $qaPercentage = ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100;
                        } else {
                            $qaPercentage = 0;
                            \Illuminate\Support\Facades\Log::warning('Invalid max_score for quarterly assessment from database', [
                                'max_score' => $quarterlyAssessment->max_score,
                                'score' => $quarterlyAssessment->score
                            ]);
                        }
                    }
                }

                $qaWeighted = ($qaPercentage / 100) * $gradeConfig->quarterly_assessment_percentage;

                // Update the grade summary
                $gradeSummary->written_work_ps = $wwPercentage;
                $gradeSummary->written_work_ws = $wwWeighted;
                $gradeSummary->performance_task_ps = $ptPercentage;
                $gradeSummary->performance_task_ws = $ptWeighted;
                $gradeSummary->quarterly_assessment_ps = $qaPercentage;
                $gradeSummary->quarterly_assessment_ws = $qaWeighted;

                // Calculate final grades
                $initialGrade = $wwWeighted + $ptWeighted + $qaWeighted;
                $gradeSummary->initial_grade = $initialGrade;

                // Transmute the grade according to DepEd rules
                $quarterlyGrade = $this->transmutationTable1($initialGrade);
                $gradeSummary->quarterly_grade = $quarterlyGrade;
                $gradeSummary->remarks = $quarterlyGrade >= 75 ? 'Passed' : 'Failed';

                $gradeSummary->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Assessment score updated successfully.",
                'old_score' => $oldScore,
                'new_score' => $request->score,
                'grade_summary' => $gradeSummary
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating assessment score: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update assessment details (name and max score)
     */
    public function updateAssessment(Request $request)
    {
        // Log the request for debugging
        Log::info('Assessment update request', [
            'request_data' => $request->all(),
            'max_score' => $request->max_score,
            'max_score_type' => gettype($request->max_score),
            'has_ajax' => $request->ajax()
        ]);

        // Check if it's an AJAX request
        $isAjax = $request->ajax();

        try {
            // Force numeric conversion for max_score
            $maxScore = is_numeric($request->max_score) ?
                (float)$request->max_score :
                (is_string($request->max_score) ? (float)$request->max_score : 100);

            // Override request with parsed max_score
            $request->merge(['max_score' => $maxScore]);

            $validated = $request->validate([
                'subject_id' => 'required|integer|exists:subjects,id',
                'term' => 'required|string|in:Q1,Q2,Q3,Q4',
                'grade_type' => 'required|string|in:written_work,performance_task,quarterly',
                'old_assessment_name' => 'required|string',
                'assessment_name' => 'required|string|max:255',
                'max_score' => 'required|numeric|min:0.1',
                'is_mapeh' => 'sometimes|boolean',
                'component_id' => 'sometimes|nullable',
            ]);

            DB::beginTransaction();

            $teacherId = Auth::id();
            Log::info('Teacher ID for assessment update', ['teacher_id' => $teacherId]);

            // Check if the teacher is authorized to access this subject
            $authorized = DB::table('section_subject')
                ->where('teacher_id', $teacherId)
                ->where('subject_id', $request->subject_id)
                ->exists();

            // If not found in section_subject, check if teacher is an adviser
            if (!$authorized) {
                $authorized = Section::whereHas('subjects', function($query) use ($request) {
                    $query->where('subjects.id', $request->subject_id);
                })->where('adviser_id', $teacherId)->exists();
            }

            if (!$authorized) {
                Log::alert('Unauthorized assessment update attempt', [
                    'teacher_id' => $teacherId,
                    'subject_id' => $request->subject_id
                ]);

                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to update assessments for this subject.'
                    ], 403);
                }

                return redirect()->back()->with('error', 'You are not authorized to update assessments for this subject.');
            }

            // Determine which subject ID to use (main subject or component)
            $subjectId = $request->subject_id; // Default to main subject ID

            // For MAPEH subjects, check component ID
            if ($request->is_mapeh && !empty($request->component_id)) {
                $componentId = $request->component_id;
                $componentExists = \App\Models\Subject::where('id', $componentId)->exists();

                if ($componentExists) {
                    $subjectId = $componentId;
                }
            }

            // Find all grades with this assessment name
            $grades = Grade::where([
                'subject_id' => $subjectId,
                'term' => $request->term,
                'grade_type' => $request->grade_type,
                'assessment_name' => $request->old_assessment_name
            ])->get();

            Log::info('Found grades for update', ['count' => $grades->count()]);

            // If no grades found, create a new assessment
            if ($grades->isEmpty()) {
                $studentIds = DB::table('student_section')
                    ->join('section_subject', 'student_section.section_id', '=', 'section_subject.section_id')
                    ->where('section_subject.subject_id', $subjectId)
                    ->pluck('student_section.student_id');

                if ($studentIds->isEmpty()) {
                    Log::warning('No students found for this subject', [
                        'subject_id' => $subjectId
                    ]);

                    if ($isAjax) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No students found for this subject. Please add students before creating assessments.'
                        ], 404);
                    }

                    return redirect()->back()->with('error', 'No students found for this subject. Please add students before creating assessments.');
                }

                // Create a template grade for the first student
                $firstStudentId = $studentIds->first();

                $grade = new Grade();
                $grade->student_id = $firstStudentId;
                $grade->subject_id = $subjectId;
                $grade->term = $request->term;
                $grade->grade_type = $request->grade_type;
                $grade->assessment_name = $request->assessment_name;
                $grade->score = 0; // Default score
                $grade->max_score = $request->max_score;
                $grade->save();

                DB::commit();

                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Assessment created successfully.',
                        'is_new' => true
                    ]);
                }

                return redirect()->back()->with('success', 'Assessment created successfully.');
            }

            // Update all grades using direct SQL for better performance
            $updatedCount = 0;
            $batchUpdates = [];

            foreach ($grades as $grade) {
                // Use direct database update for better performance
                $updated = DB::table('grades')
                    ->where('id', $grade->id)
                    ->update([
                        'assessment_name' => $request->assessment_name,
                        'max_score' => $request->max_score
                    ]);

                if ($updated) {
                    $updatedCount++;
                }
            }

            // Recalculate grade summaries for all affected students
            $studentIds = $grades->pluck('student_id')->unique();
            $updatedSummaries = 0;
            $summaryErrors = [];

            foreach ($studentIds as $studentId) {
                try {
                    $summary = $this->recalculateGradeSummary($studentId, $subjectId, $request->term);
                    if ($summary) {
                        $updatedSummaries++;
                    } else {
                        $summaryErrors[] = "Failed to update summary for student ID: {$studentId}";
                    }
                } catch (\Exception $e) {
                    Log::error('Error updating grade summary for student', [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'term' => $request->term,
                        'error' => $e->getMessage()
                    ]);

                    $summaryErrors[] = "Error updating summary for student ID {$studentId}: " . $e->getMessage();
                }
            }

            // Log the results
            Log::info('Assessment update completed', [
                'updated_grades' => $updatedCount,
                'updated_summaries' => $updatedSummaries,
                'summary_errors' => $summaryErrors
            ]);

            // Only commit if there were no summary errors
            if (empty($summaryErrors)) {
                DB::commit();
                $successMessage = 'Assessment updated successfully. Updated ' . $updatedCount . ' grade entries for ' . count($studentIds) . ' students.';

                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Assessment updated successfully.',
                        'affected_grades' => $updatedCount,
                        'affected_students' => count($studentIds)
                    ]);
                }

                return redirect()->back()->with('success', $successMessage);
            } else {
                // If there were errors updating summaries, we still commit the grade changes
                // but let the user know about the summary update issues
                DB::commit();

                $warningMessage = 'Assessment information was updated, but there were issues updating some grade summaries. Please contact the system administrator.';

                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'warning' => true,
                        'message' => 'Assessment updated with warnings.',
                        'warning_message' => $warningMessage,
                        'affected_grades' => $updatedCount,
                        'affected_students' => count($studentIds)
                    ]);
                }

                return redirect()->back()
                    ->with('success', 'Assessment updated successfully.')
                    ->with('warning', $warningMessage);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Assessment update validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating assessment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'subject_id' => $subjectId ?? 'not set'
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating assessment: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error updating assessment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Recalculate a student's grade summary for a subject and term
     */
    private function recalculateGradeSummary($studentId, $subjectId, $term)
    {
        // Get the subject's grading configurations
        $gradeConfig = GradeConfiguration::where('subject_id', $subjectId)->firstOrFail();

        // Get the student's section ID
        $sectionId = DB::table('student_section')
            ->where('student_id', $studentId)
            ->value('section_id');

        if (!$sectionId) {
            Log::error('Unable to recalculate grade summary: Student section not found', [
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'term' => $term
            ]);
            return false;
        }

        // Get the grade summary or create a new one
        $gradeSummary = GradeSummary::firstOrNew([
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'quarter' => $term,
        ]);

        // Set the section_id
        $gradeSummary->section_id = $sectionId;

        // 1. Written Works
        $writtenWorks = Grade::where([
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'term' => $term,
            'grade_type' => 'written_work'
        ])->get();

        $wwPercentage = 0;
        if ($writtenWorks->count() > 0) {
            $wwTotalScore = 0;
            $wwTotalMaxScore = 0;
            foreach ($writtenWorks as $ww) {
                $wwTotalScore += $ww->score;
                $wwTotalMaxScore += $ww->max_score;
            }
            $wwPercentage = $wwTotalMaxScore > 0 ? ($wwTotalScore / $wwTotalMaxScore) * 100 : 0;
        }
        $wwWeighted = ($wwPercentage / 100) * $gradeConfig->written_work_percentage;

        // 2. Performance Tasks
        $performanceTasks = Grade::where([
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'term' => $term,
            'grade_type' => 'performance_task'
        ])->get();

        $ptPercentage = 0;
        if ($performanceTasks->count() > 0) {
            $ptTotalScore = 0;
            $ptTotalMaxScore = 0;
            foreach ($performanceTasks as $pt) {
                $ptTotalScore += $pt->score;
                $ptTotalMaxScore += $pt->max_score;
            }
            $ptPercentage = $ptTotalMaxScore > 0 ? ($ptTotalScore / $ptTotalMaxScore) * 100 : 0;
        }
        $ptWeighted = ($ptPercentage / 100) * $gradeConfig->performance_task_percentage;

        // 3. Quarterly Assessment
        $quarterlyAssessment = Grade::where([
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'term' => $term,
        ])->where(function($query) {
            $query->where('grade_type', 'quarterly')
                  ->orWhere('grade_type', 'quarterly_assessment')
                  ->orWhere('grade_type', 'quarterly_exam');
        })->first();

        $qaPercentage = 0;
        if ($quarterlyAssessment) {
            $qaPercentage = ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100;
        }
        $qaWeighted = ($qaPercentage / 100) * $gradeConfig->quarterly_assessment_percentage;

        // Update the grade summary
        $gradeSummary->written_work_ps = $wwPercentage;
        $gradeSummary->written_work_ws = $wwWeighted;
        $gradeSummary->performance_task_ps = $ptPercentage;
        $gradeSummary->performance_task_ws = $ptWeighted;
        $gradeSummary->quarterly_assessment_ps = $qaPercentage;
        $gradeSummary->quarterly_assessment_ws = $qaWeighted;

        // Calculate final grades
        $initialGrade = $wwWeighted + $ptWeighted + $qaWeighted;
        $gradeSummary->initial_grade = $initialGrade;

        // Transmute the grade according to DepEd rules
        $quarterlyGrade = $this->transmutationTable1($initialGrade);
        $gradeSummary->quarterly_grade = $quarterlyGrade;
        $gradeSummary->remarks = $quarterlyGrade >= 75 ? 'Passed' : 'Failed';

        // Save with specific debug logging
        try {
            $result = $gradeSummary->save();
            Log::info('Grade summary saved', [
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'term' => $term,
                'section_id' => $sectionId,
                'result' => $result
            ]);
            return $gradeSummary;
        } catch (\Exception $e) {
            Log::error('Failed to save grade summary', [
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'term' => $term,
                'section_id' => $sectionId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get the maximum score for an assessment
     */
    public function getAssessmentMaxScore(Request $request)
    {
        // Log the request for debugging
        Log::info('Getting assessment max score', [
            'params' => $request->all()
        ]);

        try {
            // SUPER SIMPLE APPROACH: Just get the max score directly
            $maxScore = DB::table('grades')
                ->where('subject_id', $request->subject_id)
                ->where('term', $request->term)
                ->where('grade_type', $request->grade_type)
                ->where('assessment_name', $request->assessment_name)
                ->value('max_score');

            if ($maxScore !== null) {
                Log::info('Max score found directly', ['max_score' => $maxScore]);
                return response()->json([
                    'success' => true,
                    'max_score' => (int)$maxScore
                ]);
            }

            // If that fails, try a more general approach
            $maxScore = DB::table('grades')
                ->where('term', $request->term)
                ->where('grade_type', $request->grade_type)
                ->where('assessment_name', $request->assessment_name)
                ->value('max_score');

            if ($maxScore !== null) {
                Log::info('Max score found with general query', ['max_score' => $maxScore]);
                return response()->json([
                    'success' => true,
                    'max_score' => (int)$maxScore
                ]);
            }

            // If we get here, no assessment was found
            Log::warning('Assessment not found by any method', [
                'subject_id' => $request->subject_id,
                'term' => $request->term,
                'grade_type' => $request->grade_type,
                'assessment_name' => $request->assessment_name
            ]);

            // Return a default value instead of an error
            return response()->json([
                'success' => true,
                'max_score' => 100
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching assessment max score', [
                'error' => $e->getMessage()
            ]);

            // Return a default value instead of an error
            return response()->json([
                'success' => true,
                'max_score' => 100
            ]);
        }
    }

    /**
     * DepEd Transmutation Table 1
     *
     * @param float $initialGrade
     * @return int
     */
    private function transmutationTable1($initialGrade)
    {
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
        return 60;
    }
}
