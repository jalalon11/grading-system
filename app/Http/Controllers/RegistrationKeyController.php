<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationKey;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegistrationKeyController extends Controller
{
    /**
     * Show the form for entering a registration key
     */
    public function showKeyForm()
    {
        return view('auth.register_key');
    }

    /**
     * Verify the entered key and redirect to registration if valid
     */
    public function verifyKey(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $keyValidation = RegistrationKey::validateKey($request->key);

        if ($keyValidation && $keyValidation['valid']) {
            // Store key information in session
            $request->session()->put('valid_registration_key', true);
            $request->session()->put('registration_key_info', $keyValidation);
            return redirect()->route('register');
        }

        return back()->with('error', 'Invalid registration key.');
    }

    /**
     * Verify the entered key via AJAX and return JSON response
     */
    public function verifyKeyAjax(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $keyValidation = RegistrationKey::validateKey($request->key);

        if ($keyValidation && $keyValidation['valid']) {
            // Store key information in session
            $request->session()->put('valid_registration_key', true);
            $request->session()->put('registration_key_info', $keyValidation);

            return response()->json([
                'valid' => true,
                'key_type' => $keyValidation['key_type'],
                'school_id' => $keyValidation['school_id']
            ]);
        }

        return response()->json([
            'valid' => false
        ]);
    }

    /**
     * Admin function to reset the master key
     */
    public function resetMasterKey(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_key' => 'required|string|min:6',
        ]);

        // Verify the current password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Authentication failed. Please enter your admin account password (not the master key).');
        }

        // Ensure user is admin
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'You do not have permission to reset the master key.');
        }

        // Reset the master key
        $key = RegistrationKey::createMasterKey($request->new_key);

        return back()->with('success', 'Master registration key has been reset to: ' . $request->new_key);
    }

    /**
     * Admin function to generate a one-time key
     */
    public function generateOneTimeKey(Request $request)
    {
        $request->validate([
            'expires_at' => 'nullable|date',
            'school_id' => 'required|exists:schools,id',
            'key_type' => 'required|in:teacher,teacher_admin',
        ]);

        // If key_type is teacher_admin, check if the school already has 2 teacher admins
        if ($request->key_type === 'teacher_admin' && $request->school_id) {
            $teacherAdminCount = User::where('school_id', $request->school_id)
                ->where('role', 'teacher')
                ->where('is_teacher_admin', true)
                ->count();

            if ($teacherAdminCount >= 2) {
                return back()->with('error', 'This school already has the maximum number of Teacher Admins (2).')
                    ->withInput();
            }
        }

        // School is now required for all key types, so this check is no longer needed

        // Ensure user is admin
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'You do not have permission to generate keys.');
        }

        $expiresAt = null;
        if ($request->has('expires_at') && $request->expires_at) {
            $expiresAt = Carbon::parse($request->expires_at);
        }

        $schoolId = $request->has('school_id') ? $request->school_id : null;
        $keyType = $request->key_type;

        $result = RegistrationKey::createOneTimeKey(null, $expiresAt, $schoolId, $keyType);

        // Get school name if school_id is provided
        $schoolName = null;
        if ($schoolId) {
            $school = School::find($schoolId);
            $schoolName = $school ? $school->name : null;
        }

        return back()->with([
            'generated_key' => $result['key'],
            'key_type' => $keyType,
            'school_name' => $schoolName
        ]);
    }

    /**
     * Generate multiple teacher keys at once
     */
    public function generateBulkKeys(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'key_count' => 'required|integer|min:1|max:50',
            'expires_at' => 'nullable|date',
        ]);

        // Ensure user is admin
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'You do not have permission to generate keys.');
        }

        $schoolId = $request->school_id;
        $keyCount = $request->key_count;

        $expiresAt = null;
        if ($request->has('expires_at') && $request->expires_at) {
            $expiresAt = Carbon::parse($request->expires_at);
        }

        // Get school name
        $school = School::find($schoolId);
        $schoolName = $school ? $school->name : null;

        // Generate the keys
        $generatedKeys = [];

        for ($i = 0; $i < $keyCount; $i++) {
            $result = RegistrationKey::createOneTimeKey(null, $expiresAt, $schoolId, 'teacher');
            $generatedKeys[] = $result['key'];
        }

        // Return with success message and the generated keys
        return back()->with([
            'bulk_keys' => $generatedKeys,
            'bulk_count' => $keyCount,
            'school_name' => $schoolName
        ]);
    }

    /**
     * Initialize the default master key
     */
    public function initialize()
    {
        // Check if already initialized
        if (RegistrationKey::where('is_master', true)->exists()) {
            return redirect()->route('login')
                ->with('error', 'Registration key system is already initialized.');
        }

        // Create master key with default password
        RegistrationKey::createMasterKey('admin123');

        return redirect()->route('login')
            ->with('success', 'Registration key system has been initialized with the default master key.');
    }
}
