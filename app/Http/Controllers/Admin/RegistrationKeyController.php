<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationKey;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistrationKeyController extends Controller
{
    /**
     * Display the registration keys management page
     */
    public function index(Request $request)
    {
        // Build the query for active registration keys
        $query = RegistrationKey::where('is_master', false)
            ->where('is_used', false)
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })
            ->with(['temporaryKey', 'school']); // Eager load the relationships

        // Apply filters if provided
        $filters = [];

        // Filter by key type
        if ($request->has('key_type') && $request->key_type) {
            $query->where('key_type', $request->key_type);
            $filters['key_type'] = $request->key_type;
        }

        // Filter by school
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
            $filters['school_id'] = $request->school_id;
        }

        // Determine how many items per page
        $perPage = 15; // Default
        if ($request->has('per_page')) {
            if ($request->per_page == 'all') {
                $perPage = $query->count(); // Get all records
                $filters['per_page'] = 'all';
            } else {
                $perPage = (int) $request->per_page;
                $filters['per_page'] = $perPage;
            }
        }

        // Get the paginated results
        $keys = $query->orderBy('created_at', 'desc')
            ->paginate($perPage) // Show keys per page based on selection
            ->appends($filters); // Maintain filters in pagination links

        // Get schools for the dropdowns
        $schools = School::orderBy('name')->get();

        // Get teacher admin counts for all schools
        $teacherAdminCounts = [];
        foreach($schools as $school) {
            $count = \App\Models\User::where('school_id', $school->id)
                ->where('role', 'teacher')
                ->where('is_teacher_admin', true)
                ->count();
            $teacherAdminCounts[$school->id] = $count;
        }

        return view('admin.registration_keys', compact('keys', 'schools', 'teacherAdminCounts', 'filters'));
    }
}
