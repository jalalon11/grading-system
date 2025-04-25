@extends('layouts.app')

@section('styles')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Registration key styling */
    .key-text {
        font-family: monospace;
        font-size: 1rem;
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
        color: #2E4053;
        letter-spacing: 0.5px;
    }

    .user-select-all {
        user-select: all;
    }

    /* Sticky header for scrollable tables */
    .scrollable-table .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    /* Custom scrollbar styling */
    .scrollable-payment-list {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }

    .scrollable-payment-list::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-payment-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .scrollable-payment-list::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
    }

    .scrollable-payment-list::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.4);
    }

    /* Add shadow effect to indicate scrollability */
    .scrollable-payment-list::after,
    .scrollable-table::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0));
        pointer-events: none;
        border-bottom-left-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }

    /* Scrollable table styles */
    .scrollable-table {
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }

    .scrollable-table::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-table::-webkit-scrollbar-track {
        background: transparent;
    }

    .scrollable-table::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
    }

    .scrollable-table::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.4);
    }

    /* Sticky header styles */
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 1;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-primary">
            <i class="fas fa-school me-2"></i> School Overview
        </h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- School Information -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start mb-4">
                        <div class="school-logo-container me-md-4 mb-3 mb-md-0 text-center">
                            @if($school->logo_path)
                                <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="img-fluid" style="max-width: 120px; max-height: 120px; object-fit: contain;">
                            @else
                                <div class="bg-primary bg-opacity-10 p-4 rounded-circle">
                                    <i class="fas fa-school text-primary fa-4x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="school-info text-center text-md-start">
                            <h2 class="mb-1 fw-bold">{{ $school->name }}</h2>
                            <p class="text-muted mb-2">School Code: {{ $school->code }}</p>

                            @if($school->address)
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-secondary me-2"></i> {{ $school->address }}
                                </p>
                            @endif

                            @if($school->principal)
                                <p class="mb-2">
                                    <i class="fas fa-user-tie text-secondary me-2"></i> Principal: {{ $school->principal }}
                                </p>
                            @endif

                            @php
                                $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels :
                                            (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
                                sort($gradeLevels);
                            @endphp

                            <div class="mt-3">
                                <span class="text-muted me-2">Grade Levels:</span>
                                @foreach($gradeLevels as $grade)
                                    <span class="badge bg-primary me-1">{{ $grade }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment & Subscription Status Section -->
        <div class="col-lg-12 mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-credit-card text-primary me-2"></i> Subscription & Payment
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Subscription Status -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Subscription Status</h5>

                                    @if($school->onTrial())
                                        <div class="alert alert-info rounded-3 border-0 shadow-sm">
                                            <div class="d-flex">
                                                <div class="me-3 fs-4"><i class="fas fa-info-circle"></i></div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Trial Period</h6>
                                                    @if($school->trial_ends_at)
                                                        <p class="mb-0 small">Your trial period ends in {{ $school->remaining_trial_days }}. Subscribe now to continue using all features.</p>
                                                    @else
                                                        <p class="mb-0 small">Your school has an unlimited trial period. You can make a payment anytime to activate a subscription.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($school->hasActiveSubscription())
                                        <div class="alert alert-success rounded-3 border-0 shadow-sm">
                                            <div class="d-flex">
                                                <div class="me-3 fs-4"><i class="fas fa-check-circle"></i></div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Active Subscription</h6>
                                                    <p class="mb-0 small">
                                                        Your subscription is active until
                                                        {{ $school->subscription_ends_at ? $school->subscription_ends_at->setTimezone('Asia/Manila')->format('F d, Y') : 'Unlimited' }}.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-danger rounded-3 border-0 shadow-sm">
                                            <div class="d-flex">
                                                <div class="me-3 fs-4"><i class="fas fa-exclamation-triangle"></i></div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">Subscription Expired</h6>
                                                    <p class="mb-0 small">Your subscription has expired. Make a payment to restore access to your school.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <p class="mb-2">
                                            <strong>Status:</strong>
                                            @if($school->onTrial())
                                                <span class="badge bg-info">Trial</span>
                                            @elseif($school->hasActiveSubscription())
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Expired</span>
                                            @endif
                                        </p>

                                        @if($school->onTrial() && $school->trial_ends_at)
                                            <p class="mb-2">
                                                <strong>Trial Ends:</strong>
                                                {{ $school->trial_ends_at->setTimezone('Asia/Manila')->format('F d, Y') }}
                                            </p>
                                        @endif

                                        @if($school->hasActiveSubscription() && $school->subscription_ends_at)
                                            <p class="mb-2">
                                                <strong>Subscription Ends:</strong>
                                                {{ $school->subscription_ends_at->setTimezone('Asia/Manila')->format('F d, Y') }}
                                            </p>

                                            @php
                                                $now = now()->setTimezone('Asia/Manila');
                                                $end = $school->subscription_ends_at->setTimezone('Asia/Manila');
                                                $daysLeft = (int)$now->diffInDays($end, false);
                                                $totalDays = $school->billing_cycle === 'yearly' ? 365 : 30;
                                                $percentLeft = max(0, min(100, ($daysLeft / $totalDays) * 100));
                                            @endphp

                                            <div class="mt-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="small text-muted">Subscription Time Remaining</span>
                                                    <span class="small fw-medium">{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left</span>
                                                </div>
                                                <div class="progress" style="height: 8px;" data-total-days="{{ $totalDays }}" data-days-left="{{ $daysLeft }}">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentLeft }}%;" aria-valuenow="{{ $percentLeft }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($school->billing_cycle)
                                            <p class="mb-2">
                                                <strong>Billing Cycle:</strong>
                                                <span class="badge bg-primary">{{ ucfirst($school->billing_cycle) }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Options -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Subscription Pricing</h5>

                                    <div class="pricing-options mb-3">
                                        @php
                                            // Get the grade levels to determine pricing
                                            $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels :
                                                        (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);

                                            // Check if school has both K-6 and 7-12 grade levels
                                            $hasElementary = false;
                                            $hasHighSchool = false;

                                            foreach($gradeLevels as $grade) {
                                                if($grade == 'K' || ($grade >= 1 && $grade <= 6)) {
                                                    $hasElementary = true;
                                                }
                                                if($grade >= 7 && $grade <= 12) {
                                                    $hasHighSchool = true;
                                                }
                                            }

                                            $isK12 = $hasElementary && $hasHighSchool;
                                        @endphp

                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3">
                                                <div class="bg-light p-2 rounded-circle">
                                                    <i class="fas fa-school text-primary fa-lg"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">School Type</h6>
                                                <span class="badge bg-primary p-2">
                                                    @if($isK12)
                                                        K-12 School
                                                    @elseif($hasElementary)
                                                        Elementary School (K-6)
                                                    @elseif($hasHighSchool)
                                                        High School (7-12)
                                                    @else
                                                        Custom Grade Levels
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-primary h-100">
                                                    <div class="card-header bg-primary text-white text-center py-3">
                                                        <h6 class="mb-0 fw-bold">Monthly Plan</h6>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title fw-bold mb-0">₱{{ number_format($school->monthly_price, 2) }}</h5>
                                                        <p class="card-text small text-muted">per month</p>
                                                        <hr class="my-2">
                                                        <ul class="list-unstyled text-start small">
                                                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> Full access to all features</li>
                                                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> Monthly billing flexibility</li>
                                                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> Cancel anytime</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-primary h-100 position-relative">
                                                    <div class="card-header bg-primary text-white text-center py-3">
                                                        <h6 class="mb-0 fw-bold">Annual Plan</h6>
                                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                            20% OFF
                                                        </span>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title fw-bold mb-0">₱{{ number_format($school->yearly_price, 2) }}</h5>
                                                        <p class="card-text small text-muted">per year</p>
                                                        <hr class="my-2">
                                                        <ul class="list-unstyled text-start small">
                                                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> Full access to all features</li>
                                                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> <strong>20% savings</strong> vs monthly</li>
                                                            <li class="mb-1"><i class="fas fa-check-circle text-success me-2"></i> Uninterrupted service</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 text-center">
                                        @php
                                            $hasPendingPayment = \App\Models\Payment::where('school_id', $school->id)
                                                ->where('status', 'pending')
                                                ->exists();

                                            $hasActiveSubscription = $school->hasActiveSubscription();
                                        @endphp

                                        @if($hasPendingPayment)
                                            <div class="alert alert-warning rounded-3 border-0 shadow-sm">
                                                <i class="fas fa-info-circle me-2"></i>
                                                You have a pending payment being processed. Visit the <a href="{{ route('teacher-admin.payments.index') }}" class="alert-link">payments page</a> for details.
                                            </div>
                                        @elseif(!$hasActiveSubscription)
                                            <div class="alert alert-info rounded-3 border-0 shadow-sm">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Need to make a payment? Visit the <a href="{{ route('teacher-admin.payments.index') }}" class="alert-link">payments page</a> to manage your subscription.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Payments -->
                        <div class="col-lg-12 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">Recent Payments</h5>
                                        <a href="{{ route('teacher-admin.payments.index') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-credit-card me-1"></i> Manage Payments
                                        </a>
                                    </div>

                                    @php
                                        $recentPayments = \App\Models\Payment::where('school_id', $school->id)
                                            ->latest()
                                            ->take(10)
                                            ->get();
                                    @endphp

                                    @if($recentPayments->count() > 0)
                                        <div class="scrollable-payment-list position-relative" style="max-height: 350px; overflow-y: auto; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem;">
                                            <div class="list-group list-group-flush">
                                                @foreach($recentPayments as $payment)
                                                    <a href="{{ route('teacher-admin.payments.show', $payment) }}" class="list-group-item list-group-item-action py-3 px-3 border-bottom">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="fw-medium">₱{{ number_format($payment->amount, 2) }}</div>
                                                                <div class="small text-muted">
                                                                    {{ $payment->payment_date->setTimezone('Asia/Manila')->format('M d, Y') }} •
                                                                    {{ ucfirst($payment->billing_cycle) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                @php
                                                                    $statusColors = [
                                                                        'pending' => 'bg-warning',
                                                                        'completed' => 'bg-success',
                                                                        'failed' => 'bg-secondary'
                                                                    ];
                                                                    $statusColor = $statusColors[$payment->status] ?? 'bg-secondary';
                                                                @endphp
                                                                <span class="badge {{ $statusColor }}">
                                                                    {{ ucfirst($payment->status) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i> Showing {{ $recentPayments->count() }} most recent payments
                                            </small>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <div class="mb-3">
                                                <div class="bg-light p-3 rounded-circle d-inline-block">
                                                    <i class="fas fa-receipt text-secondary fa-2x"></i>
                                                </div>
                                            </div>
                                            <h6 class="text-muted">No Payment Records</h6>
                                            <p class="text-muted small mb-0">You haven't made any payments yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Registration Keys -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-key text-primary me-2"></i> Teacher Registration Keys
            </h5>
            <div class="d-flex">
                <span class="badge bg-primary">{{ $registrationKeys->count() }} Available</span>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                <i class="fas fa-info-circle me-1"></i> These registration keys can be provided to teachers to create accounts for your school. Each key can only be used once.
            </p>

            @if($registrationKeys->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="badge bg-primary">{{ $registrationKeys->count() }} Keys Available</span>
                    </div>
                    <button class="btn btn-primary copy-all-keys-btn"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Copy all keys to clipboard">
                        <i class="fas fa-copy me-2"></i> Copy All Keys
                    </button>
                </div>

                <div class="scrollable-table position-relative" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="keysTable">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Registration Key</th>
                                <th>Created</th>
                                <th>Expires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrationKeys as $key)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="key-text me-2 user-select-all">{{ $key->temporaryKey->plain_key }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $key->created_at->setTimezone('Asia/Manila')->format('M d, Y') }}</td>
                                    <td>
                                        @if($key->expires_at)
                                            {{ $key->expires_at->setTimezone('Asia/Manila')->format('M d, Y') }}
                                        @else
                                            <span class="badge bg-success">Never Expires</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary copy-key-btn"
                                                data-key="{{ $key->temporaryKey->plain_key }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Copy to clipboard">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($registrationKeys->count() > 5)
                    <div class="text-center py-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i> Scroll to see all {{ $registrationKeys->count() }} keys
                        </small>
                    </div>
                @endif

                <!-- Hidden textarea for copying all keys -->
                <textarea id="allKeysText" class="d-none">@foreach($registrationKeys as $key){{ $key->temporaryKey->plain_key }}
@endforeach</textarea>

                <div class="alert alert-warning mt-3 mb-0">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading">Important Note</h6>
                            <p class="mb-0">These keys are only visible to Teacher Administrators. For security reasons, keys will disappear from this list once they are used for registration.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-4">
                    <div class="mb-3">
                        <div class="bg-light p-3 rounded-circle d-inline-block">
                            <i class="fas fa-key text-secondary fa-2x"></i>
                        </div>
                    </div>
                    <h5>No Registration Keys Available</h5>
                    <p class="text-muted">There are no active registration keys for teachers at this time. Please contact the system administrator to generate new keys.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Teachers List -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-chalkboard-teacher text-primary me-2"></i> Teachers
            </h5>
            <div class="d-flex">
                <div class="input-group">
                    <input type="text" class="form-control" id="teacherSearch" placeholder="Search teachers...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="scrollable-table position-relative" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0" id="teachersTable">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th class="ps-3 bg-light">Teacher</th>
                            <th class="bg-light">Email</th>
                            <th class="bg-light">Adviser For</th>
                            <th class="bg-light">Teaching Assignments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary bg-opacity-10 p-2 rounded-circle me-3 text-center">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $teacher->name }}</div>
                                            @if($teacher->is_teacher_admin)
                                                <span class="badge bg-info">Teacher Admin</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        {{ $teacher->email }}
                                    </div>
                                </td>
                                <td>
                                    @if($teacher->sections->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($teacher->sections as $section)
                                                <span class="badge bg-primary">
                                                    {{ $section->name }} ({{ $section->grade_level }})
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted">Not an adviser</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($teachingAssignments[$teacher->id]) && count($teachingAssignments[$teacher->id]) > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($teachingAssignments[$teacher->id] as $assignment)
                                                <div class="badge bg-light text-dark border">
                                                    <span class="text-primary">{{ $assignment->section_name }}</span>
                                                    <span class="mx-1">•</span>
                                                    <span class="text-secondary">{{ $assignment->subject_name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted">No teaching assignments</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light p-3 rounded-circle mb-3">
                                            <i class="fas fa-user-slash text-secondary fa-2x"></i>
                                        </div>
                                        <h5>No Teachers Found</h5>
                                        <p class="text-muted">There are no teachers assigned to this school yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($teachers->count() > 10)
                <div class="text-center py-2 border-top">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i> Scroll to see all {{ $teachers->count() }} teachers
                    </small>
                </div>
            @endif
        </div>
    </div>

    <!-- Sections and Students -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-door-open text-primary me-2"></i> Sections and Students
            </h5>
            <div class="d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" id="sectionSearch" placeholder="Search sections or students...">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @php
                // Group sections by grade level
                $sectionsByGradeLevel = [];
                foreach($sections as $section) {
                    $gradeLevel = $section->grade_level;
                    if(!isset($sectionsByGradeLevel[$gradeLevel])) {
                        $sectionsByGradeLevel[$gradeLevel] = [];
                    }
                    $sectionsByGradeLevel[$gradeLevel][] = $section;
                }

                // Sort grade levels
                uksort($sectionsByGradeLevel, function($a, $b) {
                    // Extract numeric part from grade level
                    $aNum = (int) preg_replace('/[^0-9]/', '', $a);
                    $bNum = (int) preg_replace('/[^0-9]/', '', $b);

                    // Handle special case for Kindergarten
                    if($a === 'K' || $a === 'Kindergarten') return -1;
                    if($b === 'K' || $b === 'Kindergarten') return 1;

                    return $aNum - $bNum;
                });
            @endphp

            <!-- Grade Level Navigation -->
            <div class="bg-light border-top border-bottom py-3">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-layer-group me-2"></i> Sections by Grade Level
                            </h5>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end" style="gap: 8px;">
                                <div class="input-group" style="max-width: 250px;">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="fas fa-graduation-cap"></i>
                                    </span>
                                    <select class="form-select" id="gradeLevelSelect" onchange="switchGradeLevel(this.value)" onclick="switchGradeLevel(this.value)">
                                        @foreach($sectionsByGradeLevel as $gradeLevel => $gradeSections)
                                            <option value="{{ $gradeLevel }}" {{ $loop->first ? 'selected' : '' }}>
                                                {{ $gradeLevel }} ({{ count($gradeSections) }}) Section/s
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade Level Content -->
            <div class="p-4">
                @foreach($sectionsByGradeLevel as $gradeLevel => $gradeSections)
                    <div class="grade-level-content" id="grade-{{ $gradeLevel }}-content" style="display: {{ $loop->first ? 'block' : 'none' }}">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="mb-0 text-primary">
                                    <i class="fas fa-graduation-cap me-2"></i> Grade {{ $gradeLevel }} Sections
                                </h4>
                                <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6">
                                    {{ count($gradeSections) }} {{ Str::plural('Section', count($gradeSections)) }}
                                </div>
                            </div>

                            <!-- Grade Level Statistics -->
                            @if(isset($gradeLevelStats[$gradeLevel]))
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-chart-pie text-primary me-2"></i> Grade Level Statistics
                                            </h6>
                                            <span class="badge bg-primary">
                                                {{ $gradeLevelStats[$gradeLevel]['total'] }} {{ Str::plural('Student', $gradeLevelStats[$gradeLevel]['total']) }}
                                            </span>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <div>
                                                        <i class="fas fa-male text-primary me-1"></i> Male Students
                                                    </div>
                                                    <div class="fw-medium">
                                                        {{ $gradeLevelStats[$gradeLevel]['male'] }}
                                                        <small class="text-muted">({{ $gradeLevelStats[$gradeLevel]['total'] > 0 ? round(($gradeLevelStats[$gradeLevel]['male'] / $gradeLevelStats[$gradeLevel]['total']) * 100) : 0 }}%)</small>
                                                    </div>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $gradeLevelStats[$gradeLevel]['total'] > 0 ? ($gradeLevelStats[$gradeLevel]['male'] / $gradeLevelStats[$gradeLevel]['total']) * 100 : 0 }}%"
                                                        aria-valuenow="{{ $gradeLevelStats[$gradeLevel]['male'] }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="{{ $gradeLevelStats[$gradeLevel]['total'] }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <div>
                                                        <i class="fas fa-female text-danger me-1"></i> Female Students
                                                    </div>
                                                    <div class="fw-medium">
                                                        {{ $gradeLevelStats[$gradeLevel]['female'] }}
                                                        <small class="text-muted">({{ $gradeLevelStats[$gradeLevel]['total'] > 0 ? round(($gradeLevelStats[$gradeLevel]['female'] / $gradeLevelStats[$gradeLevel]['total']) * 100) : 0 }}%)</small>
                                                    </div>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: {{ $gradeLevelStats[$gradeLevel]['total'] > 0 ? ($gradeLevelStats[$gradeLevel]['female'] / $gradeLevelStats[$gradeLevel]['total']) * 100 : 0 }}%"
                                                        aria-valuenow="{{ $gradeLevelStats[$gradeLevel]['female'] }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="{{ $gradeLevelStats[$gradeLevel]['total'] }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex gap-2">
                                                        <span class="badge bg-success rounded-pill">
                                                            <i class="fas fa-user-check me-1"></i> {{ $gradeLevelStats[$gradeLevel]['active'] }} Active
                                                        </span>
                                                        @if($gradeLevelStats[$gradeLevel]['inactive'] > 0)
                                                            <span class="badge bg-secondary rounded-pill">
                                                                <i class="fas fa-user-slash me-1"></i> {{ $gradeLevelStats[$gradeLevel]['inactive'] }} Disabled
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="badge bg-info rounded-pill">
                                                        <i class="fas fa-door-open me-1"></i> {{ $gradeLevelStats[$gradeLevel]['sections'] }} {{ Str::plural('Section', $gradeLevelStats[$gradeLevel]['sections']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Sections List -->
                        <div class="row">
                            @foreach($gradeSections as $section)
                                <div class="col-xl-4 col-md-6 mb-4 section-card">
                                    <div class="card border-0 shadow-sm h-100">
                                        <!-- Section Header -->
                                        <div class="card-header bg-primary text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0 fw-bold">{{ $section->name }}</h5>
                                                <span class="badge bg-white text-primary">
                                                    {{ $sectionStats[$section->id]['total'] ?? 0 }} {{ Str::plural('Student', $sectionStats[$section->id]['total'] ?? 0) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Section Statistics -->
                                        <div class="bg-light py-2 px-3 border-bottom">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-primary rounded-pill" data-bs-toggle="tooltip" title="Total Students">
                                                        <i class="fas fa-users me-1"></i> {{ $sectionStats[$section->id]['total'] ?? 0 }}
                                                    </span>
                                                    <span class="badge bg-primary bg-opacity-75 rounded-pill" data-bs-toggle="tooltip" title="Male Students">
                                                        <i class="fas fa-male me-1"></i> {{ $sectionStats[$section->id]['male'] ?? 0 }}
                                                    </span>
                                                    <span class="badge bg-danger rounded-pill" data-bs-toggle="tooltip" title="Female Students">
                                                        <i class="fas fa-female me-1"></i> {{ $sectionStats[$section->id]['female'] ?? 0 }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="badge bg-success rounded-pill" data-bs-toggle="tooltip" title="Active Students">
                                                        <i class="fas fa-user-check me-1"></i> {{ $sectionStats[$section->id]['active'] ?? 0 }}
                                                    </span>
                                                    @if(($sectionStats[$section->id]['inactive'] ?? 0) > 0)
                                                        <span class="badge bg-secondary rounded-pill" data-bs-toggle="tooltip" title="Disabled Students">
                                                            <i class="fas fa-user-slash me-1"></i> {{ $sectionStats[$section->id]['inactive'] ?? 0 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            @php
                                                $totalStudents = $sectionStats[$section->id]['total'] ?? 0;
                                                $maleStudents = $sectionStats[$section->id]['male'] ?? 0;
                                                $femaleStudents = $sectionStats[$section->id]['female'] ?? 0;

                                                $malePercentage = $totalStudents > 0 ? ($maleStudents / $totalStudents) * 100 : 0;
                                                $femalePercentage = $totalStudents > 0 ? ($femaleStudents / $totalStudents) * 100 : 0;
                                            @endphp

                                            @if($totalStudents > 0)
                                                <!-- Gender Distribution Bar -->
                                                <div class="progress" style="height: 6px;" data-bs-toggle="tooltip" title="Gender Distribution: {{ round($malePercentage) }}% Male, {{ round($femalePercentage) }}% Female">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $malePercentage }}%" aria-valuenow="{{ $malePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $femalePercentage }}%" aria-valuenow="{{ $femalePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Section Info -->
                                        <div class="card-body p-0">
                                            <div class="px-3 py-2 bg-white border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 p-1 rounded-circle me-2">
                                                        <i class="fas fa-user-tie text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted small me-1">Adviser:</span>
                                                        <span class="fw-medium">
                                                            @if($section->adviser)
                                                                {{ $section->adviser->name }}
                                                            @else
                                                                <span class="text-danger">No adviser assigned</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Students Table -->
                                            @if(count($studentsBySection[$section->id] ?? []) > 0)
                                                <div class="scrollable-table position-relative" style="max-height: 300px; overflow-y: auto;">
                                                    <table class="table table-hover align-middle mb-0">
                                                        <thead>
                                                            <tr class="table-light sticky-top">
                                                                <th class="ps-3 bg-light">Student</th>
                                                                <th class="text-end pe-3 bg-light">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $hasActiveStudents = false;
                                                                $hasInactiveStudents = false;
                                                                $inactiveStarted = false;
                                                            @endphp

                                                            @foreach($studentsBySection[$section->id] ?? [] as $index => $student)
                                                                @php
                                                                    if ($student->is_active) {
                                                                        $hasActiveStudents = true;
                                                                    } else {
                                                                        $hasInactiveStudents = true;

                                                                        // Check if this is the first inactive student
                                                                        if (!$inactiveStarted) {
                                                                            $inactiveStarted = true;
                                                                        }
                                                                    }
                                                                @endphp

                                                                @if($inactiveStarted && $index > 0 && $studentsBySection[$section->id][$index-1]->is_active)
                                                                    <!-- Separator between active and inactive students -->
                                                                    <tr class="table-secondary">
                                                                        <td colspan="2" class="text-center py-2">
                                                                            <small class="text-muted"><i class="fas fa-user-slash me-1"></i> Disabled Students</small>
                                                                        </td>
                                                                    </tr>
                                                                @endif

                                                                <tr class="{{ !$student->is_active ? 'text-muted bg-light' : '' }}">
                                                                    <td class="ps-3">
                                                                        <div class="d-flex align-items-center py-1">
                                                                            <div class="avatar-circle bg-{{ $student->gender == 'Male' ? 'primary' : 'danger' }} bg-opacity-10 p-2 rounded-circle me-3 text-center">
                                                                                <i class="fas fa-user text-{{ $student->gender == 'Male' ? 'primary' : 'danger' }}"></i>
                                                                            </div>
                                                                            <div>
                                                                                <div class="fw-bold">{{ $student->surname_first }}</div>
                                                                                <div class="text-muted small">ID: {{ $student->student_id }}</div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-end pe-3">
                                                                        @if($student->is_active)
                                                                            <span class="badge bg-success">Active</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">Disabled</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @if(count($studentsBySection[$section->id] ?? []) > 5)
                                                    <div class="text-center py-1 border-top">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle me-1"></i> Scroll to see all students
                                                        </small>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center py-5">
                                                    <div class="mb-3">
                                                        <div class="bg-light p-3 rounded-circle d-inline-block">
                                                            <i class="fas fa-user-graduate text-secondary fa-2x"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="text-muted">No Students Found</h6>
                                                    <p class="text-muted small mb-0">There are no students assigned to this section yet.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- No Results Message -->
                <div id="noResultsMessage" class="text-center py-5 d-none">
                    <div class="mb-3">
                        <div class="bg-light p-3 rounded-circle d-inline-block">
                            <i class="fas fa-search text-secondary fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="text-muted">No Matching Results</h5>
                    <p class="text-muted">Try adjusting your search to find what you're looking for.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast notification for copy success -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="copySuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> Registration key copied to clipboard!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Inline script to ensure grade level content is displayed -->
<script>
    // Execute immediately
    (function() {
        // Get the current grade level
        var select = document.getElementById('gradeLevelSelect');
        if (select && select.value) {
            // Hide all grade level contents
            var contents = document.querySelectorAll('.grade-level-content');
            for (var i = 0; i < contents.length; i++) {
                contents[i].style.display = 'none';
            }

            // Show the selected grade level content
            var selectedContent = document.getElementById('grade-' + select.value + '-content');
            if (selectedContent) {
                selectedContent.style.display = 'block';

            }
        }
    })();
</script>
@endsection

@push('scripts')
<script>
    // Global function to switch grade level
    function switchGradeLevel(gradeLevel) {
        // Hide all grade level contents
        document.querySelectorAll('.grade-level-content').forEach(function(el) {
            el.style.display = 'none';
        });

        // Show the selected grade level content
        const selectedContent = document.getElementById(`grade-${gradeLevel}-content`);
        if (selectedContent) {
            selectedContent.style.display = 'block';
        }
    }

    // Function to copy registration key to clipboard
    function copyKeyToClipboard(key) {
        navigator.clipboard.writeText(key).then(function() {
            // Show success message
            const toast = new bootstrap.Toast(document.getElementById('copySuccessToast'));
            toast.show();
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    // Function to copy all registration keys to clipboard
    function copyAllKeysToClipboard() {
        const allKeysText = document.getElementById('allKeysText').value;
        navigator.clipboard.writeText(allKeysText).then(function() {
            // Show success message
            const toast = new bootstrap.Toast(document.getElementById('copySuccessToast'));
            document.querySelector('#copySuccessToast .toast-body').innerHTML =
                '<i class="fas fa-check-circle me-2"></i> All registration keys copied to clipboard!';
            toast.show();
        }).catch(function(err) {
            console.error('Could not copy all keys: ', err);
        });
    }
    // Initialize when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the grade level content
        const select = document.getElementById('gradeLevelSelect');
        if (select && select.value) {
            switchGradeLevel(select.value);
        }
    });

    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize toast and add event listener to reset content
        const copySuccessToast = document.getElementById('copySuccessToast');
        if (copySuccessToast) {
            const toast = new bootstrap.Toast(copySuccessToast);
            copySuccessToast.addEventListener('hidden.bs.toast', function () {
                // Reset toast content to default after it's hidden
                document.querySelector('#copySuccessToast .toast-body').innerHTML =
                    '<i class="fas fa-check-circle me-2"></i> Registration key copied to clipboard!';
            });
        }

        // Add event listeners for copy buttons
        document.querySelectorAll('.copy-key-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                copyKeyToClipboard(key);

                // Change button text temporarily
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-success');

                // Reset button after 2 seconds
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-primary');
                }, 2000);
            });
        });

        // Add event listener for copy all keys button
        const copyAllButton = document.querySelector('.copy-all-keys-btn');
        if (copyAllButton) {
            copyAllButton.addEventListener('click', function() {
                copyAllKeysToClipboard();

                // Change button text temporarily
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> All Keys Copied!';
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');

                // Reset button after 2 seconds
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-primary');
                }, 2000);
            });
        }

        // Initialize the first grade level content to be visible
        if ($(".grade-level-content").length > 0) {
            // Get the first grade level from the select
            const firstGradeLevel = $("#gradeLevelSelect").val();

            // Use the switchGradeLevel function for consistency
            if (firstGradeLevel) {
                switchGradeLevel(firstGradeLevel);
            } else {
                // Fallback to first content if select value is not available
                $(".grade-level-content").hide();
                $(".grade-level-content:first").show();
            }
        }

        // Grade level select navigation
        $("#gradeLevelSelect").on("change", function() {
            const gradeLevel = $(this).val();

            // Use the global function for consistency
            switchGradeLevel(gradeLevel);
        });

        // Teacher search functionality
        $("#teacherSearch").on("keyup", function() {
            const value = $(this).val().toLowerCase();
            let hasResults = false;

            $("#teachersTable tbody tr").each(function() {
                const isVisible = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(isVisible);
                if (isVisible) hasResults = true;
            });

            // Show no results message if needed
            if (!hasResults && value.length > 0) {
                if ($("#teachersNoResults").length === 0) {
                    $("#teachersTable").after(`
                        <div id="teachersNoResults" class="text-center py-5">
                            <div class="mb-3">
                                <div class="bg-light p-3 rounded-circle d-inline-block">
                                    <i class="fas fa-search text-secondary fa-2x"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">No Matching Teachers</h5>
                            <p class="text-muted">Try adjusting your search to find what you're looking for.</p>
                        </div>
                    `);
                } else {
                    $("#teachersNoResults").show();
                }
            } else {
                $("#teachersNoResults").hide();
            }
        });

        // Section and student search functionality
        $("#sectionSearch").on("keyup", function() {
            const value = $(this).val().toLowerCase();
            let totalVisibleSections = 0;
            let visibleGradeLevels = [];

            // Process each grade level
            $(".grade-level-content").each(function() {
                const gradeContent = $(this);
                let visibleSections = 0;

                // Check each section card
                gradeContent.find(".section-card").each(function() {
                    const sectionCard = $(this);
                    const sectionText = sectionCard.text().toLowerCase();

                    if (sectionText.indexOf(value) > -1) {
                        sectionCard.show();
                        visibleSections++;
                        totalVisibleSections++;
                    } else {
                        sectionCard.hide();
                    }
                });

                // Get grade level from ID
                const gradeId = gradeContent.attr("id");
                const gradeLevel = gradeId.replace("grade-", "").replace("-content", "");

                // Update select dropdown options
                const selectOption = $(`#gradeLevelSelect option[value="${gradeLevel}"]`);

                if (visibleSections > 0) {
                    selectOption.show();
                    // Update the option text with count
                    selectOption.text(`Grade ${gradeLevel} (${visibleSections})`);
                    // Add to visible grade levels array
                    visibleGradeLevels.push(gradeLevel);
                } else {
                    selectOption.hide();
                }
            });

            // Show no results message if needed
            if (totalVisibleSections === 0) {
                $("#noResultsMessage").removeClass("d-none");

                // If no visible sections, hide all grade content
                $(".grade-level-content").addClass("d-none");

                // Add a "No results" option to the select
                $("#gradeLevelSelect").html('<option value="">No matching sections</option>');
            } else {
                $("#noResultsMessage").addClass("d-none");

                // If current grade has no visible sections, switch to first grade with visible sections
                const currentGradeContent = $(".grade-level-content:not(.d-none)");
                const currentGradeHasVisible = currentGradeContent.find(".section-card:visible").length > 0;

                if (!currentGradeHasVisible && visibleGradeLevels.length > 0) {
                    // Show the first grade level with visible sections
                    const firstVisibleGradeLevel = visibleGradeLevels[0];
                    $(".grade-level-content").addClass("d-none");
                    $(`#grade-${firstVisibleGradeLevel}-content`).removeClass("d-none");

                    // Update select dropdown
                    $("#gradeLevelSelect").val(firstVisibleGradeLevel);
                }
            }

            // If search is cleared, reset to default view
            if (value === "") {
                // Show all section cards
                $(".section-card").show();

                // Reload the page to reset everything cleanly
                location.reload();
            }
        });

        // Final initialization to ensure grade level content is displayed
        setTimeout(function() {
            const currentGradeLevel = $("#gradeLevelSelect").val();
            if (currentGradeLevel) {
                // Force display of the content
                const contentElement = document.getElementById(`grade-${currentGradeLevel}-content`);
                if (contentElement) {
                    // Hide all other contents
                    document.querySelectorAll('.grade-level-content').forEach(function(el) {
                        el.style.display = 'none';
                    });

                    // Show this content with !important to override any CSS
                    contentElement.style.cssText = 'display: block !important';
                }
            }
        }, 1000);
    });
</script>
@endpush

@push('scripts-end')
<script>
    // Direct initialization at the end of the body
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for everything to be fully loaded
        setTimeout(function() {
            // Get the current grade level
            var select = document.getElementById('gradeLevelSelect');
            if (select && select.value) {
                var gradeLevel = select.value;

                // Force display of the content
                var contentElement = document.getElementById('grade-' + gradeLevel + '-content');
                if (contentElement) {
                    // Hide all other contents
                    var allContents = document.querySelectorAll('.grade-level-content');
                    for (var i = 0; i < allContents.length; i++) {
                        allContents[i].style.display = 'none';
                    }

                    // Show this content
                    contentElement.style.display = 'block';
                }
            }
        }, 500);
    });
</script>
@endpush
