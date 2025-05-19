<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolDivision;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'teachersCount' => User::where('role', 'teacher')->count(),
            'teacherAdminsCount' => User::where('role', 'teacher_admin')->count(),
            'schoolDivisionsCount' => SchoolDivision::count(),
            'schoolsCount' => School::count(),
            'studentsCount' => Student::count()
        ];

        // Get pending support tickets count
        $pendingSupportCount = \App\Models\SupportTicket::where('status', 'open')->count();

        // Get sales data for the dashboard
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get current month sales
        $currentMonthSales = \App\Models\Payment::where('status', 'completed')
            ->whereYear('payment_date', $currentYear)
            ->whereMonth('payment_date', $currentMonth)
            ->sum('amount');

        // Get current year sales
        $currentYearSales = \App\Models\Payment::where('status', 'completed')
            ->whereYear('payment_date', $currentYear)
            ->sum('amount');

        // Get monthly sales data for the current year
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[$i] = \App\Models\Payment::where('status', 'completed')
                ->whereYear('payment_date', $currentYear)
                ->whereMonth('payment_date', $i)
                ->sum('amount');
        }

        // Get yearly sales data for the last 5 years
        $yearlySales = [];
        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear - $i;
            $yearlySales[$year] = \App\Models\Payment::where('status', 'completed')
                ->whereYear('payment_date', $year)
                ->sum('amount');
        }

        // Sort yearly sales by year (ascending)
        ksort($yearlySales);

        return view('admin.dashboard', compact(
            'stats',
            'pendingSupportCount',
            'currentMonthSales',
            'currentYearSales',
            'monthlySales',
            'yearlySales'
        ));
    }

    /**
     * Update the admin profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the admin password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Password changed successfully.');
    }
}
