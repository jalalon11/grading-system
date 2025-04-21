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

    <!-- Subscription Status Cards -->
    <div class="row mb-4">
        <!-- School Information Card -->
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                            <i class="fas fa-school"></i>
                        </div>
                        <h5 class="fw-bold mb-0">School Information</h5>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small mb-1">School Name</div>
                        <div class="fw-bold fs-5">{{ $school->name }}</div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small mb-1">Billing Cycle</div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-2 px-3 py-2">{{ ucfirst($school->billing_cycle) }}</span>
                            <span class="fw-bold">₱{{ number_format($school->current_price, 2) }}/{{ $school->billing_cycle === 'yearly' ? 'year' : 'month' }}</span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="text-muted small mb-1">Subscription Status</div>
                        <div>
                            @if($school->onTrial())
                                <span class="badge bg-info px-3 py-2">Trial</span>
                                @if($school->trial_ends_at)
                                    <span class="badge bg-primary ms-1 px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $school->remaining_trial_days }} remaining
                                    </span>
                                @endif
                            @elseif($school->hasActiveSubscription())
                                <span class="badge bg-success px-3 py-2">Active</span>
                                @if($school->subscription_ends_at)
                                    <span class="badge bg-primary ms-1 px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $school->remaining_subscription_time }} remaining
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-danger px-3 py-2">Expired</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Details Card -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Subscription Details</h5>
                    </div>

                    @if($school->onTrial())
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="text-muted small mb-1">Trial Ends On</div>
                                <div class="fw-bold">{{ $school->trial_ends_at ? $school->trial_ends_at->setTimezone('Asia/Manila')->format('F d, Y') : 'Unlimited' }}</div>
                            </div>
                            <div class="col-md-8">
                                <div class="alert {{ $school->remaining_trial_days === 'Unlimited' ? 'alert-info' : 'alert-warning' }} mb-0 d-flex align-items-start">
                                    <i class="fas {{ $school->remaining_trial_days === 'Unlimited' ? 'fa-info-circle' : 'fa-exclamation-triangle' }} me-3 mt-1"></i>
                                    <div>
                                        @if($school->remaining_trial_days === 'Unlimited')
                                            <strong>Unlimited Trial Period</strong>
                                            <p class="mb-0 small">Your school has an unlimited trial period. You can make a payment anytime to activate a subscription.</p>
                                        @else
                                            <strong>Trial Ending Soon</strong>
                                            <p class="mb-0 small">Your trial period will end in {{ $school->remaining_trial_days }}. Please make a payment to continue using the system.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($school->hasActiveSubscription())
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="text-muted small mb-1">Subscription Ends On</div>
                                <div class="fw-bold">{{ $school->subscription_ends_at ? $school->subscription_ends_at->setTimezone('Asia/Manila')->format('F d, Y') : 'Unlimited' }}</div>

                                @if($school->subscription_ends_at)
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%"
                                             data-total-days="{{ $school->subscription_ends_at->diffInDays($school->subscription_ends_at->copy()->subMonth()) }}"
                                             data-days-left="{{ $school->subscription_ends_at->diffInDays(now()) }}"
                                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="alert alert-success mb-0 d-flex align-items-start">
                                    <i class="fas fa-check-circle me-3 mt-1"></i>
                                    <div>
                                        <strong>Active Subscription</strong>
                                        <p class="mb-0 small">Your subscription is active{{ $school->subscription_ends_at ? '' : ' and will not expire' }}. Your school has full access to all features of the system.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger mb-4 d-flex">
                            <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Subscription Expired</h6>
                                <p class="mb-2 small">Your school's subscription has expired and access has been disabled for all teachers.</p>
                                <p class="mb-0 small"><strong>As a Teacher Admin, you can still access the payment page to renew your subscription.</strong> Once payment is approved, your school will be reactivated automatically.</p>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            @if($hasPendingPayment)
                                <button class="btn btn-primary btn-lg" disabled title="You have a pending payment that needs to be processed first">
                                    <i class="fas fa-credit-card me-2"></i> Make Payment Now
                                </button>
                                <small class="text-center text-muted mt-1"><i class="fas fa-info-circle"></i> You have a pending payment being processed</small>
                            @else
                                <a href="{{ route('teacher-admin.payments.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card me-2"></i> Make Payment Now
                                </a>
                            @endif
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
        // Handle progress bar calculation
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            const totalDays = parseInt(progressBar.dataset.totalDays) || 30;
            const daysLeft = parseInt(progressBar.dataset.daysLeft) || 0;
            const percentLeft = Math.min(100, Math.max(0, (daysLeft / totalDays) * 100));
            const percentComplete = 100 - percentLeft;

            progressBar.style.width = percentComplete + '%';
            progressBar.setAttribute('aria-valuenow', percentComplete);
        }

        // Add animation to cards
        const cards = document.querySelectorAll('.card, .subscription-info');
        cards.forEach((card, index) => {
            card.classList.add('animate__animated', 'animate__fadeInUp');
            card.style.animationDelay = (index * 0.1) + 's';
        });

        // Add animation to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            // Different animation for failed payment rows
            if (row.classList.contains('text-muted')) {
                row.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                row.classList.add('animate__animated', 'animate__fadeInRight');
            }
            row.style.animationDelay = (0.3 + (index * 0.05)) + 's';
        });

        // Add animation to badges
        const badges = document.querySelectorAll('.badge');
        badges.forEach((badge, index) => {
            badge.classList.add('animate__animated', 'animate__fadeIn');
            badge.style.animationDelay = (0.5 + (index * 0.05)) + 's';
        });
    });
</script>
@endsection
