<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesReportController extends Controller
{
    /**
     * Display the sales report dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $schoolId = $request->input('school_id');

        // Get all years with payments for the dropdown
        $years = Payment::selectRaw('YEAR(payment_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // If no years found (no payments yet), use current year
        if ($years->isEmpty()) {
            $years = collect([Carbon::now()->year]);
        }

        // Get all schools for the dropdown
        $schools = School::orderBy('name')->get();

        // Get monthly sales data for the selected year
        $monthlySales = $this->getMonthlySalesData($year, $schoolId);

        // Get yearly sales data
        $yearlySales = $this->getYearlySalesData($schoolId);

        // Get payment method distribution
        $paymentMethods = $this->getPaymentMethodDistribution($year, $month, $schoolId);

        // Get billing cycle distribution
        $billingCycles = $this->getBillingCycleDistribution($year, $month, $schoolId);

        // Get recent payments
        $recentPayments = Payment::with(['school', 'user'])
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->latest('payment_date')
            ->take(10)
            ->get();

        // Get total sales for the current month and year
        $currentMonthSales = $this->getCurrentMonthSales($schoolId);
        $currentYearSales = $this->getCurrentYearSales($schoolId);

        // Get total completed payments count
        $completedPaymentsCount = Payment::where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->count();

        // Get pending payments count
        $pendingPaymentsCount = Payment::where('status', 'pending')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->count();

        // Get top schools by payment amount
        $topSchools = $this->getTopSchoolsByPayment($year, 5);

        return view('admin.reports.sales', compact(
            'years',
            'year',
            'month',
            'schools',
            'schoolId',
            'monthlySales',
            'yearlySales',
            'paymentMethods',
            'billingCycles',
            'recentPayments',
            'currentMonthSales',
            'currentYearSales',
            'completedPaymentsCount',
            'pendingPaymentsCount',
            'topSchools'
        ));
    }

    /**
     * Get monthly sales data for the selected year
     */
    private function getMonthlySalesData($year, $schoolId = null)
    {
        $query = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->orderBy('month');

        $data = $query->get()->pluck('total', 'month')->toArray();

        // Fill in missing months with zero
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = $data[$i] ?? 0;
        }

        return $result;
    }

    /**
     * Get yearly sales data
     */
    private function getYearlySalesData($schoolId = null)
    {
        $query = Payment::selectRaw('YEAR(payment_date) as year, SUM(amount) as total')
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->groupBy(DB::raw('YEAR(payment_date)'))
            ->orderBy('year');

        return $query->get()->pluck('total', 'year')->toArray();
    }

    /**
     * Get payment method distribution
     */
    private function getPaymentMethodDistribution($year, $month, $schoolId = null)
    {
        // Define standard payment methods to ensure they're always included
        $standardMethods = ['bank_transfer', 'gcash', 'paymaya'];

        // Get all payment methods from the database
        $dbMethods = Payment::select('payment_method')
            ->distinct()
            ->whereNotNull('payment_method')
            ->pluck('payment_method')
            ->toArray();

        // Merge standard methods with database methods
        $allMethods = array_unique(array_merge($standardMethods, $dbMethods));

        // Get data for the selected filters
        $query = Payment::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->groupBy('payment_method');

        $result = $query->get();

        // Create a collection with all methods
        $finalResult = collect();

        // Add all methods with data from the query or zero if no data
        foreach ($allMethods as $method) {
            $methodData = $result->firstWhere('payment_method', $method);

            if ($methodData) {
                // Convert to array to ensure consistent format
                $finalResult->push([
                    'payment_method' => $methodData['payment_method'],
                    'count' => (int)$methodData['count'],
                    'total' => (float)$methodData['total']
                ]);
            } else {
                $finalResult->push([
                    'payment_method' => $method,
                    'count' => 0,
                    'total' => 0
                ]);
            }
        }

        return $finalResult;
    }

    /**
     * Get billing cycle distribution
     */
    private function getBillingCycleDistribution($year, $month, $schoolId = null)
    {
        // Always include these two billing cycles
        $allCycles = ['monthly', 'yearly'];

        // Get additional billing cycles from the database if any
        $dbCycles = Payment::select('billing_cycle')
            ->distinct()
            ->whereNotNull('billing_cycle')
            ->pluck('billing_cycle')
            ->toArray();

        // Merge with standard cycles
        $allCycles = array_unique(array_merge($allCycles, $dbCycles));

        // Get data for the selected filters
        $query = Payment::selectRaw('billing_cycle, COUNT(*) as count, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->groupBy('billing_cycle');

        $result = $query->get();

        // Create a collection with all billing cycles
        $finalResult = collect();

        // Add all cycles with data from the query or zero if no data
        foreach ($allCycles as $cycle) {
            $cycleData = $result->firstWhere('billing_cycle', $cycle);

            if ($cycleData) {
                // Convert to array to ensure consistent format
                $finalResult->push([
                    'billing_cycle' => $cycleData['billing_cycle'],
                    'count' => (int)$cycleData['count'],
                    'total' => (float)$cycleData['total']
                ]);
            } else {
                $finalResult->push([
                    'billing_cycle' => $cycle,
                    'count' => 0,
                    'total' => 0
                ]);
            }
        }

        return $finalResult;
    }

    /**
     * Get current month sales
     */
    private function getCurrentMonthSales($schoolId = null)
    {
        $query = Payment::where('status', 'completed')
            ->whereYear('payment_date', Carbon::now()->year)
            ->whereMonth('payment_date', Carbon::now()->month)
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            });

        return $query->sum('amount');
    }

    /**
     * Get current year sales
     */
    private function getCurrentYearSales($schoolId = null)
    {
        $query = Payment::where('status', 'completed')
            ->whereYear('payment_date', Carbon::now()->year)
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            });

        return $query->sum('amount');
    }

    /**
     * Get top schools by payment amount
     */
    private function getTopSchoolsByPayment($year, $limit = 5)
    {
        return Payment::selectRaw('school_id, SUM(amount) as total')
            ->with(['school.school_division']) // Eager load school with its division
            ->whereYear('payment_date', $year)
            ->where('status', 'completed')
            ->groupBy('school_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Generate printable monthly sales report
     */
    public function printMonthly(Request $request)
    {
        // Get filter parameters
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $schoolId = $request->input('school_id');

        // Log the request parameters for debugging
        Log::info('Monthly Report Request', [
            'year' => $year,
            'month' => $month,
            'schoolId' => $schoolId
        ]);

        // Get monthly sales data
        $monthlySales = $this->getMonthlySalesData($year, $schoolId);

        // Get payment method distribution
        $paymentMethods = $this->getPaymentMethodDistribution($year, $month, $schoolId);

        // Get billing cycle distribution
        $billingCycles = $this->getBillingCycleDistribution($year, $month, $schoolId);

        // Get detailed payment data for the month
        $payments = Payment::with(['school', 'user'])
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        // Get school information if filtered by school
        $school = null;
        if ($schoolId) {
            $school = School::find($schoolId);
        }

        // Get total sales for the month
        $totalSales = $payments->sum('amount');

        // Get month name
        $monthName = date('F', mktime(0, 0, 0, $month, 1));

        // Log the data being passed to the view
        Log::info('Monthly Report Data', [
            'paymentCount' => $payments->count(),
            'totalSales' => $totalSales,
            'paymentMethodsCount' => count($paymentMethods),
            'billingCyclesCount' => count($billingCycles)
        ]);

        return view('admin.reports.print.monthly', compact(
            'year',
            'month',
            'monthName',
            'school',
            'payments',
            'totalSales',
            'monthlySales',
            'paymentMethods',
            'billingCycles'
        ));
    }

    /**
     * Generate printable yearly sales report
     */
    public function printYearly(Request $request)
    {
        // Get filter parameters
        $year = $request->input('year', Carbon::now()->year);
        $schoolId = $request->input('school_id');

        // Log the request parameters for debugging
        Log::info('Yearly Report Request', [
            'year' => $year,
            'schoolId' => $schoolId
        ]);

        // Get monthly sales data for the year
        $monthlySales = $this->getMonthlySalesData($year, $schoolId);

        // Get yearly sales trend
        $yearlySales = $this->getYearlySalesData($schoolId);

        // Get payment method distribution for the entire year
        $paymentMethods = $this->getYearlyPaymentMethodDistribution($year, $schoolId);

        // Get billing cycle distribution for the entire year
        $billingCycles = $this->getYearlyBillingCycleDistribution($year, $schoolId);

        // Get detailed payment data for the year
        $payments = Payment::with(['school', 'user'])
            ->whereYear('payment_date', $year)
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        // Get school information if filtered by school
        $school = null;
        if ($schoolId) {
            $school = School::find($schoolId);
        }

        // Get total sales for the year
        $totalSales = $payments->sum('amount');

        // Get top schools for the year
        $topSchools = $this->getTopSchoolsByPayment($year, 10);

        // Log the data being passed to the view
        Log::info('Yearly Report Data', [
            'paymentCount' => $payments->count(),
            'totalSales' => $totalSales,
            'paymentMethodsCount' => count($paymentMethods),
            'billingCyclesCount' => count($billingCycles),
            'topSchoolsCount' => count($topSchools)
        ]);

        return view('admin.reports.print.yearly', compact(
            'year',
            'school',
            'payments',
            'totalSales',
            'monthlySales',
            'yearlySales',
            'paymentMethods',
            'billingCycles',
            'topSchools'
        ));
    }

    /**
     * Get payment method distribution for the entire year
     */
    private function getYearlyPaymentMethodDistribution($year, $schoolId = null)
    {
        // Define standard payment methods to ensure they're always included
        $standardMethods = ['bank_transfer', 'gcash', 'paymaya'];

        // Get all payment methods from the database
        $dbMethods = Payment::select('payment_method')
            ->distinct()
            ->whereNotNull('payment_method')
            ->pluck('payment_method')
            ->toArray();

        // Merge standard methods with database methods
        $allMethods = array_unique(array_merge($standardMethods, $dbMethods));

        // Get data for the selected year
        $query = Payment::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->groupBy('payment_method');

        $result = $query->get();

        // Create a collection with all methods
        $finalResult = collect();

        // Add all methods with data from the query or zero if no data
        foreach ($allMethods as $method) {
            $methodData = $result->firstWhere('payment_method', $method);

            if ($methodData) {
                // Convert to array to ensure consistent format
                $finalResult->push([
                    'payment_method' => $methodData['payment_method'],
                    'count' => (int)$methodData['count'],
                    'total' => (float)$methodData['total']
                ]);
            } else {
                $finalResult->push([
                    'payment_method' => $method,
                    'count' => 0,
                    'total' => 0
                ]);
            }
        }

        return $finalResult;
    }

    /**
     * Get billing cycle distribution for the entire year
     */
    private function getYearlyBillingCycleDistribution($year, $schoolId = null)
    {
        // Always include these two billing cycles
        $allCycles = ['monthly', 'yearly'];

        // Get additional billing cycles from the database if any
        $dbCycles = Payment::select('billing_cycle')
            ->distinct()
            ->whereNotNull('billing_cycle')
            ->pluck('billing_cycle')
            ->toArray();

        // Merge with standard cycles
        $allCycles = array_unique(array_merge($allCycles, $dbCycles));

        // Get data for the selected year
        $query = Payment::selectRaw('billing_cycle, COUNT(*) as count, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->where('status', 'completed')
            ->when($schoolId, function($query) use ($schoolId) {
                return $query->where('school_id', $schoolId);
            })
            ->groupBy('billing_cycle');

        $result = $query->get();

        // Create a collection with all billing cycles
        $finalResult = collect();

        // Add all cycles with data from the query or zero if no data
        foreach ($allCycles as $cycle) {
            $cycleData = $result->firstWhere('billing_cycle', $cycle);

            if ($cycleData) {
                // Convert to array to ensure consistent format
                $finalResult->push([
                    'billing_cycle' => $cycleData['billing_cycle'],
                    'count' => (int)$cycleData['count'],
                    'total' => (float)$cycleData['total']
                ]);
            } else {
                $finalResult->push([
                    'billing_cycle' => $cycle,
                    'count' => 0,
                    'total' => 0
                ]);
            }
        }

        return $finalResult;
    }
}
