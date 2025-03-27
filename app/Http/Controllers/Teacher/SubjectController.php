<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::where('user_id', Auth::id())
            ->orderBy('name')
            ->paginate(10);
        
        return view('teacher.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::where('user_id', Auth::id())->get();
        return view('teacher.subjects.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'grade_level' => 'required|integer|between:1,12',
            'description' => 'nullable|string',
            'section_id' => 'required|exists:sections,id',
        ]);
        
        // Ensure the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
                          ->where('user_id', Auth::id())
                          ->firstOrFail();
        
        $validated['user_id'] = Auth::id();
        
        Subject::create($validated);
        
        return redirect()->route('teacher.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::where('user_id', Auth::id())
            ->with('students.section')
            ->findOrFail($id);
        
        return view('teacher.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject = Subject::where('user_id', Auth::id())->findOrFail($id);
        $sections = Section::where('user_id', Auth::id())->get();
        
        return view('teacher.subjects.edit', compact('subject', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::where('user_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'grade_level' => 'required|integer|between:1,12',
            'description' => 'nullable|string',
            'section_id' => 'required|exists:sections,id',
        ]);
        
        // Ensure the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
                          ->where('user_id', Auth::id())
                          ->firstOrFail();
        
        $subject->update($validated);
        
        return redirect()->route('teacher.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::where('user_id', Auth::id())->findOrFail($id);
        
        // Check if the subject has related records before deleting
        if ($subject->students()->count() > 0 || $subject->grades()->count() > 0) {
            return redirect()->route('teacher.subjects.index')
                ->with('error', 'Cannot delete subject because it has related students or grades.');
        }
        
        $subject->delete();
        
        return redirect()->route('teacher.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
