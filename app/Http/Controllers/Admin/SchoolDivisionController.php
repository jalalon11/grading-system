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

class SchoolDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = SchoolDivision::withCount('schools');

        // Handle search
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Handle sorting
        $sort = request('sort', 'name');
        $order = request('order', 'asc');

        switch ($sort) {
            case 'name':
                $query->orderBy('name', $order);
                break;
            case 'schools':
                $query->orderBy('schools_count', $order);
                break;
            case 'created_at':
                $query->orderBy('created_at', $order);
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $divisions = $query->get();

        return view('admin.school-divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.school-divisions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the received data
        Log::info('Division creation request data:', $request->all());
        
        // Validate division data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_divisions',
            'address' => 'nullable|string',
            'region' => 'nullable|string|max:255',
            'schools' => 'required|array|min:1',
            'schools.*.name' => 'required|string|max:255',
            'schools.*.code' => 'required|string|max:50|unique:schools',
            'schools.*.address' => 'nullable|string',
            'schools.*.grade_levels' => 'required|array|min:1',
            'schools.*.grade_levels.*' => 'required|in:K,1,2,3,4,5,6,7,8,9,10,11,12',
            'schools.*.teachers.*.name' => 'nullable|string|max:255',
            'schools.*.teachers.*.email' => 'nullable|email|unique:users,email',
            'schools.*.teachers.*.password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            Log::error('Division creation validation failed:', $validator->errors()->toArray());
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the school division
            $division = SchoolDivision::create([
                'name' => $request->name,
                'code' => $request->code,
                'address' => $request->address,
                'region' => $request->region,
            ]);
            
            Log::info('Created division:', ['id' => $division->id, 'name' => $division->name]);

            // Create schools for this division
            foreach ($request->schools as $schoolData) {
                $school = School::create([
                    'name' => $schoolData['name'],
                    'code' => $schoolData['code'],
                    'address' => $schoolData['address'] ?? null,
                    'grade_levels' => isset($schoolData['grade_levels']) ? json_encode($schoolData['grade_levels']) : json_encode([]),
                    'school_division_id' => $division->id,
                ]);
                
                Log::info('Created school:', ['id' => $school->id, 'name' => $school->name]);

                // Create teachers for this school if present
                if (isset($schoolData['teachers']) && is_array($schoolData['teachers'])) {
                    Log::info('Teachers data for school ' . $school->name . ':', $schoolData['teachers']);
                    
                    foreach ($schoolData['teachers'] as $teacherData) {
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
            }

            DB::commit();
            Log::info('Successfully completed division creation transaction');

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'School division created successfully with schools and teachers.',
                    'redirect' => route('admin.school-divisions.index')
                ]);
            }

            return redirect()->route('admin.school-divisions.index')
                ->with('success', 'School division created successfully with schools and teachers.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Division creation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
            
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
        $division = SchoolDivision::with('schools.teachers')->findOrFail($id);
        return view('admin.school-divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $division = SchoolDivision::with('schools')->findOrFail($id);
        return view('admin.school-divisions.edit', compact('division'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $division = SchoolDivision::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string', 
                'max:50',
                Rule::unique('school_divisions')->ignore($division->id)
            ],
            'address' => 'nullable|string',
            'region' => 'nullable|string|max:255',
        ]);
        
        $division->update([
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'region' => $request->region,
        ]);
        
        return redirect()->route('admin.school-divisions.index')
            ->with('success', 'School division updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $division = SchoolDivision::findOrFail($id);
            $division->delete();
            
            return redirect()->route('admin.school-divisions.index')
                ->with('success', 'School division deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.school-divisions.index')
                ->with('error', 'Unable to delete school division: ' . $e->getMessage());
        }
    }
}
