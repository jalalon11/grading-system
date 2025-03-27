<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Attendance;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::with(['school.schoolDivision'])
            ->where('role', 'teacher');

        // Handle search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Handle school filter
        if (request('school')) {
            $query->where('school_id', request('school'));
        }

        // Handle sorting
        $sort = request('sort', 'name');
        $order = request('order', 'asc');

        switch ($sort) {
            case 'name':
                $query->orderBy('name', $order);
                break;
            case 'school':
                $query->orderBy('school_id', $order);
                break;
            case 'created_at':
                $query->orderBy('created_at', $order);
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $teachers = $query->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        return view('admin.teachers.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'school_id' => 'required|exists:schools,id',
            'subjects' => 'nullable|string',
        ]);

        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'school_id' => $request->school_id,
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher = User::where('role', 'teacher')
                       ->with('school')
                       ->findOrFail($id);
                       
        // Get sections where this teacher is the adviser
        $sections = Section::where('adviser_id', $teacher->id)->get();
        
        // Get teacher's teaching assignments from section_subject table
        $teachingAssignments = DB::table('section_subject')
            ->where('teacher_id', $teacher->id)
            ->join('sections', 'section_subject.section_id', '=', 'sections.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select('sections.name as section_name', 'sections.grade_level', 'subjects.name as subject_name')
            ->get();

        return view('admin.teachers.show', compact('teacher', 'teachingAssignments', 'sections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $schools = School::all();
        return view('admin.teachers.edit', compact('teacher', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($teacher->id),
            ],
            'school_id' => 'required|exists:schools,id',
            'subjects' => 'nullable|string',
        ];
        
        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
        
        $request->validate($rules);
        
        // Update teacher data
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->school_id = $request->school_id;
        
        // Only update password if it's provided
        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }
        
        $teacher->save();
        
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $teacher = User::where('role', 'teacher')->findOrFail($id);
            
            // Handle sections where this teacher is adviser/homeroom teacher
            $sections = Section::where('adviser_id', $teacher->id)
                             ->get();
            
            foreach ($sections as $section) {
                $section->adviser_id = null;
                $section->save();
            }
            
            // Handle references in subject assignments - delete the records instead of nullifying
            DB::table('section_subject')
                ->where('teacher_id', $teacher->id)
                ->delete();
                
            // Handle subjects taught by the teacher
            Subject::where('user_id', $teacher->id)->update(['user_id' => null]);
            
            // Handle attendance records
            Attendance::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
            
            // Now try to delete the teacher
            $teacher->delete();
            
            DB::commit();
            
            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Unable to delete teacher: ' . $e->getMessage());
        }
    }

    /**
     * Reset password for a teacher
     */
    public function resetPassword(Request $request, string $id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        $teacher->password = Hash::make($request->password);
        $teacher->save();
        
        return redirect()->back()->with('success', 'Password has been reset successfully for ' . $teacher->name);
    }
}
