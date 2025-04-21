<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['school', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by school
        if ($request->has('school_id') && $request->school_id) {
            $query->where('school_id', $request->school_id);
        }

        $payments = $query->latest()->paginate(10);
        $schools = School::orderBy('name')->get();

        return view('admin.payments.index', compact('payments', 'schools'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['school', 'user']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Approve a payment
     */
    public function approve(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment has already been processed.');
        }

        try {
            DB::beginTransaction();

            // Update payment status
            $payment->status = 'completed';
            $payment->admin_notes = $request->admin_notes;
            $payment->save();

            // Update school subscription
            $school = $payment->school;
            $school->subscription_status = 'active';
            $school->subscription_ends_at = $payment->subscription_end_date;
            $school->is_active = true; // Reactivate the school
            $school->save();

            // Log the reactivation
            Log::info("School {$school->name} (ID: {$school->id}) has been reactivated after payment approval.");

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment approved successfully. The school subscription has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while approving the payment: ' . $e->getMessage());
        }
    }

    /**
     * Reject a payment
     */
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'This payment has already been processed.');
        }

        try {
            DB::beginTransaction();

            // Update payment status
            $payment->status = 'failed';
            $payment->admin_notes = $request->admin_notes;
            $payment->save();

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while rejecting the payment: ' . $e->getMessage());
        }
    }
}
