@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Payment Method Settings</h1>
            <p class="mb-0 text-muted">Manage payment methods available to schools</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex align-items-center">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Payment Method Settings</h5>
                        <p class="mb-0">Enable or disable payment methods for all schools. When a payment method is disabled, teacher admins will not be able to select it when making payments. You can also provide a message to explain why a payment method is unavailable.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.payment-methods.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Bank Transfer -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="payment-method-icon me-3" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                        <img src="{{ asset('images/bdo_icon.png') }}" alt="BDO" class="img-fluid">
                                    </div>
                                    <label class="form-check-label fw-bold mb-0 d-flex align-items-center" for="bank_transfer_enabled">
                                        Bank Transfer
                                        <span class="badge {{ $paymentMethodSettings['bank_transfer']['enabled'] ? 'bg-success' : 'bg-danger' }} ms-2 status-badge">
                                            {{ $paymentMethodSettings['bank_transfer']['enabled'] ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bank_transfer_enabled" name="payment_methods[bank_transfer][enabled]" {{ $paymentMethodSettings['bank_transfer']['enabled'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-group mb-0">
                                    <label for="bank_transfer_message" class="form-label fw-bold">Disabled Message</label>
                                    <textarea
                                        name="payment_methods[bank_transfer][message]"
                                        id="bank_transfer_message"
                                        class="form-control {{ $paymentMethodSettings['bank_transfer']['enabled'] ? 'bg-light' : '' }}"
                                        rows="3"
                                        placeholder="Message to display when this payment method is disabled"
                                        {{ $paymentMethodSettings['bank_transfer']['enabled'] ? 'readonly' : '' }}
                                    >{{ $paymentMethodSettings['bank_transfer']['message'] }}</textarea>
                                    <div class="form-text small">
                                        {{ $paymentMethodSettings['bank_transfer']['enabled'] ? 'This payment method is currently enabled. Disable it to set a message.' : 'This message will be shown to teacher admins when they try to use this payment method.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GCash -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="payment-method-icon me-3" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                        <img src="{{ asset('images/gcash_icon.png') }}" alt="GCash" class="img-fluid">
                                    </div>
                                    <label class="form-check-label fw-bold mb-0 d-flex align-items-center" for="gcash_enabled">
                                        GCash
                                        <span class="badge {{ $paymentMethodSettings['gcash']['enabled'] ? 'bg-success' : 'bg-danger' }} ms-2 status-badge">
                                            {{ $paymentMethodSettings['gcash']['enabled'] ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="gcash_enabled" name="payment_methods[gcash][enabled]" {{ $paymentMethodSettings['gcash']['enabled'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-group mb-0">
                                    <label for="gcash_message" class="form-label fw-bold">Disabled Message</label>
                                    <textarea
                                        name="payment_methods[gcash][message]"
                                        id="gcash_message"
                                        class="form-control {{ $paymentMethodSettings['gcash']['enabled'] ? 'bg-light' : '' }}"
                                        rows="3"
                                        placeholder="Message to display when this payment method is disabled"
                                        {{ $paymentMethodSettings['gcash']['enabled'] ? 'readonly' : '' }}
                                    >{{ $paymentMethodSettings['gcash']['message'] }}</textarea>
                                    <div class="form-text small">
                                        {{ $paymentMethodSettings['gcash']['enabled'] ? 'This payment method is currently enabled. Disable it to set a message.' : 'This message will be shown to teacher admins when they try to use this payment method.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PayMaya -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="payment-method-icon me-3" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                        <img src="{{ asset('images/maya_icon.png') }}" alt="PayMaya" class="img-fluid">
                                    </div>
                                    <label class="form-check-label fw-bold mb-0 d-flex align-items-center" for="paymaya_enabled">
                                        PayMaya
                                        <span class="badge {{ $paymentMethodSettings['paymaya']['enabled'] ? 'bg-success' : 'bg-danger' }} ms-2 status-badge">
                                            {{ $paymentMethodSettings['paymaya']['enabled'] ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="paymaya_enabled" name="payment_methods[paymaya][enabled]" {{ $paymentMethodSettings['paymaya']['enabled'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-group mb-0">
                                    <label for="paymaya_message" class="form-label fw-bold">Disabled Message</label>
                                    <textarea
                                        name="payment_methods[paymaya][message]"
                                        id="paymaya_message"
                                        class="form-control {{ $paymentMethodSettings['paymaya']['enabled'] ? 'bg-light' : '' }}"
                                        rows="3"
                                        placeholder="Message to display when this payment method is disabled"
                                        {{ $paymentMethodSettings['paymaya']['enabled'] ? 'readonly' : '' }}
                                    >{{ $paymentMethodSettings['paymaya']['message'] }}</textarea>
                                    <div class="form-text small">
                                        {{ $paymentMethodSettings['paymaya']['enabled'] ? 'This payment method is currently enabled. Disable it to set a message.' : 'This message will be shown to teacher admins when they try to use this payment method.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="payment-method-icon me-3 bg-secondary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; color: white;">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </div>
                                    <label class="form-check-label fw-bold mb-0 d-flex align-items-center" for="other_enabled">
                                        Other Payment Methods
                                        <span class="badge {{ $paymentMethodSettings['other']['enabled'] ? 'bg-success' : 'bg-danger' }} ms-2 status-badge">
                                            {{ $paymentMethodSettings['other']['enabled'] ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="other_enabled" name="payment_methods[other][enabled]" {{ $paymentMethodSettings['other']['enabled'] ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-group mb-0">
                                    <label for="other_message" class="form-label fw-bold">Disabled Message</label>
                                    <textarea
                                        name="payment_methods[other][message]"
                                        id="other_message"
                                        class="form-control {{ $paymentMethodSettings['other']['enabled'] ? 'bg-light' : '' }}"
                                        rows="3"
                                        placeholder="Message to display when this payment method is disabled"
                                        {{ $paymentMethodSettings['other']['enabled'] ? 'readonly' : '' }}
                                    >{{ $paymentMethodSettings['other']['message'] }}</textarea>
                                    <div class="form-text small">
                                        {{ $paymentMethodSettings['other']['enabled'] ? 'This payment method is currently enabled. Disable it to set a message.' : 'This message will be shown to teacher admins when they try to use this payment method.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all toggle switches
        const toggles = document.querySelectorAll('.form-check-input[type="checkbox"]');
        
        // Add event listeners to each toggle
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const method = this.id.replace('_enabled', '');
                const textarea = document.getElementById(`${method}_message`);
                const badge = this.closest('.card-header').querySelector('.status-badge');
                const helpText = textarea.nextElementSibling;
                
                if (this.checked) {
                    // Method is enabled
                    badge.classList.remove('bg-danger');
                    badge.classList.add('bg-success');
                    badge.textContent = 'Enabled';
                    
                    textarea.classList.add('bg-light');
                    textarea.setAttribute('readonly', true);
                    helpText.textContent = 'This payment method is currently enabled. Disable it to set a message.';
                } else {
                    // Method is disabled
                    badge.classList.remove('bg-success');
                    badge.classList.add('bg-danger');
                    badge.textContent = 'Disabled';
                    
                    textarea.classList.remove('bg-light');
                    textarea.removeAttribute('readonly');
                    helpText.textContent = 'This message will be shown to teacher admins when they try to use this payment method.';
                }
            });
        });

        // Add form submit handler to ensure all fields are enabled before submission
        document.querySelector('form').addEventListener('submit', function() {
            // Enable all textareas to ensure their values are submitted
            document.querySelectorAll('textarea[readonly]').forEach(textarea => {
                // We keep the readonly attribute but remove any disabled attribute
                if (textarea.hasAttribute('disabled')) {
                    textarea.removeAttribute('disabled');
                }
            });
        });
    });
</script>
@endpush
