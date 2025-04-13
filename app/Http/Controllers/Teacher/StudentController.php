<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get sections associated with the current teacher
            $sections = Section::where('adviser_id', Auth::id())->pluck('id');

            // Get students in these sections
            $students = Student::whereIn('section_id', $sections)
                ->with('section')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();

            // Get assigned sections where the teacher teaches subjects but is not the adviser
            $teacherId = Auth::id();
            $assignedSectionIds = DB::table('section_subject')
                ->where('teacher_id', $teacherId)
                ->join('sections', 'section_subject.section_id', '=', 'sections.id')
                ->where(function($query) use ($teacherId) {
                    $query->where('sections.adviser_id', '!=', $teacherId)
                          ->orWhereNull('sections.adviser_id');
                })
                ->pluck('sections.id')
                ->unique();

            $assignedSections = Section::whereIn('id', $assignedSectionIds)
                ->get();

            // Get students from these assigned sections
            $assignedStudents = Student::whereIn('section_id', $assignedSectionIds)
                ->with('section')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();

            // Get subjects assigned to the teacher for each section
            $teacherSubjectsData = DB::table('section_subject')
                ->where('teacher_id', $teacherId)
                ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
                ->select('section_subject.section_id', 'subjects.id', 'subjects.name', 'subjects.code')
                ->get();

            $assignedSubjectsBySection = [];

            foreach ($teacherSubjectsData as $subjectData) {
                if (!isset($assignedSubjectsBySection[$subjectData->section_id])) {
                    $assignedSubjectsBySection[$subjectData->section_id] = [];
                }
                $assignedSubjectsBySection[$subjectData->section_id][] = (object)[
                    'id' => $subjectData->id,
                    'name' => $subjectData->name,
                    'code' => $subjectData->code
                ];
            }

            return view('teacher.students.index', compact(
                'students',
                'assignedStudents',
                'assignedSections',
                'assignedSubjectsBySection'
            ));
        } catch (\Exception $e) {
            Log::error('Error in student index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('teacher.students.index', [
                'students' => collect(),
                'assignedStudents' => collect(),
                'assignedSections' => collect(),
                'assignedSubjectsBySection' => []
            ])->with('error', 'Error loading students. Please contact administrator.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::where('adviser_id', Auth::id())->get();

        return view('teacher.students.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50|unique:students',
            'lrn' => 'required|numeric|unique:students',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'section_id' => 'required|exists:sections,id',
            'address' => 'nullable|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
        ]);

        // Verify the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
            ->where('adviser_id', Auth::id())
            ->firstOrFail();

        Student::create($validated);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // Check if viewing from assigned section
        $isFromAssignedSection = $request->has('from_assigned');
        $assignedSubjectId = $request->query('subject_id');

        if ($isFromAssignedSection && $assignedSubjectId) {
            try {
                // Verify this teacher teaches this subject to this student
                $student = Student::with([
                    'section',
                    'grades' => function($query) use ($assignedSubjectId) {
                        $query->where('subject_id', $assignedSubjectId)
                              ->with('subject'); // Make sure subject relation is loaded with grades
                    },
                    'attendances'
                ])->findOrFail($id);

                // Check if the teacher is assigned to teach this subject in the student's section
                $teacherAssigned = DB::table('section_subject')
                    ->where('section_id', $student->section_id)
                    ->where('subject_id', $assignedSubjectId)
                    ->where('teacher_id', Auth::id())
                    ->exists();

                if (!$teacherAssigned) {
                    abort(403, 'You are not authorized to view this student\'s grades for this subject.');
                }

                // Get the selected transmutation table from the request or use default (1)
                $selectedTransmutationTable = $request->query('transmutation_table', 1);

                // Get the subject with its detailed information
                $subject = Subject::with('components')->findOrFail($assignedSubjectId);

                // Check if this is a MAPEH subject
                $isMAPEH = $subject->getIsMAPEHAttribute();

                // Get subject IDs to include in approvals (main subject + components if MAPEH)
                $subjectIds = [$assignedSubjectId];
                if ($isMAPEH && $subject->components->count() > 0) {
                    foreach ($subject->components as $component) {
                        $subjectIds[] = $component->id;
                    }
                }

                // Get grade approvals for this subject (and components if MAPEH) in this section
                $gradeApprovals = \App\Models\GradeApproval::where('section_id', $student->section_id)
                    ->whereIn('subject_id', $subjectIds)
                    ->get();

                // Organize grade approvals by subject_id and quarter
                $extendedApprovals = [];
                foreach ($gradeApprovals as $approval) {
                    if (!isset($extendedApprovals[$approval->subject_id])) {
                        $extendedApprovals[$approval->subject_id] = [];
                    }
                    // Store the quarter as a property in the approval object
                    $extendedApprovals[$approval->subject_id][$approval->quarter] = $approval;
                }

                return view('teacher.students.show', compact('student', 'selectedTransmutationTable', 'isFromAssignedSection', 'subject', 'extendedApprovals'));
            } catch (\Exception $e) {
                Log::error('Error in student show: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);

                return redirect()->route('teacher.students.index')
                    ->with('error', 'Error loading student data. Please try again or contact administrator.');
            }
        }

        // Default behavior - get sections associated with the current teacher
        $sectionIds = Section::where('adviser_id', Auth::id())->pluck('id');

        // Find the student and ensure they belong to one of the teacher's sections
        $student = Student::whereIn('section_id', $sectionIds)
            ->with(['section.subjects', 'section.adviser', 'grades.subject', 'attendances'])
            ->findOrFail($id);

        // Get the selected transmutation table from the request or use default (1)
        $selectedTransmutationTable = $request->query('transmutation_table', 1);

        // Get all subjects for this student's section
        $subjects = $student->section->subjects;

        // Get MAPEH subjects and components
        $mapehSubjects = $subjects->filter(function($subject) {
            return $subject->is_mapeh && !$subject->mapeh_component;
        });

        $mapehComponents = $subjects->filter(function($subject) {
            return $subject->mapeh_component;
        });

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

        // Get grade approvals for all subjects in this section
        $gradeApprovals = \App\Models\GradeApproval::where('section_id', $student->section_id)
            ->get();

        // Get all subjects in this section for debugging
        $sectionSubjects = \App\Models\Subject::whereHas('sections', function($query) use ($student) {
            $query->where('sections.id', $student->section_id);
        })->get();

        // Log the subjects in this section
        \Illuminate\Support\Facades\Log::debug('Subjects in section ' . $student->section_id, [
            'count' => $sectionSubjects->count(),
            'subjects' => $sectionSubjects->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name
                ];
            })->toArray()
        ]);

        // Organize grade approvals by subject_id and quarter
        $organizedApprovals = [];
        foreach ($gradeApprovals as $approval) {
            if (!isset($organizedApprovals[$approval->subject_id])) {
                $organizedApprovals[$approval->subject_id] = [];
            }
            // Store the quarter as a property in the approval object
            $organizedApprovals[$approval->subject_id][$approval->quarter] = $approval;
        }

        // Log the organized approvals
        \Illuminate\Support\Facades\Log::debug('Organized Grade Approvals', [
            'organizedApprovals' => $organizedApprovals
        ]);

        // Debug information about grade approvals
        \Illuminate\Support\Facades\Log::debug('Grade Approvals for section ' . $student->section_id, [
            'count' => $gradeApprovals->count(),
            'approvals' => $gradeApprovals->toArray()
        ]);

        // Use the organized approvals instead of just keying by subject_id
        // This ensures we have approvals for each quarter
        $gradeApprovals = $organizedApprovals;

        // Log the final structure
        \Illuminate\Support\Facades\Log::debug('Final Grade Approvals Structure', [
            'gradeApprovals' => $gradeApprovals
        ]);

        // Extend approvals to include MAPEH components if the parent is approved
        $extendedApprovals = $gradeApprovals;

        // Debug MAPEH parent map
        \Illuminate\Support\Facades\Log::debug('MAPEH Parent Map', [
            'mapehParentMap' => $mapehParentMap
        ]);

        // For each MAPEH component, check if its parent is approved for each quarter
        foreach ($mapehParentMap as $componentId => $parentId) {
            if (isset($gradeApprovals[$parentId])) {
                // For each quarter that the parent is approved
                foreach ($gradeApprovals[$parentId] as $quarter => $parentApproval) {
                    if ($parentApproval->is_approved) {
                        // Create a virtual approval for the component for this quarter
                        if (!isset($extendedApprovals[$componentId])) {
                            $extendedApprovals[$componentId] = [];
                        }

                        $componentApproval = new \App\Models\GradeApproval([
                            'subject_id' => $componentId,
                            'section_id' => $student->section_id,
                            'quarter' => $quarter,
                            'is_approved' => true,
                            'inherited_from_parent' => true
                        ]);

                        $extendedApprovals[$componentId][$quarter] = $componentApproval;

                        // Log the inheritance
                        \Illuminate\Support\Facades\Log::debug("Inherited approval from MAPEH parent", [
                            'componentId' => $componentId,
                            'parentId' => $parentId,
                            'quarter' => $quarter
                        ]);
                    }
                }
            }
        }

        // Log the extended approvals
        \Illuminate\Support\Facades\Log::debug('Extended Approvals with MAPEH Components', [
            'extendedApprovals' => $extendedApprovals
        ]);

        return view('teacher.students.show', compact('student', 'selectedTransmutationTable', 'extendedApprovals', 'mapehParentMap'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Get sections associated with the current teacher
        $sections = Section::where('adviser_id', Auth::id())->get();
        $sectionIds = $sections->pluck('id');

        // Find the student and ensure they belong to one of the teacher's sections
        $student = Student::whereIn('section_id', $sectionIds)->findOrFail($id);

        return view('teacher.students.edit', compact('student', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Get sections associated with the current teacher
        $sectionIds = Section::where('adviser_id', Auth::id())->pluck('id');

        // Find the student and ensure they belong to one of the teacher's sections
        $student = Student::whereIn('section_id', $sectionIds)->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50|unique:students,student_id,' . $student->id,
            'lrn' => 'required|numeric|unique:students,lrn,' . $student->id,
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'section_id' => 'required|exists:sections,id',
            'address' => 'nullable|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:50',
        ]);

        // Verify the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
            ->where('adviser_id', Auth::id())
            ->firstOrFail();

        $student->update($validated);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Get sections associated with the current teacher
        $sectionIds = Section::where('adviser_id', Auth::id())->pluck('id');

        // Find the student and ensure they belong to one of the teacher's sections
        $student = Student::whereIn('section_id', $sectionIds)->findOrFail($id);

        // Check if student has grades or attendance records
        if ($student->grades()->count() > 0 || $student->attendances()->count() > 0) {
            return redirect()->route('teacher.students.index')
                ->with('error', 'Cannot delete student with grades or attendance records.');
        }

        $student->delete();

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Display gender distribution data for a specific section or all sections
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function genderDistribution(Request $request)
    {
        $sectionId = $request->input('section_id', 'all');

        if ($sectionId === 'all') {
            // Get all students for the teacher
            $students = Student::with('section')->whereHas('section', function($query) {
                $query->where('adviser_id', Auth::id());
            })->get();
        } else {
            // Get students for a specific section
            $students = Student::with('section')
                ->where('section_id', $sectionId)
                ->whereHas('section', function($query) {
                    $query->where('adviser_id', Auth::id());
                })
                ->get();
        }

        // Calculate gender statistics
        $maleCount = $students->filter(function($student) {
            return strtolower($student->gender) === 'male';
        })->count();

        $femaleCount = $students->filter(function($student) {
            return strtolower($student->gender) === 'female';
        })->count();

        $totalStudents = $students->count();
        $malePercentage = $totalStudents > 0 ? round(($maleCount / $totalStudents) * 100) : 0;
        $femalePercentage = $totalStudents > 0 ? round(($femaleCount / $totalStudents) * 100) : 0;

        return response()->json([
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'male_percentage' => $malePercentage,
            'female_percentage' => $femalePercentage,
            'total' => $totalStudents
        ]);
    }
}
