<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            
            return view('teacher.students.index', compact('students'));
        } catch (\Exception $e) {
            Log::error('Error in student index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('teacher.students.index', ['students' => collect()])
                ->with('error', 'Error loading students. Please contact administrator.');
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
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'section_id' => 'required|exists:sections,id',
            'address' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
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
    public function show(string $id)
    {
        // Get sections associated with the current teacher
        $sectionIds = Section::where('adviser_id', Auth::id())->pluck('id');
        
        // Find the student and ensure they belong to one of the teacher's sections
        $student = Student::whereIn('section_id', $sectionIds)
            ->with(['section', 'grades', 'attendances'])
            ->findOrFail($id);
        
        return view('teacher.students.show', compact('student'));
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
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'required|date',
            'section_id' => 'required|exists:sections,id',
            'address' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
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
}
