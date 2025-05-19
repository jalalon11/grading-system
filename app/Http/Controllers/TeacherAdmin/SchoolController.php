<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Section;
use App\Models\Subject;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
    /**
     * Display the school overview with teachers, sections, and students.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $school = $user->school;

            // Get all teachers in the school
            $teachers = User::where('school_id', $user->school_id)
                ->where('role', 'teacher')
                ->get();

            // Get sections where each teacher is the adviser
            foreach ($teachers as $teacher) {
                $teacher->load(['sections' => function($query) use ($teacher) {
                    $query->where('adviser_id', $teacher->id)->withCount('students');
                }]);
            }

            // Get active registration keys for teachers in this school
            $registrationKeys = \App\Models\RegistrationKey::where('school_id', $user->school_id)
                ->where('is_master', false)
                ->where('is_used', false)
                ->where('key_type', 'teacher')
                ->where(function($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', \Carbon\Carbon::now());
                })
                ->with('temporaryKey')
                ->get()
                ->filter(function($key) {
                    // Only include keys that have a non-expired temporary key
                    return $key->temporaryKey &&
                           ($key->temporaryKey->expires_at === null ||
                            $key->temporaryKey->expires_at->isAfter(\Carbon\Carbon::now()));
                });

            // Get teaching assignments for each teacher
            $teachingAssignments = [];
            foreach ($teachers as $teacher) {
                $assignments = DB::table('section_subject')
                    ->where('teacher_id', $teacher->id)
                    ->join('sections', 'section_subject.section_id', '=', 'sections.id')
                    ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
                    ->select(
                        'sections.id as section_id',
                        'sections.name as section_name',
                        'sections.grade_level',
                        'subjects.id as subject_id',
                        'subjects.name as subject_name',
                        'subjects.code as subject_code'
                    )
                    ->get();

                $teachingAssignments[$teacher->id] = $assignments;
            }

            // Get all sections in the school with their students
            $sections = Section::where('school_id', $user->school_id)
                ->with(['adviser', 'students'])
                ->get();

            // Organize students by section, sorted alphabetically by surname with disabled students at the end
            // Also calculate gender counts for each section and grade level
            $studentsBySection = [];
            $sectionStats = [];
            $gradeLevelStats = [];

            foreach ($sections as $section) {
                // Get active and inactive students separately
                $activeStudents = $section->students->where('is_active', true);
                $inactiveStudents = $section->students->where('is_active', false);

                // Sort active students alphabetically by surname
                $sortedActiveStudents = $activeStudents->sortBy(function($student) {
                    // Extract surname from surname_first format
                    $nameParts = explode(',', $student->surname_first);
                    return trim($nameParts[0]); // Return surname for sorting
                });

                // Sort inactive students alphabetically by surname
                $sortedInactiveStudents = $inactiveStudents->sortBy(function($student) {
                    // Extract surname from surname_first format
                    $nameParts = explode(',', $student->surname_first);
                    return trim($nameParts[0]); // Return surname for sorting
                });

                // Combine sorted active students followed by sorted inactive students
                $studentsBySection[$section->id] = $sortedActiveStudents->values()->concat($sortedInactiveStudents->values());

                // Calculate gender statistics for this section
                $totalStudents = $section->students->count();
                $maleStudents = $section->students->where('gender', 'Male')->count();
                $femaleStudents = $section->students->where('gender', 'Female')->count();
                $activeStudentsCount = $activeStudents->count();
                $inactiveStudentsCount = $inactiveStudents->count();

                $sectionStats[$section->id] = [
                    'total' => $totalStudents,
                    'male' => $maleStudents,
                    'female' => $femaleStudents,
                    'active' => $activeStudentsCount,
                    'inactive' => $inactiveStudentsCount
                ];

                // Aggregate statistics by grade level
                $gradeLevel = $section->grade_level;
                if (!isset($gradeLevelStats[$gradeLevel])) {
                    $gradeLevelStats[$gradeLevel] = [
                        'total' => 0,
                        'male' => 0,
                        'female' => 0,
                        'active' => 0,
                        'inactive' => 0,
                        'sections' => 0
                    ];
                }

                // Add this section's stats to the grade level totals
                $gradeLevelStats[$gradeLevel]['total'] += $totalStudents;
                $gradeLevelStats[$gradeLevel]['male'] += $maleStudents;
                $gradeLevelStats[$gradeLevel]['female'] += $femaleStudents;
                $gradeLevelStats[$gradeLevel]['active'] += $activeStudentsCount;
                $gradeLevelStats[$gradeLevel]['inactive'] += $inactiveStudentsCount;
                $gradeLevelStats[$gradeLevel]['sections']++;
            }

            return view('teacher_admin.school.index', compact(
                'school',
                'teachers',
                'teachingAssignments',
                'sections',
                'studentsBySection',
                'sectionStats',
                'gradeLevelStats',
                'registrationKeys'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading school overview: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Error loading school overview. Please try again or contact support.');
        }
    }

    /**
     * Show the form for editing the school information.
     */
    public function edit()
    {
        try {
            $user = Auth::user();
            $school = $user->school;

            // Check if school details can be updated (60-day restriction)
            $canUpdate = $school->canUpdateDetails();

            return view('teacher_admin.school.edit', compact('school', 'canUpdate'));
        } catch (\Exception $e) {
            Log::error('Error loading school edit form: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Error loading school edit form. Please try again or contact support.');
        }
    }

    /**
     * Update the school information.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            $school = $user->school;

            // Check if school details can be updated (60-day restriction)
            if (!$school->canUpdateDetails()) {
                return back()->with('error', 'School information can only be updated once every 60 days. You can update again in ' . $school->days_until_next_update . ' days.')
                    ->withInput();
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'principal' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Update school information
            $school->name = $request->name;
            $school->principal = $request->principal;
            $school->address = $request->address;

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');

                try {
                    // Delete old logo if exists
                    if ($school->logo_path) {
                        Storage::disk('r2')->delete($school->logo_path);
                    }

                    // Store the file in R2 storage
                    $path = $logo->store('school_logos', 'r2');
                    $school->logo_path = $path;

                    Log::info('School logo uploaded successfully', [
                        'path' => $path,
                        'disk' => 'r2'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to upload school logo', [
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }

            // Set the last update timestamp
            $school->last_details_update_at = now();
            $school->save();

            return redirect()->route('teacher-admin.school.index')
                ->with('success', 'School information updated successfully. You can update it again after 60 days.');
        } catch (\Exception $e) {
            Log::error('Error updating school information: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Error updating school information. Please try again or contact support.')
                ->withInput();
        }
    }


}
