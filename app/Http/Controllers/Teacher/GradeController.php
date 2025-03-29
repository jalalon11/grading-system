<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\GradeConfiguration;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
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
                'quarterly' => 'Quarterly Assessment'
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
            
            // Verify that this is a MAPEH assessment from the setup
            if ($isMAPEH && (!session('is_mapeh') || empty(session('selected_components')) || empty(session('component_max_score')))) {
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
                'grade_type' => 'required|in:written_work,performance_task,quarterly',
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
                'grade_type' => 'required|in:written_work,performance_task,quarterly',
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
        
        $gradeTypes = [
            'written_work' => 'Written Work',
            'performance_task' => 'Performance Task',
            'quarterly' => 'Quarterly Assessment'
        ];
        
        return view('teacher.grades.assessment-setup', compact(
            'subject',
            'section',
            'term',
            'gradeType',
            'gradeTypes'
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
}
