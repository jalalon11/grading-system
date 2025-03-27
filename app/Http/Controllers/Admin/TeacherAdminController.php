<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher')
                     ->where('is_teacher_admin', true);

        if ($request->has('school') && $request->school) {
            $query->where('school_id', $request->school);
        }

        $teacherAdmins = $query->with('school')->latest()->paginate(10);
        $schools = School::all();

        return view('admin.teacher_admins.index', compact('teacherAdmins', 'schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.teacher_admins.form', compact('schools', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => [
                'required',
                'exists:users,id',
                Rule::unique('users', 'id')->where(function ($query) use ($request) {
                    return $query->where('is_teacher_admin', true)
                                ->where('school_id', $request->school_id);
                })
            ],
        ]);

        try {
            DB::beginTransaction();

            // Check if school already has 2 teacher admins
            $adminCount = User::where('school_id', $request->school_id)
                            ->where('is_teacher_admin', true)
                            ->count();

            if ($adminCount >= 2) {
                return back()->with('error', 'This school already has the maximum number of teacher admins (2).');
            }

            // Update the teacher to teacher admin
            $teacher = User::findOrFail($request->teacher_id);
            
            // Check if teacher belongs to the selected school
            if ($teacher->school_id != $request->school_id) {
                return back()->with('error', 'The selected teacher does not belong to the selected school.');
            }

            $teacher->is_teacher_admin = true;
            $teacher->save();

            DB::commit();

            return redirect()->route('admin.teacher-admins.index')
                           ->with('success', "{$teacher->name} has been assigned as a Teacher Admin.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while assigning the Teacher Admin role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacherAdmin)
    {
        if (!$teacherAdmin->is_teacher_admin) {
            abort(404);
        }

        $schools = School::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.teacher_admins.form', compact('teacherAdmin', 'schools', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $teacherAdmin)
    {
        if ($teacherAdmin->role !== 'teacher_admin') {
            abort(404);
        }

        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);

        try {
            DB::beginTransaction();

            // If school is changing, check admin count for new school
            if ($request->school_id !== $teacherAdmin->school_id) {
                $adminCount = User::where('school_id', $request->school_id)
                                ->where('role', 'teacher_admin')
                                ->count();

                if ($adminCount >= 2) {
                    return back()->with('error', 'The selected school already has the maximum number of teacher admins (2).');
                }
            }

            $teacherAdmin->school_id = $request->school_id;
            $teacherAdmin->save();

            DB::commit();

            return redirect()->route('admin.teacher-admins.index')
                           ->with('success', "Teacher Admin information updated successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating the Teacher Admin.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacherAdmin)
    {
        if (!$teacherAdmin->is_teacher_admin) {
            abort(404);
        }

        try {
            DB::beginTransaction();

            // Convert teacher admin back to regular teacher
            $teacherAdmin->is_teacher_admin = false;
            $teacherAdmin->save();

            DB::commit();

            return redirect()->route('admin.teacher-admins.index')
                           ->with('success', "{$teacherAdmin->name} has been removed from Teacher Admin role.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while removing the Teacher Admin role.');
        }
    }

    /**
     * Get teachers for a specific school
     */
    public function getTeachers(School $school)
    {
        $teachers = User::where('school_id', $school->id)
                       ->where('role', 'teacher')
                       ->where('is_teacher_admin', false)
                       ->select('id', 'name')
                       ->get();

        return response()->json($teachers);
    }
}
