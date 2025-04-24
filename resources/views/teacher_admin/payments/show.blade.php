@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold display-6"><i class="fas fa-receipt text-primary me-2"></i>Payment Details</h2>
            <p class="text-muted">View details for payment #{{ $payment->reference_number }}</p>
        </div>
        <div class="col-md-6 text-md-end d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
            <a href="{{ route('teacher-admin.payments.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Back to Payments
            </a>
        </div>
    </div>

    <!-- Payment Status Timeline -->   
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="payment-timeline d-flex">
                        <div class="timeline-step completed flex-fill text-center p-3">
                            <div class="step-icon">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="step-label">Submitted</div>
                            <div class="step-date small text-muted">{{ $payment->payment_date->setTimezone('Asia/Manila')->format('M d, Y') }}</div>
                        </div>
                        <div class="timeline-step {{ $payment->status !== 'pending' ? 'completed' : 'active' }} flex-fill text-center p-3">
                            <div class="step-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="step-label">Under Review</div>
                        </div>
                        <div class="timeline-step {{ $payment->status === 'completed' ? 'completed' : ($payment->status === 'failed' ? 'failed' : '') }} flex-fill text-center p-3">
                            <div class="step-icon">
                                <i class="{{ $payment->status === 'completed' ? 'fas fa-check-circle' : ($payment->status === 'failed' ? 'fas fa-times-circle' : 'fas fa-flag-checkered') }}"></i>
                            </div>
                            <div class="step-label">{{ $payment->status === 'completed' ? 'Approved' : ($payment->status === 'failed' ? 'Rejected' : 'Finalized') }}</div>
                            @if($payment->status !== 'pending')
                                <div class="step-date small text-muted">{{ $payment->updated_at->setTimezone('Asia/Manila')->format('M d, Y') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-file-invoice text-success me-2"></i>
                            Payment #{{ $payment->reference_number }}
                        </h5>
                        <div class="d-flex align-items-center">
                            <div class="me-2 d-flex align-items-center">
                                <span class="badge {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning' : 'bg-danger') }} payment-status-badge">
                                    <i class="{{ $payment->status === 'completed' ? 'fas fa-check-circle' : ($payment->status === 'pending' ? 'fas fa-clock' : 'fas fa-times-circle') }} me-1"></i>
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                            @if($payment->status === 'completed' || $payment->status === 'failed')
                                <a href="{{ route('teacher-admin.payments.receipt', $payment) }}" class="btn btn-sm btn-outline-primary rounded-pill" target="_blank">
                                    <i class="fas fa-download me-1"></i> Download Receipt
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($payment->status === 'pending')
                        <div class="alert alert-warning border-0 shadow-sm rounded-3">
                            <div class="d-flex">
                                <div class="me-3 fs-3"><i class="fas fa-clock"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-2">Payment Under Review</h6>
                                    <p class="mb-0">This payment is currently being reviewed by the administrator. You will be notified once it's processed. This typically takes 1-2 business days.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($payment->status === 'completed')
                        <div class="alert alert-success border-0 shadow-sm rounded-3">
                            <div class="d-flex">
                                <div class="me-3 fs-3"><i class="fas fa-check-circle"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-2">Payment Approved</h6>
                                    <p class="mb-0">This payment has been approved and your subscription is active until {{ $payment->subscription_end_date->format('F d, Y') }}.</p>

                                    @if($payment->admin_notes)
                                        <p class="small mt-2 mb-0"><i class="fas fa-info-circle me-1"></i> Admin approval notes are available in the Additional Information section below.</p>
                                    @endif

                                    <!-- @if($school->hasActiveSubscription() && $school->subscription_ends_at)
                                        <div class="mt-3">
                                            <div class="subscription-timer-container p-2 bg-light rounded-3 d-inline-block">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-hourglass-half text-primary me-2"></i>
                                                    <div>
                                                        <div class="small text-muted">Subscription Time Remaining</div>
                                                        <div class="fw-bold" id="subscription-timer">{{ $school->remaining_subscription_time }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif -->
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger border-0 shadow-sm rounded-3">
                            <div class="d-flex">
                                <div class="me-3 fs-3"><i class="fas fa-times-circle"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-2">Payment Rejected</h6>
                                    <p class="mb-0">This payment was rejected. Please submit a new payment or contact the administrator for assistance.</p>
                                    <p class="small mt-2 mb-0"><i class="fas fa-info-circle me-1"></i> See the rejection reason in the Additional Information section below.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4 g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-money-bill-wave me-2"></i>Payment Information</h6>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="payment-info-icon bg-primary text-white me-3">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Reference Number</div>
                                            <div class="fw-bold">{{ $payment->reference_number }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="payment-info-icon bg-success text-white me-3">
                                            <i class="fas fa-money-bill"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Amount</div>
                                            <div class="fw-bold fs-5">₱{{ number_format($payment->amount, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="payment-info-icon bg-info text-white me-3">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Payment Date</div>
                                            <div class="fw-bold">{{ $payment->payment_date->setTimezone('Asia/Manila')->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="payment-info-icon me-3 {{ $payment->payment_method === 'bank_transfer' ? 'bg-primary' : ($payment->payment_method === 'gcash' ? 'bg-info' : ($payment->payment_method === 'paymaya' ? 'bg-primary' : 'bg-secondary')) }} text-white">
                                            <i class="{{ $payment->payment_method === 'bank_transfer' ? 'fas fa-university' : ($payment->payment_method === 'gcash' ? 'fas fa-mobile-alt' : ($payment->payment_method === 'paymaya' ? 'fas fa-wallet' : 'fas fa-credit-card')) }}"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Payment Method</div>
                                            <div class="fw-bold">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3 text-success"><i class="fas fa-calendar-check me-2"></i>Subscription Information</h6>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="payment-info-icon bg-primary text-white me-3">
                                            <i class="fas fa-school"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">School</div>
                                            <div class="fw-bold">{{ $school->name }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="payment-info-icon bg-success text-white me-3">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Start Date</div>
                                            <div class="fw-bold">{{ $payment->subscription_start_date->setTimezone('Asia/Manila')->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div class="payment-info-icon bg-danger text-white me-3">
                                            <i class="fas fa-stop-circle"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">End Date</div>
                                            <div class="fw-bold">{{ $payment->subscription_end_date->setTimezone('Asia/Manila')->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="payment-info-icon bg-primary text-white me-3">
                                            <i class="fas fa-sync-alt"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Billing Cycle</div>
                                            <div class="fw-bold">{{ ucfirst($payment->billing_cycle ?? $school->billing_cycle) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($payment->notes || $payment->admin_notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-3 {{ $payment->status === 'failed' && $payment->admin_notes ? 'border border-danger border-opacity-25' : '' }}">
                                    <div class="card-header bg-white py-3 border-bottom border-light">
                                        <h6 class="fw-bold mb-0 {{ $payment->status === 'failed' && $payment->admin_notes ? 'text-danger' : 'text-primary' }}">
                                            <i class="fas fa-comment-alt me-2"></i>
                                            @if($payment->status === 'failed' && $payment->admin_notes)
                                                Rejection Information
                                            @else
                                                Additional Information
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($payment->notes)
                                            <div class="mb-3 pb-3 {{ $payment->admin_notes ? 'border-bottom' : '' }}">
                                                <div class="d-flex">
                                                    <div class="payment-info-icon bg-secondary text-white me-3">
                                                        <i class="fas fa-sticky-note"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-muted small">Your Notes</div>
                                                        <div class="mt-1">{{ $payment->notes }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($payment->admin_notes)
                                            <div>
                                                <div class="d-flex">
                                                    <div class="payment-info-icon {{ $payment->status === 'completed' ? 'bg-success' : ($payment->status === 'failed' ? 'bg-danger' : 'bg-info') }} text-white me-3">
                                                        <i class="fas fa-comment-dots"></i>
                                                    </div>
                                                    <div>
                                                        <div class="{{ $payment->status === 'failed' ? 'fw-bold text-danger' : 'text-muted small' }}">{{ $payment->status === 'completed' ? 'Admin Approval Note' : ($payment->status === 'failed' ? 'Rejection Reason' : 'Admin Notes') }}</div>
                                                        <div class="mt-1 {{ $payment->status === 'failed' ? 'fw-medium' : '' }}">{{ $payment->admin_notes }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($payment->status === 'pending' && !$hasPendingPayment && !$hasActiveSubscription)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    <i class="fas fa-sync-alt me-2"></i> Submit Another Payment
                                </a>
                            </div>
                        </div>
                    </div>
                    @elseif($payment->status === 'failed' && !$hasPendingPayment && !$hasActiveSubscription)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-primary rounded-pill px-4 py-2">
                                    <i class="fas fa-sync-alt me-2"></i> Submit New Payment
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

<style>
/* Payment Timeline Styling */
.payment-timeline {
    position: relative;
    display: flex;
    justify-content: space-between;
    background-color: white;
}

.payment-timeline::before {
    content: '';
    position: absolute;
    top: 40px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #e3e6f0;
    z-index: 1;
}

.timeline-step {
    position: relative;
    z-index: 2;
    padding: 1.5rem 0.75rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    font-size: 1.25rem;
    color: #858796;
    border: 2px solid #e3e6f0;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

.timeline-step.completed .step-icon {
    background-color: #1cc88a;
    color: white;
    border-color: #1cc88a;
    box-shadow: 0 0 0 4px rgba(28, 200, 138, 0.25);
}

.timeline-step.active .step-icon {
    background-color: #f6c23e;
    color: white;
    border-color: #f6c23e;
    box-shadow: 0 0 0 4px rgba(246, 194, 62, 0.25);
    animation: pulse 2s infinite;
}

.timeline-step.failed .step-icon {
    background-color: #e74a3b;
    color: white;
    border-color: #e74a3b;
    box-shadow: 0 0 0 4px rgba(231, 74, 59, 0.25);
}

.step-label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #5a5c69;
}

.timeline-step.completed .step-label,
.timeline-step.active .step-label,
.timeline-step.failed .step-label {
    color: #2e59d9;
}

/* Payment Status Badge Animation */
.payment-status-badge {
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 0.75rem;
    font-weight: 600;
    vertical-align: middle;
}

.payment-status-badge.bg-warning::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: shine 2s infinite;
}

@keyframes shine {
    0% { left: -100%; }
    100% { left: 100%; }
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(246, 194, 62, 0.7);
    }
    70% {
        transform: scale(1.1);
        box-shadow: 0 0 0 10px rgba(246, 194, 62, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(246, 194, 62, 0);
    }
}

/* Payment Info Icons */
.payment-info-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

/* Subscription Timer */
.subscription-timer-container {
    transition: all 0.3s ease;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .payment-timeline::before {
        top: 30px;
    }

    .step-icon {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .step-label {
        font-size: 0.75rem;
    }

    .step-date {
        font-size: 0.7rem;
    }

    .payment-info-icon {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }

    /* Ensure badge alignment on mobile */
    .payment-status-badge {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem !important;
    }

    /* Adjust header layout on mobile */
    .card-header .d-flex {
        flex-wrap: wrap;
    }

    .card-header .d-flex > h5 {
        margin-bottom: 0.5rem;
        width: 100%;
    }

    .card-header .d-flex > div {
        margin-left: auto;
    }
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Subscription timer update
        const subscriptionTimer = document.getElementById('subscription-timer');
        if (subscriptionTimer) {
            // Update the timer every minute
            setInterval(function() {
                fetch('{{ route("teacher-admin.subscription.remaining-time") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.remaining_time) {
                            subscriptionTimer.textContent = data.remaining_time;
                        }
                    })
                    .catch(error => console.error('Error updating subscription timer:', error));
            }, 60000); // Update every minute
        }

        // Receipt functionality is now handled by a direct link to the receipt page
    });
</script>
@endpush

@endsection
