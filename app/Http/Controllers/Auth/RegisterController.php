<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        // Clear the registration key session data after successful registration
        $request->session()->forget(['valid_registration_key', 'registration_key_info']);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'registration_key' => ['sometimes'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Get registration key info from session
        $keyInfo = session('registration_key_info', []);

        // Set default role and values
        $role = 'teacher';
        $schoolId = null;
        $isTeacherAdmin = false;

        // If we have key info, use it to set role, school_id, and teacher admin status
        if (!empty($keyInfo)) {
            // Set role and teacher admin status based on key type
            if (isset($keyInfo['key_type']) && $keyInfo['key_type']) {
                if ($keyInfo['key_type'] === 'teacher_admin') {
                    $role = 'teacher';
                    $isTeacherAdmin = true;
                } else {
                    $role = 'teacher';
                }
            }

            // Set school_id if provided
            if (isset($keyInfo['school_id']) && $keyInfo['school_id']) {
                $schoolId = $keyInfo['school_id'];
            }
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
            'school_id' => $schoolId,
            'is_teacher_admin' => $isTeacherAdmin,
        ]);
    }
}
