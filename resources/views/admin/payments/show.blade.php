@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="fas fa-receipt text-primary me-2"></i>Payment Details</h2>
            <p class="text-muted">Review and process payment #{{ $payment->reference_number }}</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary">
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
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            This payment is pending approval. Please review the details and approve or reject it.
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <form action="{{ route('admin.payments.approve', $payment) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="admin_notes_approve" class="form-label">Admin Notes (Optional)</label>
                                        <textarea name="admin_notes" id="admin_notes_approve" rows="3" class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check-circle me-1"></i> Approve Payment
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="admin_notes_reject" class="form-label">Rejection Reason (Optional)</label>
                                        <textarea name="admin_notes" id="admin_notes_reject" rows="3" class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times-circle me-1"></i> Reject Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($payment->status === 'completed')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            This payment has been approved and the school's subscription is active until {{ $payment->subscription_end_date->format('F d, Y') }}.
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            This payment was rejected.
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
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Submitted By</td>
                                    <td>{{ $payment->user->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">School Information</h6>
                            <div class="d-flex align-items-center mb-3">
                                @if($payment->school->logo_path)
                                    <div class="me-3" style="width: 50px; height: 50px; position: relative; border-radius: 50%; overflow: hidden; border: 2px solid #f8f9fc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <img src="{{ $payment->school->logo_url }}" alt="{{ $payment->school->name }}" style="position: absolute; width: 100%; height: 100%; object-fit: cover; top: 0; left: 0;">
                                    </div>
                                @else
                                    <div class="me-3" style="width: 50px; height: 50px; border-radius: 50%; background-color: #4e73df; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 18px; text-transform: uppercase; border: 2px solid #f8f9fc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        {{ substr($payment->school->name, 0, 1) }}
                                    </div>
                                @endif
                                <h5 class="mb-0 fw-bold">{{ $payment->school->name }}</h5>
                            </div>
                            <table class="table table-borderless mt-2">
                                <tr>
                                    <td class="fw-bold ps-0">School Code</td>
                                    <td>{{ $payment->school->code }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Billing Cycle</td>
                                    <td>{{ ucfirst($payment->billing_cycle) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Subscription Start</td>
                                    <td>{{ $payment->subscription_start_date->setTimezone('Asia/Manila')->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold ps-0">Subscription End</td>
                                    <td>{{ $payment->subscription_end_date->setTimezone('Asia/Manila')->format('F d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($payment->notes)
                        <div class="mb-3">
                            <h6 class="fw-bold">Payment Notes</h6>
                            <p>{{ $payment->notes }}</p>
                        </div>
                    @endif

                    @if($payment->admin_notes)
                        <div class="mb-3">
                            <h6 class="fw-bold">Admin Notes</h6>
                            <p>{{ $payment->admin_notes }}</p>
                        </div>
                    @endif

                    @if($payment->school->subscription_status === 'trial')
                        <div class="mb-3">
                            <h6 class="fw-bold">Trial Ends On</h6>
                            <p>{{ $payment->school->trial_ends_at ? $payment->school->trial_ends_at->format('F d, Y') : 'N/A' }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <h6 class="fw-bold">Subscription Ends On</h6>
                        <p>{{ $payment->school->subscription_ends_at ? $payment->school->subscription_ends_at->format('F d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize all timers when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the payment timer
        createCountdownTimer('admin-minutes-timer');

        // Initialize the subscription status timer
        createCountdownTimer('admin-status-timer');
    });
</script>
@endpush
@endsection
