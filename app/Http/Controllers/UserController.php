<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Update the user's dark mode preference.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateDarkMode(Request $request)
    {
        $user = Auth::user();
        $user->dark_mode_preference = $request->input('dark_mode');
        $user->save();
        
        return response()->json(['success' => true]);
    }
}
