<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SubjectsImport;
use App\Exports\SubjectsTemplateExport;

class SubjectController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        try {
            $subjects = Subject::where('school_id', Auth::user()->school_id)
                ->withCount('sections')
                ->get();

            return view('teacher_admin.subjects.index', compact('subjects'));
        } catch (\Exception $e) {
            Log::error('Error loading subjects: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return view('teacher_admin.subjects.index', ['subjects' => collect()])
                ->with('error', 'Error loading subjects. Please try again or contact support.');
        }
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        try {
            // Get grade levels with fallback
            $school = Auth::user()->school;
            $gradeLevels = [];
            
            if ($school) {
                // Parse grade levels from school settings
                $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels : 
                             (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
                
                // If still empty, use default grades
                if (empty($gradeLevels)) {
                    $gradeLevels = range(7, 12);
                }
            } else {
                // Default grade levels
                $gradeLevels = range(7, 12);
            }
            
            return view('teacher_admin.subjects.create', compact('gradeLevels'));
        } catch (\Exception $e) {
            Log::error('Error loading subject creation form: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('teacher-admin.subjects.index')
                ->with('error', 'Failed to load subject creation form. Please try again.');
        }
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log all form data for debugging
            Log::info('Subject form data', [
                'all_data' => $request->all(),
                'is_batch_exists' => $request->has('is_batch'),
                'is_batch_value' => $request->input('is_batch')
            ]);
            
            // Check if this is a batch entry - accept both string '1' and integer 1
            $isBatch = $request->has('is_batch') && ($request->is_batch == '1' || $request->is_batch == 1);
            
            if ($isBatch) {
                // Process batch subject creation
                Log::info('Processing batch subject creation', [
                    'is_batch' => $request->is_batch,
                    'batch_subjects_exists' => $request->has('batch_subjects'),
                    'batch_subjects_value' => $request->input('batch_subjects')
                ]);
                
                // Validate batch input
                $validated = $request->validate([
                    'batch_subjects' => 'required|string',
                ]);
                
                // Parse the batch input
                $subjects = [];
                $lines = explode("\n", trim($validated['batch_subjects']));
                $createdCount = 0;
                $errors = [];
                
                foreach ($lines as $index => $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    $parts = array_map('trim', explode(',', $line));
                    
                    // Check if we have at least the required parts
                    if (count($parts) < 3) {
                        $errors[] = "Line " . ($index + 1) . ": Invalid format, expected 'Name, Code, Grade Level'";
                        continue;
                    }
                    
                    // Ensure grade_level is an integer
                    $gradeLevel = trim($parts[2]);
                    // Remove "Grade " prefix if it exists
                    if (strpos(strtolower($gradeLevel), 'grade') === 0) {
                        $gradeLevel = trim(substr($gradeLevel, 5));
                    }
                    
                    $subjectData = [
                        'name' => $parts[0],
                        'code' => $parts[1],
                        'grade_level' => (int)$gradeLevel,
                        'description' => count($parts) > 3 ? $parts[3] : null,
                        'school_id' => Auth::user()->school_id,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    try {
                        DB::table('subjects')->insert($subjectData);
                        $createdCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Line " . ($index + 1) . ": " . $e->getMessage();
                    }
                }
                
                Log::info('Batch subject creation completed', [
                    'created' => $createdCount,
                    'errors' => count($errors),
                ]);
                
                $message = "$createdCount subjects created successfully.";
                if (count($errors) > 0) {
                    $message .= " " . count($errors) . " errors occurred.";
                    Log::warning('Batch subject creation errors', ['errors' => $errors]);
                    return redirect()->route('teacher-admin.subjects.index')
                        ->with('warning', $message)
                        ->with('errors', $errors);
                }
                
                return redirect()->route('teacher-admin.subjects.index')
                    ->with('success', $message);
            } else {
                // Process single subject creation (original code)
                // Validate the request
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'code' => 'nullable|string|max:50',
                    'grade_level' => 'nullable|integer',
                    'description' => 'nullable|string',
                    'is_mapeh' => 'sometimes|boolean',
                    'music_weight' => 'required_if:is_mapeh,1|numeric|min:0|max:100',
                    'arts_weight' => 'required_if:is_mapeh,1|numeric|min:0|max:100',
                    'pe_weight' => 'required_if:is_mapeh,1|numeric|min:0|max:100',
                    'health_weight' => 'required_if:is_mapeh,1|numeric|min:0|max:100',
                ]);
                
                // If MAPEH subject, check that weights add up to 100%
                if ($request->has('is_mapeh') && $request->is_mapeh) {
                    $totalWeight = $request->music_weight + $request->arts_weight + $request->pe_weight + $request->health_weight;
                    if (abs($totalWeight - 100) > 0.01) {
                        return back()->withInput()->with('error', 'The total of all MAPEH component weights must equal 100%. Currently: ' . $totalWeight . '%');
                    }
                }
                
                // Begin transaction
                DB::beginTransaction();
                
                // Create the subject
                $subject = new Subject([
                    'name' => $request->name,
                    'code' => $request->code ?: strtoupper(substr($request->name, 0, 4)),
                    'grade_level' => $request->grade_level,
                    'description' => $request->description,
                    'school_id' => Auth::user()->school_id,
                    'is_active' => true,
                    'is_component' => false,
                ]);
                
                $subject->save();
                
                // If it's a MAPEH subject, create the components
                if ($request->has('is_mapeh') && $request->is_mapeh) {
                    $this->createMapehComponents($subject->id, $request);
                }
                
                // Commit transaction
                DB::commit();
                
                return redirect()->route('teacher-admin.subjects.index')
                    ->with('success', 'Subject created successfully.');
            }
        } catch (\Exception $e) {
            // Rollback the transaction if it was started
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            Log::error('Error creating subject: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'Failed to create subject: ' . $e->getMessage());
        }
    }

    /**
     * Create MAPEH component subjects
     */
    private function createMAPEHComponents($parentSubjectId, Request $request)
    {
        // Default weights
        $weights = [
            'music' => $request->filled('music_weight') ? $request->music_weight : 25,
            'arts' => $request->filled('arts_weight') ? $request->arts_weight : 25,
            'pe' => $request->filled('pe_weight') ? $request->pe_weight : 25,
            'health' => $request->filled('health_weight') ? $request->health_weight : 25,
        ];
        
        // Normalize weights to ensure they sum to 100%
        $totalWeight = array_sum($weights);
        if ($totalWeight != 100) {
            foreach ($weights as $key => $weight) {
                $weights[$key] = ($weight / $totalWeight) * 100;
            }
        }
        
        // Create the component subjects
        $components = [
            [
                'name' => 'Music',
                'code' => 'MUSIC',
                'description' => 'Music component of MAPEH',
                'weight' => $weights['music']
            ],
            [
                'name' => 'Arts',
                'code' => 'ARTS',
                'description' => 'Arts component of MAPEH',
                'weight' => $weights['arts']
            ],
            [
                'name' => 'Physical Education',
                'code' => 'PE',
                'description' => 'Physical Education component of MAPEH',
                'weight' => $weights['pe']
            ],
            [
                'name' => 'Health',
                'code' => 'HEALTH',
                'description' => 'Health component of MAPEH',
                'weight' => $weights['health']
            ]
        ];
        
        $parentSubject = Subject::find($parentSubjectId);
        
        foreach ($components as $component) {
            DB::table('subjects')->insert([
                'name' => $component['name'],
                'code' => $component['code'],
                'description' => $component['description'],
                'grade_level' => $parentSubject->grade_level,
                'school_id' => Auth::user()->school_id,
                'is_active' => true,
                'is_component' => true,
                'parent_subject_id' => $parentSubjectId,
                'component_weight' => $component['weight'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Display the specified subject.
     */
    public function show(Subject $subject)
    {
        try {
            // Authorize the request
            $this->authorize('view', $subject);
            
            // Load relationships
            $subject->load(['sections', 'teachers']);
            
            // Get teachers for the same school
            $teachers = User::where('school_id', Auth::user()->school_id)
                ->where('role', 'teacher')
                ->get();
    
            return view('teacher_admin.subjects.show', compact('subject', 'teachers'));
        } catch (\Exception $e) {
            Log::error('Error viewing subject: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('teacher-admin.subjects.index')
                ->with('error', 'Failed to view subject details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        try {
            // Authorize the request
            $this->authorize('update', $subject);
            
            // Get grade levels with fallback
            $school = Auth::user()->school;
            $gradeLevels = [];
            
            if ($school) {
                // Parse grade levels from school settings
                $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels : 
                             (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
                
                // If still empty, use default grades
                if (empty($gradeLevels)) {
                    $gradeLevels = range(7, 12);
                }
            } else {
                // Default grade levels
                $gradeLevels = range(7, 12);
            }
    
            return view('teacher_admin.subjects.edit', compact('subject', 'gradeLevels'));
        } catch (\Exception $e) {
            Log::error('Error loading subject edit form: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('teacher-admin.subjects.index')
                ->with('error', 'Failed to load subject edit form. Please try again.');
        }
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        try {
            // Authorize the request
            $this->authorize('update', $subject);
            
            // Validate the input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'grade_level' => 'nullable|string',
            ]);
            
            Log::info('Updating subject', ['subject_id' => $subject->id, 'data' => $validated]);
            
            // Process grade level - extract just the number if it contains "Grade X"
            $gradeLevel = $request->grade_level;
            if ($gradeLevel && preg_match('/Grade\s+(\d+)/i', $gradeLevel, $matches)) {
                $gradeLevel = $matches[1]; // Extract just the number
            }
            
            Log::info('Processed grade level', ['original' => $request->grade_level, 'processed' => $gradeLevel]);
            
            // Begin transaction
            DB::beginTransaction();
            
            // Update the subject using DB query builder
            DB::table('subjects')
                ->where('id', $subject->id)
                ->update([
                    'name' => $request->name,
                    'code' => $request->code,
                    'description' => $request->description,
                    'grade_level' => $gradeLevel,
                    'updated_at' => now(),
                ]);
            
            // Log success
            Log::info('Subject updated successfully', ['subject_id' => $subject->id]);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('teacher-admin.subjects.index')
                ->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Failed to update subject: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'Failed to update subject: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        try {
            // Authorize the request
            $this->authorize('delete', $subject);
            
            Log::info('Deleting subject', ['subject_id' => $subject->id]);
            
            // Begin transaction
            DB::beginTransaction();
            
            // Check if the subject is assigned to any sections
            $sectionsCount = DB::table('section_subject')
                ->where('subject_id', $subject->id)
                ->count();
                
            if ($sectionsCount > 0) {
                // Detach from all sections
                DB::table('section_subject')
                    ->where('subject_id', $subject->id)
                    ->delete();
                
                Log::info('Detached subject from all sections', ['subject_id' => $subject->id, 'sections_count' => $sectionsCount]);
            }
            
            // Delete the subject
            DB::table('subjects')
                ->where('id', $subject->id)
                ->delete();
            
            // Log success
            Log::info('Subject deleted successfully', ['subject_id' => $subject->id]);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('teacher-admin.subjects.index')
                ->with('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Failed to delete subject: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Failed to delete subject: ' . $e->getMessage());
        }
    }

    /**
     * Assign teachers to sections for this subject.
     */
    public function assignTeachers(Request $request, Subject $subject)
    {
        try {
            // Authorize the request
            $this->authorize('update', $subject);
            
            // Validate the input
            $validated = $request->validate([
                'sections' => 'required|array',
                'sections.*.section_id' => 'required|exists:sections,id',
                'sections.*.teacher_id' => 'required|exists:users,id',
            ]);
            
            Log::info('Assigning teachers to subject sections', [
                'subject_id' => $subject->id, 
                'sections' => $request->sections
            ]);
            
            // Begin transaction
            DB::beginTransaction();
            
            // Update teacher assignments for each section
            foreach ($request->sections as $section) {
                // Check if the relationship already exists
                $exists = DB::table('section_subject')
                    ->where('section_id', $section['section_id'])
                    ->where('subject_id', $subject->id)
                    ->exists();
                
                if ($exists) {
                    // Update existing relationship
                    DB::table('section_subject')
                        ->where('section_id', $section['section_id'])
                        ->where('subject_id', $subject->id)
                        ->update([
                            'teacher_id' => $section['teacher_id'],
                            'updated_at' => now(),
                        ]);
                } else {
                    // Create new relationship
                    DB::table('section_subject')->insert([
                        'section_id' => $section['section_id'],
                        'subject_id' => $subject->id,
                        'teacher_id' => $section['teacher_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            // Log success
            Log::info('Teachers assigned to subject sections successfully', ['subject_id' => $subject->id]);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('teacher-admin.subjects.show', $subject)
                ->with('success', 'Teachers assigned successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Failed to assign teachers: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Failed to assign teachers: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle the active status of a subject.
     */
    public function toggleStatus(Subject $subject)
    {
        try {
            // Authorize the request
            $this->authorize('update', $subject);
            
            // Toggle the is_active field
            $newStatus = !$subject->is_active;
            
            // Update using query builder
            DB::table('subjects')
                ->where('id', $subject->id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);
            
            // Log success
            $statusText = $newStatus ? 'activated' : 'deactivated';
            Log::info("Subject {$statusText}", ['subject_id' => $subject->id]);
            
            return redirect()->route('teacher-admin.subjects.index')
                ->with('success', "Subject {$statusText} successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to toggle subject status: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Failed to update subject status: ' . $e->getMessage());
        }
    }

    /**
     * Download the template file for batch entry
     */
    public function downloadTemplate()
    {
        return Excel::download(new SubjectsTemplateExport, 'subjects_template.xlsx');
    }

    /**
     * Handle batch store of subjects
     */
    public function batchStore(Request $request)
    {
        try {
            $request->validate([
                'batch_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
            ]);

            Excel::import(new SubjectsImport, $request->file('batch_file'));

            return redirect()->route('teacher-admin.subjects.index')
                ->with('success', 'Subjects imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing subjects: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('teacher-admin.subjects.index')
                ->with('error', 'Error importing subjects. Please check the file format and try again.');
        }
    }
}
