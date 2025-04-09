<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RegistrationKey;

class RegistrationKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if there's a valid registration key in the session
        if ($request->session()->has('valid_registration_key')) {
            return $next($request);
        }
        
        // If this is a POST request with a registration_key, attempt to validate it
        if ($request->isMethod('post') && $request->has('registration_key')) {
            if (RegistrationKey::validateKey($request->registration_key)) {
                $request->session()->put('valid_registration_key', true);
                return $next($request);
            }
            return redirect()->route('login')
                ->with('error', 'Invalid registration key provided.');
        }
        
        // Check if there's a key parameter in the request
        $key = $request->query('key');
        
        if (!$key) {
            // Redirect to login page with register tab active
            return redirect()->route('login')
                ->with('register_tab', true);
        }
        
        // Validate the key
        if (RegistrationKey::validateKey($key)) {
            // Store in session so the user doesn't need to enter it again
            $request->session()->put('valid_registration_key', true);
            
            // Continue to registration
            return $next($request);
        }
        
        // Invalid key, redirect to login page with register tab active and error message
        return redirect()->route('login')
            ->with('error', 'Invalid registration key provided.')
            ->with('register_tab', true);
    }
}
