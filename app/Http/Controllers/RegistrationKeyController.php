<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationKey;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
        
        if (RegistrationKey::validateKey($request->key)) {
            $request->session()->put('valid_registration_key', true);
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
        
        $isValid = RegistrationKey::validateKey($request->key);
        
        if ($isValid) {
            // Store in session for persistence
            $request->session()->put('valid_registration_key', true);
        }
        
        return response()->json([
            'valid' => $isValid
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
        ]);
        
        // Ensure user is admin
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'You do not have permission to generate keys.');
        }
        
        $expiresAt = null;
        if ($request->has('expires_at')) {
            $expiresAt = Carbon::parse($request->expires_at);
        }
        
        $result = RegistrationKey::createOneTimeKey(null, $expiresAt);
        
        return back()->with('generated_key', $result['key']);
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
