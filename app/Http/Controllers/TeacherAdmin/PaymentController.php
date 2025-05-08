<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentMethodSetting;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $school = $user->school;

        if (!$school) {
            return redirect()->route('teacher-admin.dashboard')
                ->with('error', 'You are not associated with any school.');
        }

        $payments = Payment::where('school_id', $school->id)
            ->latest()
            ->paginate(10);

        // Check if there's a pending payment
        $hasPendingPayment = Payment::where('school_id', $school->id)
            ->where('status', 'pending')
            ->exists();

        // Check if there's a completed payment AND the subscription is still active
        // This allows making new payments when subscription has expired
        $hasActiveSubscription = $school->hasActiveSubscription();

        return view('teacher_admin.payments.index', compact('payments', 'school', 'hasPendingPayment', 'hasActiveSubscription'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $school = $user->school;

        if (!$school) {
            return redirect()->route('teacher-admin.dashboard')
                ->with('error', 'You are not associated with any school.');
        }

        // Check if there's a pending payment
        $hasPendingPayment = Payment::where('school_id', $school->id)
            ->where('status', 'pending')
            ->exists();

        // Check if there's an active subscription
        $hasActiveSubscription = $school->hasActiveSubscription();

        // If there's a pending payment, redirect back with a message
        if ($hasPendingPayment) {
            return redirect()->route('teacher-admin.payments.index')
                ->with('warning', 'You already have a pending payment. Please wait for it to be processed before making another payment.');
        }

        // If there's an active subscription, redirect back with a message
        if ($hasActiveSubscription) {
            return redirect()->route('teacher-admin.payments.index')
                ->with('info', 'You already have an active subscription. No need to make another payment at this time.');
        }

        // Get payment method settings
        $paymentMethodSettings = [
            'bank_transfer' => [
                'enabled' => PaymentMethodSetting::isEnabled('bank_transfer'),
                'message' => PaymentMethodSetting::getDisabledMessage('bank_transfer'),
            ],
            'gcash' => [
                'enabled' => PaymentMethodSetting::isEnabled('gcash'),
                'message' => PaymentMethodSetting::getDisabledMessage('gcash'),
            ],
            'paymaya' => [
                'enabled' => PaymentMethodSetting::isEnabled('paymaya'),
                'message' => PaymentMethodSetting::getDisabledMessage('paymaya'),
            ],
            'other' => [
                'enabled' => PaymentMethodSetting::isEnabled('other'),
                'message' => PaymentMethodSetting::getDisabledMessage('other'),
            ],
        ];

        return view('teacher_admin.payments.create', compact('school', 'paymentMethodSettings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $school = $user->school;

        if (!$school) {
            return redirect()->route('teacher-admin.dashboard')
                ->with('error', 'You are not associated with any school.');
        }

        $request->validate([
            'billing_cycle' => 'required|in:monthly,yearly',
            'payment_method' => 'required|in:bank_transfer,gcash,paymaya,other',
            'reference_number' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Check if the selected payment method is enabled
        $paymentMethod = $request->payment_method;
        if (!PaymentMethodSetting::isEnabled($paymentMethod)) {
            $message = PaymentMethodSetting::getDisabledMessage($paymentMethod) ?: 'This payment method is currently unavailable.';
            return redirect()->back()->withInput()->with('error', $message);
        }

        // Calculate amount based on billing cycle
        $amount = $request->billing_cycle === 'yearly' ? $school->yearly_price : $school->monthly_price;

        // Calculate subscription dates
        $startDate = now();
        $endDate = $request->billing_cycle === 'yearly'
            ? $startDate->copy()->addYear()
            : $startDate->copy()->addMonth();

        $payment = Payment::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'amount' => $amount,
            'payment_date' => now(),
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'billing_cycle' => $request->billing_cycle,
            'subscription_start_date' => $startDate,
            'subscription_end_date' => $endDate,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('teacher-admin.payments.show', $payment)
            ->with('success', 'Payment submitted successfully. It will be processed by the administrator.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $user = Auth::user();
        $school = $user->school;

        if ($payment->school_id !== $school->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if there's a pending payment (other than the current one)
        $hasPendingPayment = Payment::where('school_id', $school->id)
            ->where('status', 'pending')
            ->where('id', '!=', $payment->id)
            ->exists();

        // Check if there's an active subscription
        $hasActiveSubscription = $school->hasActiveSubscription();

        return view('teacher_admin.payments.show', compact('payment', 'school', 'hasPendingPayment', 'hasActiveSubscription'));
    }

    /**
     * Get the remaining subscription time as JSON
     */
    public function getRemainingTime()
    {
        $user = Auth::user();
        $school = $user->school;

        if (!$school) {
            return response()->json(['error' => 'No school associated with this user'], 404);
        }

        $remainingTime = $school->hasActiveSubscription() ? $school->remaining_subscription_time : null;

        return response()->json([
            'remaining_time' => $remainingTime,
            'has_active_subscription' => $school->hasActiveSubscription(),
            'subscription_ends_at' => $school->subscription_ends_at ? $school->subscription_ends_at->format('Y-m-d H:i:s') : null
        ]);
    }

    /**
     * Generate a printable receipt for a payment
     */
    public function receipt(Payment $payment)
    {
        $user = Auth::user();
        $school = $user->school;

        if ($payment->school_id !== $school->id) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow generating receipts for completed payments
        if ($payment->status !== 'completed' && $payment->status !== 'failed') {
            return redirect()->route('teacher-admin.payments.show', $payment)
                ->with('warning', 'Receipts are only available for processed payments.');
        }

        return view('teacher_admin.payments.receipt', compact('payment', 'school'));
    }
}
