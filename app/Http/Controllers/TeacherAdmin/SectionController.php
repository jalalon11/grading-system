<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SectionsImport;
use App\Exports\SectionsTemplateExport;

class SectionController extends Controller
{
    /**
     * Display a listing of the sections.
     */
    public function index()
    {
        try {
            $sections = Section::where('school_id', Auth::user()->school_id)
                ->with(['adviser', 'subjects'])
                ->withCount('students')
                ->orderByRaw('CAST(REPLACE(REPLACE(REPLACE(grade_level, "Grade ", ""), "grade ", ""), " ", "") AS UNSIGNED)')
                ->get();

            return view('teacher_admin.sections.index', compact('sections'));
        } catch (\Exception $e) {
            Log::error('Error loading sections: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return view('teacher_admin.sections.index', ['sections' => collect()])
                ->with('error', 'Error loading sections. Please try again or contact support.');
        }
    }

    /**
     * Show the form for creating a new section.
     */
    public function create()
    {
        try {
            $teachers = User::where('school_id', Auth::user()->school_id)
                ->where('role', 'teacher')
                ->get();

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
            
            return view('teacher_admin.sections.create', compact('teachers', 'gradeLevels'));
        } catch (\Exception $e) {
            Log::error('Error loading section creation form: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('teacher-admin.sections.index')
                ->with('error', 'Failed to load section creation form. Please try again.');
        }
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request)
    {
        try {
            // Debug - log entire request
            Log::info('Section store - complete request', [
                'method' => $request->method(),
                'url' => $request->url(),
                'all' => $request->all(),
                'is_batch_exists' => $request->has('is_batch'),
                'is_batch_value' => $request->input('is_batch'),
                'batch_sections_exists' => $request->has('batch_sections'),
                'batch_sections_hidden_exists' => $request->has('batch_sections_hidden'),
            ]);
            
            // Check if this is a batch entry - accept both string '1' and integer 1
            $isBatch = $request->has('is_batch') && ($request->is_batch == '1' || $request->is_batch == 1);
            
            if ($isBatch) {
                // Process batch section creation
                Log::info('Processing batch section creation', [
                    'is_batch' => $request->is_batch,
                    'batch_sections_exists' => $request->has('batch_sections'),
                    'batch_sections_hidden_exists' => $request->has('batch_sections_hidden'),
                    'batch_sections_value' => $request->input('batch_sections'),
                    'batch_sections_hidden_value' => $request->input('batch_sections_hidden')
                ]);
                
                // Get batch data from either parameter
                $batchSectionsData = null;
                
                if ($request->has('batch_sections_json') && !empty($request->input('batch_sections_json'))) {
                    // Handle JSON-encoded data
                    $batchSectionsData = json_decode($request->input('batch_sections_json'), true);
                    Log::info('Using JSON-encoded batch_sections parameter', ['data' => $batchSectionsData]);
                } elseif ($request->has('batch_sections') && !empty($request->input('batch_sections'))) {
                    $batchSectionsData = $request->input('batch_sections');
                    Log::info('Using batch_sections parameter', ['data' => $batchSectionsData]);
                } elseif ($request->has('batch_sections_hidden') && !empty($request->input('batch_sections_hidden'))) {
                    $batchSectionsData = urldecode($request->input('batch_sections_hidden'));
                    Log::info('Using batch_sections_hidden parameter', ['data' => $batchSectionsData]);
                }
                
                // Debug - check if batch data was found
                if (empty($batchSectionsData)) {
                    return back()->withInput()->with('error', 'Batch sections data is missing. Please try again.');
                }
                
                // Use the batch data for validation and processing
                $validated = ['batch_sections' => $batchSectionsData];
                
                // Parse the batch input
                $sections = [];
                
                // Convert the batch data to a string if it's not already one
                if (is_array($batchSectionsData)) {
                    $batchSectionsData = $batchSectionsData;
                }
                
                $lines = explode("\n", trim($batchSectionsData));
                $createdCount = 0;
                $errors = [];
                
                Log::info('Batch parsing', [
                    'lines_count' => count($lines),
                    'first_line' => count($lines) > 0 ? $lines[0] : 'none',
                    'data_type' => gettype($batchSectionsData)
                ]);
                
                // First, validate all inputs before adding to database
                $allValid = true;
                $allTeachersValid = true;
                $processedLines = [];
                
                foreach ($lines as $index => $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    Log::info('Processing raw line', ['line' => $line]);
                    
                    // Split by comma and trim each part to handle extra spaces
                    // Use a safer explode to handle potential commas in quotes (not perfect but safer)
                    $parts = array_map('trim', explode(',', $line));
                    
                    // Filter out empty parts that might result from double commas
                    $parts = array_values(array_filter($parts, function($part) {
                        return !empty($part);
                    }));
                    
                    Log::info('Parsed line ' . ($index + 1), [
                        'line' => $line,
                        'parts' => $parts,
                        'parts_count' => count($parts),
                        'values' => count($parts) >= 4 ? [
                            'name' => $parts[0],
                            'grade_level' => $parts[1],
                            'adviser_id' => $parts[2],
                            'school_year' => $parts[3]
                        ] : 'insufficient parts'
                    ]);
                    
                    // Check if we have all the required parts
                    if (count($parts) < 4) {
                        $errors[] = "Line " . ($index + 1) . ": Invalid format, expected 'Name, Grade Level, Adviser ID, School Year'. Found " . count($parts) . " parts instead of 4.";
                        $allValid = false;
                        continue;
                    }
                    
                    // Extract data
                    $name = $parts[0];
                    $gradeLevel = $parts[1];
                    
                    // Ensure adviser ID is a valid integer
                    $adviserId = trim($parts[2]);
                    if (!is_numeric($adviserId)) {
                        $errors[] = "Line " . ($index + 1) . ": Adviser ID must be a number, got '{$adviserId}'";
                        $allValid = false;
                        continue;
                    }
                    $adviserId = (int)$adviserId;
                    
                    $schoolYear = $parts[3];
                    
                    // Validate adviser exists and belongs to the same school
                    $teacher = User::where('id', $adviserId)
                        ->where('school_id', Auth::user()->school_id)
                        ->where('role', 'teacher')
                        ->first();
                    
                    if (!$teacher) {
                        $errors[] = "Line " . ($index + 1) . ": Invalid adviser ID {$adviserId}. No teacher with this ID found in your school.";
                        $allValid = false;
                        $allTeachersValid = false;
                        continue;
                    }
                    
                    // Add valid section to be processed
                    $processedLines[] = [
                        'name' => $name,
                        'grade_level' => $gradeLevel,
                        'adviser_id' => $adviserId,
                        'school_id' => Auth::user()->school_id,
                        'school_year' => $schoolYear,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                // If validation failed, return with all errors
                if (!$allValid) {
                    Log::warning('Batch section creation validation failed', [
                        'errors' => $errors,
                        'all_teachers_valid' => $allTeachersValid
                    ]);
                    
                    // Add extra information if teacher IDs are invalid
                    if (!$allTeachersValid) {
                        // Get available teacher IDs
                        $availableTeachers = User::where('school_id', Auth::user()->school_id)
                            ->where('role', 'teacher')
                            ->get(['id', 'name']);
                        
                        $errors[] = "Available teacher IDs in your system: " . $availableTeachers->map(function($teacher) {
                            return "ID {$teacher->id}: {$teacher->name}";
                        })->implode(', ');
                    }
                    
                    return back()
                        ->withInput()
                        ->with('error', 'Batch entry validation failed. Please check the errors below.')
                        ->with('batch_errors', $errors);
                }
                
                // Begin transaction
                DB::beginTransaction();
                
                try {
                    // Now insert all valid records
                    foreach ($processedLines as $sectionData) {
                        DB::table('sections')->insert($sectionData);
                        $createdCount++;
                    }
                    
                    // Commit the transaction
                    DB::commit();
                    
                    Log::info('Batch section creation completed successfully', [
                        'created' => $createdCount
                    ]);
                    
                    return redirect()->route('teacher-admin.sections.index')
                        ->with('success', "$createdCount sections created successfully.");
                } catch (\Exception $e) {
                    // Rollback the transaction on error
                    DB::rollBack();
                    
                    Log::error('Error during batch section insertion', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    return back()
                        ->withInput()
                        ->with('error', 'Database error: ' . $e->getMessage());
                }
            } else {
                // Process single section creation (original code)
                // Validate the input
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'grade_level' => 'required|string',
                    'adviser_id' => 'required|exists:users,id',
                    'school_year' => 'required|string|max:20',
                ]);
                
                Log::info('Creating new section', ['data' => $validated]);
                
                // Begin transaction
                DB::beginTransaction();
                
                // Create the section directly with DB query builder
                $sectionId = DB::table('sections')->insertGetId([
                    'name' => $request->name,
                    'grade_level' => $request->grade_level,
                    'adviser_id' => $request->adviser_id,
                    'school_id' => Auth::user()->school_id,
                    'school_year' => $request->school_year,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Log success
                Log::info('Section created successfully', ['section_id' => $sectionId]);
                
                // Commit transaction
                DB::commit();
                
                return redirect()->route('teacher-admin.sections.index')
                    ->with('success', 'Section created successfully.');
            }
        } catch (\Exception $e) {
            // Rollback transaction if it was started
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            Log::error('Failed to create section: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'Failed to create section: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified section.
     */
    public function show(Section $section)
    {
        try {
            // Authorize the request
            $this->authorize('view', $section);
            
            // Load relationships
            $section->load(['adviser', 'subjects']);
            $section->loadCount('students');
            
            // Get teachers for the same school
            $teachers = User::where('school_id', Auth::user()->school_id)
                ->where('role', 'teacher')
                ->get();
                
            // Get active subjects for the same school
            $subjects = Subject::where('school_id', Auth::user()->school_id)
                ->where('is_active', true)
                ->get();
    
            return view('teacher_admin.sections.show', compact('section', 'teachers', 'subjects'));
        } catch (\Exception $e) {
            Log::error('Error viewing section: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('teacher-admin.sections.index')
                ->with('error', 'Failed to view section details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Section $section)
    {
        try {
            // Authorize the request
            $this->authorize('update', $section);
            
            // Get teachers for the same school
            $teachers = User::where('school_id', Auth::user()->school_id)
                ->where('role', 'teacher')
                ->get();
    
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
    
            return view('teacher_admin.sections.edit', compact('section', 'teachers', 'gradeLevels'));
        } catch (\Exception $e) {
            Log::error('Error loading section edit form: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('teacher-admin.sections.index')
                ->with('error', 'Failed to load section edit form. Please try again.');
        }
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, Section $section)
    {
        try {
            // Authorize the request
            $this->authorize('update', $section);
            
            // Validate the input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'grade_level' => 'required|string',
                'adviser_id' => 'required|exists:users,id',
                'school_year' => 'required|string|max:20',
            ]);
            
            Log::info('Updating section', ['section_id' => $section->id, 'data' => $validated]);
            
            // Begin transaction
            DB::beginTransaction();
            
            // Update the section using DB query builder
            DB::table('sections')
                ->where('id', $section->id)
                ->update([
                    'name' => $request->name,
                    'grade_level' => $request->grade_level,
                    'adviser_id' => $request->adviser_id,
                    'school_year' => $request->school_year,
                    'updated_at' => now(),
                ]);
            
            // Log success
            Log::info('Section updated successfully', ['section_id' => $section->id]);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('teacher-admin.sections.index')
                ->with('success', 'Section updated successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Failed to update section: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'Failed to update section: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy(Section $section)
    {
        try {
            // Authorize the request
            $this->authorize('delete', $section);
            
            Log::info('Deleting section', ['section_id' => $section->id]);
            
            // Begin transaction
            DB::beginTransaction();
            
            // First, detach all subject relationships
            DB::table('section_subject')
                ->where('section_id', $section->id)
                ->delete();
            
            // Then delete the section
            DB::table('sections')
                ->where('id', $section->id)
                ->delete();
            
            // Log success
            Log::info('Section deleted successfully', ['section_id' => $section->id]);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('teacher-admin.sections.index')
                ->with('success', 'Section deleted successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Failed to delete section: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Failed to delete section: ' . $e->getMessage());
        }
    }

    /**
     * Assign subjects to the section.
     */
    public function assignSubjects(Request $request, Section $section)
    {
        try {
            // Authorize the request
            $this->authorize('update', $section);
            
            // Validate the input
            $validated = $request->validate([
                'subjects' => 'required|array',
                'subjects.*.subject_id' => 'required|exists:subjects,id',
                'subjects.*.teacher_id' => 'required|exists:users,id',
            ]);
            
            Log::info('Assigning subjects to section', [
                'section_id' => $section->id, 
                'subjects' => $request->subjects
            ]);
            
            // Begin transaction
            DB::beginTransaction();
            
            // Instead of clearing all existing subjects, we'll determine which ones to add or update
            $existingSubjectIds = $section->subjects->pluck('id')->toArray();
            $newSubjectIds = collect($request->subjects)->pluck('subject_id')->toArray();
            
            // Loop through new subject assignments
            foreach ($request->subjects as $subject) {
                // Check if this subject is already assigned to this section
                $existingPivot = DB::table('section_subject')
                    ->where('section_id', $section->id)
                    ->where('subject_id', $subject['subject_id'])
                    ->first();
                
                if ($existingPivot) {
                    // Update the existing subject-teacher assignment
                    DB::table('section_subject')
                        ->where('section_id', $section->id)
                        ->where('subject_id', $subject['subject_id'])
                        ->update([
                            'teacher_id' => $subject['teacher_id'],
                            'updated_at' => now(),
                        ]);
                } else {
                    // Insert a new subject-teacher assignment
                    DB::table('section_subject')->insert([
                        'section_id' => $section->id,
                        'subject_id' => $subject['subject_id'],
                        'teacher_id' => $subject['teacher_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            // Log success
            Log::info('Subjects assigned successfully', ['section_id' => $section->id]);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('teacher-admin.sections.show', $section)
                ->with('success', 'Subjects assigned successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Failed to assign subjects: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Failed to assign subjects: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle the active status of a section.
     */
    public function toggleStatus(Section $section)
    {
        try {
            // Authorize the request
            $this->authorize('update', $section);
            
            // Toggle the is_active field
            $newStatus = !$section->is_active;
            
            // Update using query builder
            DB::table('sections')
                ->where('id', $section->id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);
            
            // Log success
            $statusText = $newStatus ? 'activated' : 'deactivated';
            Log::info("Section {$statusText}", ['section_id' => $section->id]);
            
            return redirect()->route('teacher-admin.sections.index')
                ->with('success', "Section {$statusText} successfully.");
        } catch (\Exception $e) {
            Log::error('Failed to toggle section status: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Failed to update section status: ' . $e->getMessage());
        }
    }

    /**
     * Update the classroom adviser for a section.
     */
    public function updateAdviser(Request $request, Section $section)
    {
        try {
            // Authorize the request
            $this->authorize('update', $section);
            
            // Validate the input
            $validated = $request->validate([
                'adviser_id' => 'required|exists:users,id'
            ]);
            
            Log::info('Updating section adviser', [
                'section_id' => $section->id,
                'new_adviser_id' => $request->adviser_id
            ]);
            
            // Begin transaction
            DB::beginTransaction();
            
            // Update the section adviser
            DB::table('sections')
                ->where('id', $section->id)
                ->update([
                    'adviser_id' => $request->adviser_id,
                    'updated_at' => now(),
                ]);
            
            // Log success
            Log::info('Section adviser updated successfully', ['section_id' => $section->id]);
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('teacher-admin.sections.show', $section)
                ->with('success', 'Section adviser has been updated successfully.');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Failed to update section adviser: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Failed to update section adviser: ' . $e->getMessage());
        }
    }

    /**
     * Download the template file for batch entry
     */
    public function downloadTemplate()
    {
        return Excel::download(new SectionsTemplateExport, 'sections_template.xlsx');
    }

    /**
     * Handle batch store of sections
     */
    public function batchStore(Request $request)
    {
        try {
            $request->validate([
                'batch_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
            ]);

            Excel::import(new SectionsImport, $request->file('batch_file'));

            return redirect()->route('teacher-admin.sections.index')
                ->with('success', 'Sections imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing sections: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('teacher-admin.sections.index')
                ->with('error', 'Error importing sections. Please check the file format and try again.');
        }
    }
}
