@extends('layouts.app')

@section('content')
<!-- Toast container for notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5" id="toastContainer"></div>
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="fas fa-cog text-primary me-2"></i>Billing Settings</h2>
            <p class="text-muted">Manage billing settings for {{ $school->name }}</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.schools.show', $school) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Back to School Details
            </a>
        </div>
    </div>

    <!-- Subscription Status Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Current Subscription Status</h5>
                            <p>
                                <strong>Status:</strong>
                                @if($school->onTrial())
                                    <span class="badge bg-info">Trial</span>
                                @elseif($school->hasActiveSubscription())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Expired</span>
                                @endif
                            </p>
                            <p><strong>Trial Status:</strong>
                                @if($school->trial_ends_at)
                                    @if($school->subscription_status === 'trial')
                                        <span class="badge bg-info">Active Trial</span>
                                        @php
                                            // Use Philippine timezone
                                            $now = now()->setTimezone('Asia/Manila');
                                            $trialEndsAt = $school->trial_ends_at->setTimezone('Asia/Manila');
                                            $diffInHours = $now->diffInHours($trialEndsAt, false);
                                            $daysRemaining = (int)floor($diffInHours / 24);
                                            $hoursRemaining = (int)($diffInHours % 24);
                                        @endphp
                                        @if($diffInHours > 0)
                                            @if($daysRemaining > 0)
                                                ({{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }} remaining)
                                            @else
                                                ({{ $hoursRemaining }} {{ Str::plural('hour', $hoursRemaining) }} remaining)
                                            @endif
                                        @else
                                            (Expired)
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Trial Completed</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>
                            <p><strong>Subscription Ends:</strong> {{ $school->subscription_ends_at ? $school->subscription_ends_at->setTimezone('Asia/Manila')->format('F d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Billing Information</h5>
                            <p><strong>Billing Cycle:</strong> <span class="badge bg-primary">{{ ucfirst($school->billing_cycle) }}</span></p>
                            <p><strong>Monthly Price:</strong> ₱{{ number_format($school->monthly_price, 2) }}</p>
                            <p><strong>Yearly Price:</strong> ₱{{ number_format($school->yearly_price, 2) }}</p>
                            <p class="text-muted small"><i class="fas fa-info-circle me-1"></i> Current subscription status is automatically loaded in the form below.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Billing Settings Form -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-cog text-success me-2"></i>Billing Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.schools.update-billing', $school->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="subscription_status" class="form-label">Subscription Status</label>
                            <select name="subscription_status" id="subscription_status" class="form-select @error('subscription_status') is-invalid @enderror">
                                <option value="trial" {{ $school->subscription_status === 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="active" {{ $school->subscription_status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ $school->subscription_status === 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('subscription_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="trial_ends_at" class="form-label">Trial End Date</label>
                            <div class="input-group">
                                <input type="datetime-local" name="trial_ends_at" id="trial_ends_at" class="form-control @error('trial_ends_at') is-invalid @enderror" value="{{ $school->trial_ends_at ? $school->trial_ends_at->format('Y-m-d\TH:i') : now()->addMonths(3)->format('Y-m-d\TH:i') }}" {{ $school->trial_ends_at ? '' : 'disabled' }}>
                                <button class="btn btn-outline-secondary" type="button" id="toggleTrialDate">{{ $school->trial_ends_at ? 'Remove' : 'Set Date' }}</button>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_trial_ends_at" name="remove_trial_ends_at" {{ !$school->trial_ends_at ? 'checked' : '' }}>
                                <label class="form-check-label" for="remove_trial_ends_at">
                                    No trial end date (unlimited trial)
                                </label>
                            </div>
                            @error('trial_ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subscription_ends_at" class="form-label">Subscription End Date</label>
                            <div class="input-group">
                                <input type="datetime-local" name="subscription_ends_at" id="subscription_ends_at" class="form-control @error('subscription_ends_at') is-invalid @enderror" value="{{ $school->subscription_ends_at ? $school->subscription_ends_at->format('Y-m-d\TH:i') : '' }}" {{ $school->subscription_ends_at ? '' : 'disabled' }}>
                                <button class="btn btn-outline-secondary" type="button" id="toggleSubscriptionDate">{{ $school->subscription_ends_at ? 'Remove' : 'Set Date' }}</button>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_subscription_ends_at" name="remove_subscription_ends_at" {{ !$school->subscription_ends_at ? 'checked' : '' }}>
                                <label class="form-check-label" for="remove_subscription_ends_at">
                                    No subscription end date (unlimited subscription)
                                </label>
                            </div>
                            <div class="text-muted small mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                @if($school->onTrial())
                                    For schools on trial, subscription end date should be empty until a payment is made.
                                @else
                                    Set this date when the school has an active subscription.
                                @endif
                            </div>
                            @error('subscription_ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Billing Cycle</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="billing_cycle" id="monthly" value="monthly" {{ $school->billing_cycle === 'monthly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="monthly">Monthly</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="billing_cycle" id="yearly" value="yearly" {{ $school->billing_cycle === 'yearly' ? 'checked' : '' }}>
                                <label class="form-check-label" for="yearly">Yearly</label>
                            </div>
                            @error('billing_cycle')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="monthly_price" class="form-label">Monthly Price (₱)</label>
                                    <input type="number" name="monthly_price" id="monthly_price" class="form-control @error('monthly_price') is-invalid @enderror" value="{{ old('monthly_price', $school->monthly_price) }}" step="0.01" min="0">
                                    @error('monthly_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="yearly_price" class="form-label">Yearly Price (₱)</label>
                                    <input type="number" name="yearly_price" id="yearly_price" class="form-control @error('yearly_price') is-invalid @enderror" value="{{ old('yearly_price', $school->yearly_price) }}" step="0.01" min="0">
                                    @error('yearly_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.schools.show', $school) }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subscriptionStatusSelect = document.getElementById('subscription_status');
        const trialEndsAtInput = document.getElementById('trial_ends_at');
        const subscriptionEndsAtInput = document.getElementById('subscription_ends_at');
        const billingCycleRadios = document.querySelectorAll('input[name="billing_cycle"]');
        const monthlyPriceInput = document.getElementById('monthly_price');
        const yearlyPriceInput = document.getElementById('yearly_price');

        const removeTrialCheckbox = document.getElementById('remove_trial_ends_at');
        const removeSubscriptionCheckbox = document.getElementById('remove_subscription_ends_at');
        const toggleTrialDateBtn = document.getElementById('toggleTrialDate');
        const toggleSubscriptionDateBtn = document.getElementById('toggleSubscriptionDate');

        // Store current school data for easy access
        @php
        $status = $school->onTrial() ? 'trial' : ($school->hasActiveSubscription() ? 'active' : 'expired');
        $trialEndsAt = $school->trial_ends_at ? "'" . $school->trial_ends_at->format('Y-m-d\TH:i') . "'" : 'null';
        $subscriptionEndsAt = $school->subscription_ends_at ? "'" . $school->subscription_ends_at->format('Y-m-d\TH:i') . "'" : 'null';
        @endphp

        const schoolData = {
            subscriptionStatus: '{{ $status }}',
            trialEndsAt: {!! $trialEndsAt !!},
            subscriptionEndsAt: {!! $subscriptionEndsAt !!},
            billingCycle: '{{ $school->billing_cycle }}',
            monthlyPrice: {{ $school->monthly_price }},
            yearlyPrice: {{ $school->yearly_price }}
        };

        // Function to apply current school status to the form
        function applyCurrentStatus() {
            // Set subscription status
            subscriptionStatusSelect.value = schoolData.subscriptionStatus;

            // Set trial end date
            if (schoolData.trialEndsAt) {
                trialEndsAtInput.value = schoolData.trialEndsAt;
                trialEndsAtInput.disabled = false;
                removeTrialCheckbox.checked = false;
                toggleTrialDateBtn.textContent = 'Remove';
            } else {
                trialEndsAtInput.disabled = true;
                removeTrialCheckbox.checked = true;
                toggleTrialDateBtn.textContent = 'Set Date';
            }

            // Set subscription end date
            // If school is on trial, subscription end date should be empty
            if (schoolData.subscriptionStatus === 'trial') {
                subscriptionEndsAtInput.disabled = true;
                removeSubscriptionCheckbox.checked = true;
                toggleSubscriptionDateBtn.textContent = 'Set Date';
            } else if (schoolData.subscriptionEndsAt) {
                subscriptionEndsAtInput.value = schoolData.subscriptionEndsAt;
                subscriptionEndsAtInput.disabled = false;
                removeSubscriptionCheckbox.checked = false;
                toggleSubscriptionDateBtn.textContent = 'Remove';
            } else {
                subscriptionEndsAtInput.disabled = true;
                removeSubscriptionCheckbox.checked = true;
                toggleSubscriptionDateBtn.textContent = 'Set Date';
            }

            // Set billing cycle
            document.querySelector(`input[name="billing_cycle"][value="${schoolData.billingCycle}"]`).checked = true;

            // Set prices
            monthlyPriceInput.value = schoolData.monthlyPrice;
            yearlyPriceInput.value = schoolData.yearlyPrice;

            // Show success message
            showToast('Current status applied to form successfully');
        }

        // Function to show toast notification
        function showToast(message) {
            const toastContainer = document.getElementById('toastContainer');

            const toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center text-white bg-success border-0';
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');

            const toastBody = document.createElement('div');
            toastBody.className = 'd-flex';

            const messageDiv = document.createElement('div');
            messageDiv.className = 'toast-body';
            messageDiv.textContent = message;

            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'btn-close btn-close-white me-2 m-auto';
            closeButton.setAttribute('data-bs-dismiss', 'toast');
            closeButton.setAttribute('aria-label', 'Close');

            toastBody.appendChild(messageDiv);
            toastBody.appendChild(closeButton);
            toastEl.appendChild(toastBody);
            toastContainer.appendChild(toastEl);

            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();

            // Remove the toast from DOM after it's hidden
            toastEl.addEventListener('hidden.bs.toast', function() {
                toastContainer.removeChild(toastEl);
            });
        }

        // Function to sync dates based on subscription status
        function syncDates() {
            if (subscriptionStatusSelect.value === 'trial' && !removeTrialCheckbox.checked) {
                // If trial is selected, set a default trial end date (3 months from now)
                if (!trialEndsAtInput.value) {
                    const threeMonthsFromNow = new Date();
                    threeMonthsFromNow.setMonth(threeMonthsFromNow.getMonth() + 3);
                    trialEndsAtInput.value = threeMonthsFromNow.toISOString().slice(0, 16);
                }
            }
        }

        // Function to toggle trial date input
        function toggleTrialDate() {
            removeTrialCheckbox.checked = !removeTrialCheckbox.checked;
            trialEndsAtInput.disabled = removeTrialCheckbox.checked;
            toggleTrialDateBtn.textContent = removeTrialCheckbox.checked ? 'Set Date' : 'Remove';

            if (!removeTrialCheckbox.checked && !trialEndsAtInput.value) {
                const threeMonthsFromNow = new Date();
                threeMonthsFromNow.setMonth(threeMonthsFromNow.getMonth() + 3);
                trialEndsAtInput.value = threeMonthsFromNow.toISOString().slice(0, 16);
            }
        }

        // Function to toggle subscription date input
        function toggleSubscriptionDate() {
            removeSubscriptionCheckbox.checked = !removeSubscriptionCheckbox.checked;
            subscriptionEndsAtInput.disabled = removeSubscriptionCheckbox.checked;
            toggleSubscriptionDateBtn.textContent = removeSubscriptionCheckbox.checked ? 'Set Date' : 'Remove';

            if (!removeSubscriptionCheckbox.checked && !subscriptionEndsAtInput.value) {
                const oneYearFromNow = new Date();
                oneYearFromNow.setFullYear(oneYearFromNow.getFullYear() + 1);
                subscriptionEndsAtInput.value = oneYearFromNow.toISOString().slice(0, 16);
            }
        }

        // Function to toggle duration inputs based on billing cycle
        function toggleDurationInputs() {
            const selectedCycle = document.querySelector('input[name="billing_cycle"]:checked').value;

            // You can add additional logic here if needed
        }

        // Add event listeners
        trialEndsAtInput.addEventListener('change', syncDates);
        subscriptionStatusSelect.addEventListener('change', function() {
            if (this.value === 'trial') {
                syncDates();
            }
        });

        // Add event listeners to billing cycle radio buttons
        billingCycleRadios.forEach(function(radio) {
            radio.addEventListener('change', toggleDurationInputs);
        });

        // Add event listeners for trial and subscription date toggles
        toggleTrialDateBtn.addEventListener('click', toggleTrialDate);
        toggleSubscriptionDateBtn.addEventListener('click', toggleSubscriptionDate);

        removeTrialCheckbox.addEventListener('change', function() {
            trialEndsAtInput.disabled = this.checked;
            toggleTrialDateBtn.textContent = this.checked ? 'Set Date' : 'Remove';
        });

        removeSubscriptionCheckbox.addEventListener('change', function() {
            subscriptionEndsAtInput.disabled = this.checked;
            toggleSubscriptionDateBtn.textContent = this.checked ? 'Set Date' : 'Remove';
        });

        // Initialize the duration inputs on page load
        toggleDurationInputs();

        // Automatically apply current status when page loads
        applyCurrentStatus();
        showToast('Current subscription status loaded');
    });
</script>
@endpush
@endsection
