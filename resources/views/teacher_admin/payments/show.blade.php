@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="fas fa-receipt text-primary me-2"></i>Payment Details</h2>
            <p class="text-muted">View details for payment #{{ $payment->reference_number }}</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('teacher-admin.payments.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Back to Payments
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-invoice text-success me-2"></i>
                            Payment #{{ $payment->reference_number }}
                        </h5>
                        <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }} fs-6">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($payment->status === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            This payment is currently being reviewed by the administrator. You will be notified once it's processed.
                        </div>
                    @elseif($payment->status === 'completed')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            This payment has been approved and your subscription is active until {{ $payment->subscription_end_date->format('F d, Y') }}.
                            @if($school->hasActiveSubscription() && $school->subscription_ends_at)
                                <div class="mt-2">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $school->remaining_subscription_time }} remaining
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            This payment was rejected. Please contact the administrator for more information or submit a new payment.
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Payment Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold ps-0">Reference #</td>
                                    <td>{{ $payment->reference_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Amount</td>
                                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Payment Date</td>
                                    <td>{{ $payment->payment_date->setTimezone('Asia/Manila')->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Payment Method</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Subscription Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold ps-0">School</td>
                                    <td>{{ $school->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Start Date</td>
                                    <td>{{ $payment->subscription_start_date->setTimezone('Asia/Manila')->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">End Date</td>
                                    <td>{{ $payment->subscription_end_date->setTimezone('Asia/Manila')->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Billing Cycle</td>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($payment->billing_cycle ?? $school->billing_cycle) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($payment->notes)
                        <div class="mt-3">
                            <h6 class="fw-bold">Notes</h6>
                            <p>{{ $payment->notes }}</p>
                        </div>
                    @endif

                    @if($payment->admin_notes)
                        <div class="mt-3">
                            <h6 class="fw-bold">Admin Notes</h6>
                            <p>{{ $payment->admin_notes }}</p>
                        </div>
                    @endif

                    @if($payment->status === 'pending' && !$hasPendingPayment && !$hasActiveSubscription)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-sync-alt me-1"></i> Submit Another Payment
                                </a>
                            </div>
                        </div>
                    </div>
                    @elseif($payment->status === 'failed' && !$hasPendingPayment && !$hasActiveSubscription)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-primary">
                                    <i class="fas fa-sync-alt me-1"></i> Submit New Payment
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
