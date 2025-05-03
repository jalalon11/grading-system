@extends('layouts.app')

@section('styles')
<style>
    /* Custom styles for payment management page */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .empty-state {
        padding: 2rem;
    }

    .empty-state-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto;
        font-size: 2rem;
    }

    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    /* Hover effect for table rows */
    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    /* Card hover effects */
    .card {
        transition: all 0.3s ease;
        border-radius: 0.75rem;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Button styles */
    .btn-primary {
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
    }

    /* Badge styles */
    .badge {
        transition: all 0.2s ease;
    }
    .badge:hover {
        transform: scale(1.05);
    }

    /* Failed payment styles */
    tr.text-muted {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    tr.text-muted:hover {
        opacity: 0.95;
    }
    tr.text-muted .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
        opacity: 0.8;
        transition: all 0.3s ease;
    }
    tr.text-muted .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
        opacity: 1;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .display-6 {
            font-size: 1.5rem;
        }

        .icon-circle {
            width: 32px;
            height: 32px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Payment Management</h1>
            <p class="mb-0 text-muted">Manage your school's subscription and payment history</p>
        </div>
        @if(!$hasPendingPayment && !($school->hasActiveSubscription() && !$school->onTrial()))
            <a href="{{ route('teacher-admin.payments.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle fa-sm me-2"></i>Make Payment
            </a>
        @endif
    </div>

    <!-- Subscription Overview -->
    <div class="row mb-4">
        <!-- School Information Card -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card h-100 slide-up" style="animation-delay: 0.1s">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-school"></i>
                        </div>
                        <h5 class="fw-bold mb-0">School Information</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="icon-circle bg-light text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted small text-uppercase">School Name</div>
                            <div class="fw-bold fs-5">{{ $school->name }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="icon-circle bg-light text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted small text-uppercase">Billing Cycle</div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">{{ ucfirst($school->billing_cycle) }}</span>
                                <span class="fw-bold fs-5">₱{{ number_format($school->current_price, 2) }}</span>
                                <span class="text-muted ms-1">/{{ $school->billing_cycle === 'yearly' ? 'year' : 'month' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-circle bg-light text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="text-muted small text-uppercase">Subscription Status</div>
                            <div class="mt-1">
                                @if($school->onTrial())
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info">
                                            <i class="fas fa-flask me-1"></i> Trial
                                        </span>
                                        @if($school->trial_ends_at)
                                            <span class="badge bg-primary ms-2">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $school->remaining_trial_days }} days left
                                            </span>
                                        @endif
                                    </div>
                                @elseif($school->hasActiveSubscription())
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                        @if($school->subscription_ends_at)
                                            <span class="badge bg-primary ms-2">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $school->remaining_subscription_time }} left
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Expired
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Details Card -->
        <div class="col-lg-8">
            <div class="card h-100 slide-up" style="animation-delay: 0.2s">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Subscription Details</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($school->onTrial())
                        <div class="row g-0 align-items-center">
                            <div class="col-lg-5 mb-4 mb-lg-0">
                                <div class="p-4 bg-light rounded-3 h-100">
                                    <h6 class="fw-bold text-uppercase text-primary mb-3">Trial Period</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-info text-white me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                            <i class="fas fa-calendar-day"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Trial Ends On</div>
                                            <div class="fw-bold fs-5">{{ $school->trial_ends_at ? $school->trial_ends_at->setTimezone('Asia/Manila')->format('F d, Y') : 'Unlimited' }}</div>
                                        </div>
                                    </div>

                                    @if($school->trial_ends_at)
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">Trial Progress</small>
                                                <small class="text-muted">{{ $school->remaining_trial_days }} days left</small>
                                            </div>
                                            <div class="progress" style="height: 8px; border-radius: 4px; overflow: hidden;">
                                                @php
                                                    // Calculate trial period progress
                                                    $trialStartDate = $school->trial_starts_at ?? $school->created_at;
                                                    $trialEndDate = $school->trial_ends_at;
                                                    $totalTrialDays = $trialEndDate->diffInDays($trialStartDate);
                                                    $trialDaysElapsed = now()->diffInDays($trialStartDate);
                                                    $trialProgress = $totalTrialDays > 0 ? min(100, max(0, ($trialDaysElapsed / $totalTrialDays) * 100)) : 0;
                                                @endphp
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    style="width: {{ $trialProgress }}%"
                                                    data-percent-complete="{{ $trialProgress }}"
                                                    aria-valuenow="{{ $trialProgress }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="ps-lg-4">
                                    <div class="alert {{ $school->remaining_trial_days === 'Unlimited' ? 'alert-info' : 'alert-warning' }} mb-4 d-flex align-items-start shadow-sm">
                                        <i class="fas {{ $school->remaining_trial_days === 'Unlimited' ? 'fa-info-circle' : 'fa-exclamation-triangle' }} me-3 mt-1 fa-lg"></i>
                                        <div>
                                            @if($school->remaining_trial_days === 'Unlimited')
                                                <h6 class="fw-bold mb-2">Unlimited Trial Period</h6>
                                                <p class="mb-0">Your school has an unlimited trial period. You can make a payment anytime to activate a subscription.</p>
                                            @else
                                                <h6 class="fw-bold mb-2">Trial Ending Soon</h6>
                                                <p class="mb-0">Your trial period will end in {{ $school->remaining_trial_days }}. Please make a payment to continue using the system.</p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($school->remaining_trial_days !== 'Unlimited' && !$hasPendingPayment)
                                        <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-credit-card me-2"></i> Upgrade to Full Subscription
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($school->hasActiveSubscription())
                        <div class="row g-0 align-items-center">
                            <div class="col-lg-5 mb-4 mb-lg-0">
                                <div class="p-4 bg-light rounded-3 h-100">
                                    <h6 class="fw-bold text-uppercase text-primary mb-3">Active Subscription</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-success text-white me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Subscription Ends On</div>
                                            <div class="fw-bold fs-5">{{ $school->subscription_ends_at ? $school->subscription_ends_at->setTimezone('Asia/Manila')->format('F d, Y') : 'Unlimited' }}</div>
                                        </div>
                                    </div>

                                    @if($school->subscription_ends_at)
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">Subscription Period</small>
                                                <small class="text-muted">{{ $school->remaining_subscription_time }} left</small>
                                            </div>
                                            <div class="progress" style="height: 8px; border-radius: 4px; overflow: hidden;">
                                                @php
                                                    // Calculate subscription period
                                                    $subscriptionStartDate = $school->subscription_starts_at ?? $school->created_at;
                                                    $subscriptionEndDate = $school->subscription_ends_at;
                                                    $totalSubscriptionDays = $subscriptionEndDate->diffInDays($subscriptionStartDate);
                                                    $daysElapsed = now()->diffInDays($subscriptionStartDate);
                                                    $percentComplete = $totalSubscriptionDays > 0 ? min(100, max(0, ($daysElapsed / $totalSubscriptionDays) * 100)) : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $percentComplete }}%"
                                                    data-percent-complete="{{ $percentComplete }}"
                                                    aria-valuenow="{{ $percentComplete }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="text-muted">Start</small>
                                                <small class="text-muted">End</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="ps-lg-4">
                                    <div class="alert alert-success mb-4 d-flex align-items-start shadow-sm">
                                        <i class="fas fa-check-circle me-3 mt-1 fa-lg"></i>
                                        <div>
                                            <h6 class="fw-bold mb-2">Active Subscription</h6>
                                            <p class="mb-0">Your subscription is active{{ $school->subscription_ends_at ? '' : ' and will not expire' }}. Your school has full access to all features of the system.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row g-0">
                            <div class="col-lg-5 mb-4 mb-lg-0">
                                <div class="p-4 bg-light rounded-3 h-100">
                                    <h6 class="fw-bold text-uppercase text-danger mb-3">Expired Subscription</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="icon-circle bg-danger text-white me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                            <i class="fas fa-calendar-times"></i>
                                        </div>
                                        <div>
                                            <div class="text-muted small">Expired On</div>
                                            <div class="fw-bold fs-5">{{ $school->subscription_ends_at ? $school->subscription_ends_at->setTimezone('Asia/Manila')->format('F d, Y') : 'N/A' }}</div>
                                        </div>
                                    </div>

                                    <div class="alert alert-danger mb-0 py-2 px-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <small>Access disabled for all teachers</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="ps-lg-4">
                                    <div class="alert alert-danger mb-4 d-flex align-items-start shadow-sm">
                                        <i class="fas fa-exclamation-triangle me-3 mt-1 fa-lg"></i>
                                        <div>
                                            <h6 class="fw-bold mb-2">Subscription Expired</h6>
                                            <p class="mb-2">Your school's subscription has expired and access has been disabled for all teachers.</p>
                                            <p class="mb-0"><strong>As a Teacher Admin, you can still access the payment page to renew your subscription.</strong> Once payment is approved, your school will be reactivated automatically.</p>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        @if($hasPendingPayment)
                                            <button class="btn btn-primary btn-lg" disabled title="You have a pending payment that needs to be processed first">
                                                <i class="fas fa-credit-card me-2"></i> Make Payment Now
                                            </button>
                                            <div class="alert alert-warning py-2 text-center">
                                                <i class="fas fa-spinner fa-spin me-2"></i> You have a pending payment being processed
                                            </div>
                                        @else
                                            <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-credit-card me-2"></i> Renew Subscription Now
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History Section -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex align-items-center">
                <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-history"></i>
                </div>
                <h5 class="fw-bold mb-0">Payment History</h5>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th class="ps-4 py-3">Reference #</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3">Method</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Billing Cycle</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="{{ $payment->status === 'failed' ? 'text-muted bg-light bg-opacity-50' : '' }}">
                                <td class="ps-4 fw-medium">{{ $payment->reference_number }}</td>
                                <td>{{ $payment->payment_date->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                                <td class="fw-bold">₱{{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    @php
                                        $methodColors = [
                                            'gcash' => 'bg-info',
                                            'bank_transfer' => 'bg-primary',
                                            'cash' => 'bg-success',
                                            'check' => 'bg-secondary',
                                        ];
                                        $methodColor = $methodColors[$payment->payment_method] ?? 'bg-info';

                                        // If status is failed, make the badge appear greyed out
                                        if ($payment->status === 'failed') {
                                            $methodColor = 'bg-secondary bg-opacity-50';
                                        }
                                    @endphp
                                    <span class="badge {{ $methodColor }} rounded-pill px-3 py-2">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->status === 'completed')
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator bg-success me-2"></span>
                                            <span>Completed</span>
                                        </div>
                                    @elseif($payment->status === 'pending')
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator bg-warning me-2"></span>
                                            <span>Pending</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <span class="status-indicator bg-secondary me-2"></span>
                                            <span>Failed</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $payment->status === 'failed' ? 'bg-secondary bg-opacity-50 text-muted' : 'bg-primary bg-opacity-10 text-primary' }} px-3 py-2 rounded-pill">
                                        {{ ucfirst($payment->billing_cycle) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('teacher-admin.payments.show', $payment) }}" class="btn btn-sm {{ $payment->status === 'failed' ? 'btn-outline-secondary' : 'btn-primary' }} rounded-pill px-3">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon bg-light text-secondary mb-3">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        <h6 class="fw-bold">No Payment Records</h6>
                                        <p class="text-muted mb-3">You haven't made any payments yet.</p>

                                        @if($hasPendingPayment)
                                            <button class="btn btn-primary rounded-pill px-4" disabled title="You have a pending payment that needs to be processed first">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Make New Payment
                                            </button>
                                            <div class="mt-2">
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                    <i class="fas fa-info-circle me-1"></i> Pending payment being processed
                                                </span>
                                            </div>
                                        @elseif($hasActiveSubscription)
                                            <button class="btn btn-primary rounded-pill px-4" disabled title="You already have an active subscription">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Make New Payment
                                            </button>
                                            <div class="mt-2">
                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i> Active subscription
                                                </span>
                                            </div>
                                        @else
                                            <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-primary rounded-pill px-4">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Make New Payment
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($payments->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle progress bar animations
        const animateProgressBar = (progressBar) => {
            if (progressBar) {
                // The width is already set in the HTML, but we'll animate it for a better effect
                const percentComplete = parseFloat(progressBar.dataset.percentComplete) || 0;

                // First set width to 0
                progressBar.style.width = '0%';

                // Then animate to the actual percentage
                setTimeout(() => {
                    progressBar.style.transition = 'width 1.5s ease-in-out';
                    progressBar.style.width = percentComplete + '%';
                }, 300);
            }
        };

        // Animate subscription progress bar
        const subscriptionProgressBar = document.querySelector('.progress-bar.bg-success');
        animateProgressBar(subscriptionProgressBar);

        // Animate trial progress bar
        const trialProgressBar = document.querySelector('.progress-bar.bg-info');
        animateProgressBar(trialProgressBar);

        // Animate elements with the slide-up class
        const slideUpElements = document.querySelectorAll('.slide-up');
        slideUpElements.forEach(element => {
            // Get the animation delay from the style attribute or default to 0
            const delay = parseFloat(element.style.animationDelay || '0s') * 1000;

            // Initially hide the element
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

            // Show the element after the delay
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, delay);
        });

        // Animate table rows with staggered delay
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 500 + (index * 100)); // Staggered delay
        });

        // Add hover effects to buttons and badges
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                if (!this.disabled) {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
                }
            });

            button.addEventListener('mouseleave', function() {
                if (!this.disabled) {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                }
            });
        });

        // Add pulse animation to pending status indicators
        const pendingIndicators = document.querySelectorAll('.status-pending, .badge.bg-warning');
        pendingIndicators.forEach(indicator => {
            setInterval(() => {
                indicator.style.transform = 'scale(1.1)';
                indicator.style.transition = 'transform 0.5s ease';

                setTimeout(() => {
                    indicator.style.transform = 'scale(1)';
                }, 500);
            }, 2000);
        });

        // Add subtle animation to icons in the School Information card
        const infoIcons = document.querySelectorAll('.card-body .icon-circle');
        infoIcons.forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
                this.style.transition = 'transform 0.3s ease';
            });

            icon.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
    });
</script>
@endsection
