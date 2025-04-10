<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolDivision;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = School::with(['schoolDivision', 'teachers']);

        // Handle search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Handle division filter
        if (request('division')) {
            $query->where('school_division_id', request('division'));
        }

        // Handle sorting
        $sort = request('sort', 'name');
        $order = request('order', 'asc');

        switch ($sort) {
            case 'name':
                $query->orderBy('name', $order);
                break;
            case 'division':
                $query->orderBy('school_division_id', $order);
                break;
            case 'created_at':
                $query->orderBy('created_at', $order);
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $schools = $query->get();

        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = SchoolDivision::all();
        return view('admin.schools.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the received data
        Log::info('School creation request data:', $request->all());
        
        // Validate school data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools',
            'address' => 'nullable|string',
            'principal' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_division_id' => 'required|exists:school_divisions,id',
            'grade_levels' => 'required|array|min:1',
            'grade_levels.*' => 'required|in:K,1,2,3,4,5,6,7,8,9,10,11,12',
            'teachers' => 'nullable|array',
            'teachers.*.name' => 'nullable|string|max:255',
            'teachers.*.email' => 'nullable|email|unique:users,email',
            'teachers.*.password' => 'nullable|string|min:6',
            'teachers.*.password_confirmation' => 'nullable|same:teachers.*.password',
            'teachers.*.subjects' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('School creation validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create school data array
            $schoolData = [
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
                'principal' => $request->principal,
                'grade_levels' => json_encode($request->grade_levels),
                'school_division_id' => $request->school_division_id,
            ];
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                
                try {
                    // Store the file in R2 storage
                    $path = $logo->store('school_logos', 'r2');
                    $schoolData['logo_path'] = $path;
                    
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

            // Create the school
            $school = School::create($schoolData);
            
            Log::info('Created school:', ['id' => $school->id, 'name' => $school->name]);

            // Create teachers for this school if present
            if (isset($request->teachers) && is_array($request->teachers)) {
                Log::info('Teachers data for school ' . $school->name . ':', $request->teachers);
                
                foreach ($request->teachers as $teacherData) {
                    // Skip empty teacher entries
                    if (empty($teacherData['name']) || empty($teacherData['email']) || empty($teacherData['password'])) {
                        Log::info('Skipping empty teacher data');
                        continue;
                    }
                    
                    $user = User::create([
                        'name' => $teacherData['name'],
                        'email' => $teacherData['email'],
                        'password' => Hash::make($teacherData['password']),
                        'role' => 'teacher',
                        'school_id' => $school->id,
                    ]);
                    
                    Log::info('Created teacher:', ['id' => $user->id, 'name' => $user->name, 'school_id' => $user->school_id]);
                }
            } else {
                Log::info('No teachers data for school ' . $school->name);
            }

            DB::commit();
            Log::info('Successfully completed school creation transaction');

            return redirect()->route('admin.schools.index')
                ->with('success', 'School created successfully' . 
                (isset($request->teachers) ? ' with teachers.' : '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School creation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $school = School::with(['schoolDivision'])->findOrFail($id);
        $teachers = User::where('role', 'teacher')
            ->where('is_teacher_admin', false)
            ->where('school_id', $school->id)
            ->get();
        $teacherAdmins = User::where('role', 'teacher')
            ->where('is_teacher_admin', true)
            ->where('school_id', $school->id)
            ->get();
            
        return view('admin.schools.show', compact('school', 'teachers', 'teacherAdmins'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $school = School::with('teachers')->findOrFail($id);
        $divisions = SchoolDivision::all();
        return view('admin.schools.edit', compact('school', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $school = School::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string', 
                'max:50',
                Rule::unique('schools')->ignore($school->id)
            ],
            'address' => 'nullable|string',
            'principal' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_division_id' => 'required|exists:school_divisions,id',
            'grade_levels' => 'required|array|min:1',
            'grade_levels.*' => 'required|in:K,1,2,3,4,5,6,7,8,9,10,11,12',
        ]);
        
        $updateData = [
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'principal' => $request->principal,
            'school_division_id' => $request->school_division_id,
            'grade_levels' => json_encode($request->grade_levels),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete the old logo if it exists
            if ($school->logo_path) {
                // Use the configured filesystem disk
                $disk = config('filesystems.disk'); // Get the default disk ('r2' in cloud)
                
                try {
                    Storage::disk($disk)->delete($school->logo_path);
                    Log::info('Deleted old school logo', [
                        'path' => $school->logo_path,
                        'disk' => $disk
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete old logo', [
                        'path' => $school->logo_path,
                        'disk' => $disk,
                        'error' => $e->getMessage()
                    ]);
                    // Optionally, log the error but don't stop the update process
                }
            }
            
            $logo = $request->file('logo');
            
            try {
                // Store the file in R2 storage
                $path = $logo->store('school_logos', 'r2');
                $updateData['logo_path'] = $path;
                
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
        
        $school->update($updateData);
        
        return redirect()->route('admin.schools.index')
            ->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $school = School::findOrFail($id);
            
            // Handle associated teachers if needed
            // This would depend on your application's structure and business rules
            
            $school->delete();
            
            return redirect()->route('admin.schools.index')
                ->with('success', 'School deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.schools.index')
                ->with('error', 'Unable to delete school: ' . $e->getMessage());
        }
    }

    /**
     * Set school as inactive
     */
    public function disable(string $id)
    {
        try {
            $school = School::findOrFail($id);
            $school->update(['is_active' => false]);
            
            return redirect()->route('admin.schools.show', $school->id)
                ->with('success', 'School has been disabled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to disable school: ' . $e->getMessage());
        }
    }

    /**
     * Set school as active
     */
    public function enable(string $id)
    {
        try {
            $school = School::findOrFail($id);
            $school->update(['is_active' => true]);
            
            return redirect()->route('admin.schools.show', $school->id)
                ->with('success', 'School has been enabled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to enable school: ' . $e->getMessage());
        }
    }
}
