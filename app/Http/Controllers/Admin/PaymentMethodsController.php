<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethodSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodsController extends Controller
{
    /**
     * Display the payment methods settings page.
     */
    public function index()
    {
        // Get all payment method settings
        $paymentMethodSettings = PaymentMethodSetting::getSettings();
        
        return view('admin.payment_methods.index', compact('paymentMethodSettings'));
    }

    /**
     * Update payment method settings.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $paymentMethods = $request->input('payment_methods', []);
            
            foreach ($paymentMethods as $method => $settings) {
                $enabled = isset($settings['enabled']) && $settings['enabled'] === 'on';
                $message = $settings['message'] ?? '';
                
                PaymentMethodSetting::updateOrCreate(
                    ['method' => $method],
                    [
                        'enabled' => $enabled,
                        'message' => $message,
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()->route('admin.payment-methods.index')
                ->with('success', 'Payment method settings updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating payment method settings: ' . $e->getMessage());
        }
    }
}
