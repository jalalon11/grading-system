@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="fw-bold display-6 mb-1"><i class="fas fa-credit-card text-primary me-2"></i>Make Payment</h2>
                    <p class="text-muted">Submit a new payment for your school's subscription.</p>
                </div>
                <a href="{{ route('teacher-admin.payments.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to Payments
                </a>
            </div>
        </div>
    </div>

    <!-- Payment Process Steps -->
    <div class="row mb-4">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow payment-steps-card">
                <div class="card-body p-0">
                    <div class="payment-steps d-flex">
                        <div class="payment-step active flex-fill text-center p-3 position-relative" id="step1">
                            <div class="step-number">1</div>
                            <div class="step-title">Select Plan</div>
                        </div>
                        <div class="payment-step flex-fill text-center p-3 position-relative" id="step2">
                            <div class="step-number">2</div>
                            <div class="step-title d-none d-sm-block">Select Payment Method</div>
                            <div class="step-title d-block d-sm-none">Payment Method</div>
                        </div>
                        <div class="payment-step flex-fill text-center p-3 position-relative" id="step3">
                            <div class="step-number">3</div>
                            <div class="step-title d-none d-sm-block">Enter Reference Number</div>
                            <div class="step-title d-block d-sm-none">Reference #</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="card-header bg-primary bg-gradient text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-money-bill-wave text-white me-2"></i>
                        Payment Details
                    </h5>
                </div>
                <div class="card-body p-lg-4">
                    <form action="{{ route('teacher-admin.payments.store') }}" method="POST">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-lg-5">
                                <div class="card bg-light border-0 rounded-3 h-100">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-school me-2"></i>School Information</h5>
                                        <div class="d-flex align-items-center mb-3">
                                            @if($school->logo_path)
                                                <div class="me-3" style="width: 60px; height: 60px; position: relative; border-radius: 50%; overflow: hidden; border: 2px solid #f8f9fc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                    <img src="{{ $school->logo_url }}" alt="{{ $school->name }}" style="position: absolute; width: 100%; height: 100%; object-fit: contain; top: 0; left: 0;">
                                                </div>
                                            @else
                                                <div class="me-3" style="width: 60px; height: 60px; border-radius: 50%; background-color: #4e73df; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 24px; text-transform: uppercase; border: 2px solid #f8f9fc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                    {{ substr($school->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="fw-bold mb-1 fs-5">{{ $school->name }}</h6>
                                                <div>
                                                    @if($school->onTrial())
                                                        <span class="badge bg-info rounded-pill px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> Trial</span>
                                                    @elseif($school->hasActiveSubscription())
                                                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> Active</span>
                                                    @else
                                                        <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-exclamation-circle me-1"></i> Expired</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if($school->onTrial())
                                            <div class="alert alert-info rounded-3 border-0 shadow-sm">
                                                <div class="d-flex">
                                                    <div class="me-3 fs-4"><i class="fas fa-info-circle"></i></div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1">Trial Period</h6>
                                                        <p class="mb-0 small">Your trial period ends in {{ $school->getRemainingTrialTimeAttribute() }}. Subscribe now to continue using all features.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($school->subscriptionExpired())
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="card border-0 rounded-3 shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-3 text-success"><i class="fas fa-tags me-2"></i>Subscription Plans</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="pricing-card border rounded-3 h-100 p-3 position-relative">
                                                    <div class="ribbon ribbon-top-right"><span>Standard</span></div>
                                                    <h6 class="fw-bold mb-3">Monthly Plan</h6>
                                                    <div class="price-tag mb-3">
                                                        <span class="currency">₱</span>
                                                        <span class="amount">{{ number_format($school->monthly_price, 0) }}</span>
                                                        <span class="period">/month</span>
                                                    </div>
                                                    <ul class="feature-list ps-0 mb-3">
                                                        <li><i class="fas fa-check text-success me-2"></i> Full access to all features</li>
                                                        <li><i class="fas fa-check text-success me-2"></i> Monthly billing</li>
                                                        <li><i class="fas fa-check text-success me-2"></i> Cancel anytime</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="pricing-card border border-primary rounded-3 h-100 p-3 position-relative">
                                                    <div class="ribbon ribbon-top-right"><span class="bg-primary">Best Value</span></div>
                                                    <h6 class="fw-bold mb-3">Annual Plan</h6>
                                                    <div class="price-tag mb-3">
                                                        <span class="currency">₱</span>
                                                        <span class="amount">{{ number_format($school->yearly_price / 12, 0) }}</span>
                                                        <span class="period">/month</span>
                                                    </div>
                                                    <div class="total-price mb-3 small">₱{{ number_format($school->yearly_price, 2) }} billed annually</div>
                                                    <div class="savings-badge mb-3">
                                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                                            <i class="fas fa-piggy-bank me-1"></i> Save {{ round((1 - ($school->yearly_price / ($school->monthly_price * 12))) * 100) }}%
                                                        </span>
                                                    </div>
                                                    <ul class="feature-list ps-0 mb-0">
                                                        <li><i class="fas fa-check text-success me-2"></i> Full access to all features</li>
                                                        <li><i class="fas fa-check text-success me-2"></i> Annual billing</li>
                                                        <li><i class="fas fa-check text-success me-2"></i> Priority support</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-calendar-alt me-2"></i>Select Billing Cycle</h5>
                            <div class="row g-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="billing-option h-100">
                                        <input class="billing-option-input" type="radio" name="billing_cycle" id="monthly" value="monthly" checked>
                                        <label class="billing-option-label" for="monthly">
                                            <div class="billing-option-inner p-3 p-md-4 h-100">
                                                <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                                    <span class="billing-title">Monthly</span>
                                                    <span class="billing-check"><i class="fas fa-check-circle"></i></span>
                                                </div>
                                                <div class="billing-price mb-2 mb-md-3">
                                                    <span class="currency">₱</span>
                                                    <span class="amount">{{ number_format($school->monthly_price, 0) }}</span>
                                                    <span class="period">/month</span>
                                                </div>
                                                <div class="billing-details">
                                                    <p class="mb-0 text-muted small">Billed monthly. Renew or cancel anytime.</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="billing-option h-100 position-relative">
                                        <div class="ribbon ribbon-top-right d-none d-md-block"><span style="background-color: #0d6efd;">Best Value</span></div>
                                        <input class="billing-option-input" type="radio" name="billing_cycle" id="yearly" value="yearly">
                                        <label class="billing-option-label" for="yearly">
                                            <div class="billing-option-inner p-3 p-md-4 h-100">
                                                <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                                    <span class="billing-title">Annual <span class="badge bg-warning text-dark ms-1 d-inline-block d-md-none">Best Value</span></span>
                                                    <span class="billing-check"><i class="fas fa-check-circle"></i></span>
                                                </div>
                                                <div class="billing-price mb-2">
                                                    <span class="currency">₱</span>
                                                    <span class="amount">{{ number_format($school->yearly_price, 0) }}</span>
                                                    <span class="period">/year</span>
                                                </div>
                                                <div class="savings-tag mb-2 mb-md-3">
                                                    <span class="badge bg-success rounded-pill px-2 px-md-3 py-1 py-md-2">
                                                        <i class="fas fa-piggy-bank me-1"></i> Save {{ round((1 - ($school->yearly_price / ($school->monthly_price * 12))) * 100) }}%
                                                    </span>
                                                </div>
                                                <div class="billing-details">
                                                    <p class="mb-0 text-muted small">Best value. One annual payment.</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-credit-card me-2"></i>Select Payment Method</h5>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="payment-method-option">
                                        <input class="payment-method-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                        <label class="payment-method-label" for="bank_transfer">
                                            <div class="payment-method-icon payment-icon-custom">
                                                <img src="{{ asset('images/bdo_icon.png') }}" alt="BDO" class="img-fluid payment-logo">
                                            </div>
                                            <div class="payment-method-title">Bank Transfer</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="payment-method-option">
                                        <input class="payment-method-input" type="radio" name="payment_method" id="gcash" value="gcash">
                                        <label class="payment-method-label" for="gcash">
                                            <div class="payment-method-icon payment-icon-custom">
                                                <img src="{{ asset('images/gcash_icon.png') }}" alt="GCash" class="img-fluid payment-logo">
                                            </div>
                                            <div class="payment-method-title">GCash</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="payment-method-option">
                                        <input class="payment-method-input" type="radio" name="payment_method" id="paymaya" value="paymaya">
                                        <label class="payment-method-label" for="paymaya">
                                            <div class="payment-method-icon payment-icon-custom">
                                                <img src="{{ asset('images/maya_icon.png') }}" alt="PayMaya" class="img-fluid payment-logo">
                                            </div>
                                            <div class="payment-method-title">PayMaya</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="payment-method-option">
                                        <input class="payment-method-input" type="radio" name="payment_method" id="other" value="other">
                                        <label class="payment-method-label" for="other">
                                            <div class="payment-method-icon bg-secondary">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </div>
                                            <div class="payment-method-title">Other</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method Instructions -->
                        <div id="payment-instructions" class="mb-4">
                            <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-info-circle me-2"></i>Payment Instructions</h5>

                            <!-- Bank Transfer Instructions -->
                            <div id="bank_transfer_instructions" class="payment-instruction-block" style="display: none;">
                                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="card-header text-white py-3" style="background: #204399;">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-university me-2"></i>Bank Transfer Instructions</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-light border-0 rounded-3 mb-4">
                                            <div class="d-flex">
                                                <div class="me-3 fs-3" style="color: #204399;"><i class="fas fa-info-circle"></i></div>
                                                <div>
                                                    <p class="mb-0">Please transfer the exact amount to the bank account below using online banking or over-the-counter deposit. After completing the transfer, enter the reference number from your receipt.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card bg-light border-0 rounded-3 h-100">
                                                    <div class="card-body p-4">
                                                        <h6 class="fw-bold mb-3" style="color: #204399;"><i class="fas fa-university me-2"></i>Bank Account Details</h6>
                                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                                            <div class="icon-circle text-white me-3" style="background-color: #204399;">
                                                                <i class="fas fa-university"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">Bank Name</div>
                                                                <div class="fw-bold">BDO</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                                            <div class="icon-circle text-white me-3" style="background-color: #204399;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">Account Name</div>
                                                                <div class="fw-bold">Vincent Jhanrey Jalalon</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                                            <div class="icon-circle text-white me-3" style="background-color: #204399;">
                                                                <i class="fas fa-hashtag"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">Account Number</div>
                                                                <div class="fw-bold">4895 0415 0302 6316</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                                            <div class="icon-circle text-white me-3" style="background-color: #204399;">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">Branch</div>
                                                                <div class="fw-bold">Main Branch</div>
                                                            </div>
                                                        </div>
                                                        <div class="alert alert-warning mt-3 mb-0">
                                                            <div class="d-flex">
                                                                <div class="me-3"><i class="fas fa-exclamation-triangle"></i></div>
                                                                <div>
                                                                    <p class="mb-0">Bank QR codes have limited validity. Please use the account details above to make your transfer.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- GCash Instructions -->
                            <div id="gcash_instructions" class="payment-instruction-block" style="display: none;">
                                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="card-header bg-primary bg-gradient text-white py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-wallet me-2"></i>GCash Payment Instructions</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-light border-0 rounded-3 mb-4">
                                            <div class="d-flex">
                                                <div class="me-3 fs-3 text-primary"><i class="fas fa-info-circle"></i></div>
                                                <div>
                                                    <p class="mb-0">Please send the exact amount to the GCash account below or scan the QR code using your GCash app. After completing the payment, enter the reference number from your receipt.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-4 align-items-center">
                                            <div class="col-md-6">
                                                <div class="card bg-light border-0 rounded-3 h-100">
                                                    <div class="card-body p-4">
                                                        <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-wallet me-2"></i>GCash Account Details</h6>
                                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                                            <div class="icon-circle bg-primary text-white me-3">
                                                                <i class="fas fa-mobile-alt"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">GCash Number</div>
                                                                <div class="fw-bold">099 5627 7648</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-circle bg-primary text-white me-3">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">Account Name</div>
                                                                <div class="fw-bold">Grading System Inc.</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-white border-0 shadow-sm rounded-3 text-center">
                                                    <div class="card-body p-4">
                                                        <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-qrcode me-2"></i>Scan QR Code</h6>
                                                        <div class="qr-code-container mb-3 p-3 bg-light rounded-3 d-inline-block">
                                                            <img src="{{ asset('images/gcashQR.jpg') }}" alt="GCash QR Code" class="img-fluid qr-code-img" style="max-height: 180px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#qrModal" data-qr-src="{{ asset('images/gcashQR.jpg') }}" data-qr-title="GCash QR Code">
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-primary text-white rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#qrModal" data-qr-src="{{ asset('images/gcashQR.jpg') }}" data-qr-title="GCash QR Code">
                                                            <i class="fas fa-search-plus me-1"></i> Enlarge QR Code
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PayMaya Instructions -->
                            <div id="paymaya_instructions" class="payment-instruction-block" style="display: none;">
                                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="card-header text-white py-3" style="background: #1c9e77;">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-wallet me-2"></i>PayMaya Payment Instructions</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-light border-0 rounded-3 mb-4">
                                            <div class="d-flex">
                                                <div class="me-3 fs-3" style="color: #1c9e77;"><i class="fas fa-info-circle"></i></div>
                                                <div>
                                                    <p class="mb-0">Please send the exact amount to the PayMaya account below or scan the QR code using your PayMaya app. After completing the payment, enter the reference number from your receipt.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-4 align-items-center">
                                            <div class="col-md-6">
                                                <div class="card bg-light border-0 rounded-3 h-100">
                                                    <div class="card-body p-4">
                                                        <h6 class="fw-bold mb-3" style="color: #1c9e77;"><i class="fas fa-wallet me-2"></i>PayMaya Account Details</h6>
                                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                                            <div class="icon-circle text-white me-3" style="background-color: #1c9e77;">
                                                                <i class="fas fa-mobile-alt"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">PayMaya Number</div>
                                                                <div class="fw-bold">098 5512 5218</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-circle text-white me-3" style="background-color: #1c9e77;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                            <div>
                                                                <div class="text-muted small">Account Name</div>
                                                                <div class="fw-bold">Grading System Inc.</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card bg-white border-0 shadow-sm rounded-3 text-center">
                                                    <div class="card-body p-4">
                                                        <h6 class="fw-bold mb-3" style="color: #1c9e77;">Scan QR Code</h6>
                                                        <div class="qr-code-container mb-3 p-3 bg-light rounded-3 d-inline-block">
                                                            <img src="{{ asset('images/mayaQR.jpg') }}" alt="PayMaya QR Code" class="img-fluid qr-code-img" style="max-height: 180px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#qrModal" data-qr-src="{{ asset('images/mayaQR.jpg') }}" data-qr-title="PayMaya QR Code">
                                                        </div>
                                                        <button type="button" class="btn btn-sm text-white rounded-pill px-3" style="background-color: #1c9e77;" data-bs-toggle="modal" data-bs-target="#qrModal" data-qr-src="{{ asset('images/mayaQR.jpg') }}" data-qr-title="PayMaya QR Code">
                                                            <i class="fas fa-search-plus me-1"></i> Enlarge QR Code
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Other Payment Method Instructions -->
                            <div id="other_instructions" class="payment-instruction-block" style="display: none;">
                                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                    <div class="card-header bg-secondary bg-gradient text-white py-3">
                                        <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Other Payment Methods</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="alert alert-light border-0 rounded-3">
                                            <div class="d-flex">
                                                <div class="me-3 fs-3 text-secondary"><i class="fas fa-envelope"></i></div>
                                                <div>
                                                    <h6 class="fw-bold mb-2">Contact Administrator</h6>
                                                    <p class="mb-0">Please contact the administrator at <strong>vinz0799@gmail.com</strong> for alternative payment methods. After making the payment, please enter the reference number provided to you below.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                            <div class="card-header bg-primary bg-gradient text-white py-3">
                                <h5 class="mb-0 fw-bold"><i class="fas fa-receipt me-2"></i>Payment Reference Details</h5>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <div class="row g-3 g-md-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="form-group mb-0">
                                            <label for="reference_number" class="form-label fw-bold">Reference Number <span class="text-danger">*</span></label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text bg-light"><i class="fas fa-hashtag text-primary"></i></span>
                                                <input type="text" name="reference_number" id="reference_number" class="form-control form-control-lg @error('reference_number') is-invalid @enderror" placeholder="Enter reference number" required>
                                            </div>
                                            <div class="form-text small">Enter the reference number from your payment receipt.</div>
                                            @error('reference_number')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label for="notes" class="form-label fw-bold">Additional Notes</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text bg-light"><i class="fas fa-comment-alt text-primary"></i></span>
                                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Any additional information about your payment"></textarea>
                                            </div>
                                            @error('notes')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                            <div class="card-header bg-light py-3">
                                <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Important Information</h5>
                            </div>
                            <div class="card-body p-4">
                                @if($school->subscription_status === 'expired')
                                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-3">
                                    <div class="d-flex">
                                        <div class="me-3 fs-3"><i class="fas fa-exclamation-triangle"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-2">Subscription Expired - School Access Disabled</h6>
                                            <p class="mb-0">Your school's subscription has expired and access has been disabled for all teachers. Once your payment is approved, your school will be reactivated automatically.</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-info border-0 shadow-sm rounded-3 mb-3">
                                    <div class="d-flex">
                                        <div class="me-3 fs-3"><i class="fas fa-info-circle"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-2">Payment Review Process</h6>
                                            <p class="mb-0">Your payment will be reviewed by the administrator. Once approved, your subscription will be activated and you'll receive a confirmation email.</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-0">
                                    <div class="d-flex">
                                        <div class="me-3 fs-3"><i class="fas fa-exclamation-circle"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-2">Important Notice</h6>
                                            <p class="mb-0">To prevent duplicate submissions, you can only submit up to 3 payments per hour. Please ensure all payment details are correct before submitting.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top gap-3">
                            <a href="{{ route('teacher-admin.payments.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4 w-100 w-md-auto order-2 order-md-1">
                                <i class="fas fa-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 w-100 w-md-auto order-1 order-md-2 position-relative" id="submit-payment-btn">
                                <i class="fas fa-paper-plane me-2"></i> Submit Payment
                                <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle pulse-dot">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Payment Steps Styling */
.payment-steps-card {
    background: white;
    color: #5a5c69;
    overflow: hidden;
    border-radius: 0.75rem;
    box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.payment-steps {
    position: relative;
    display: flex;
    justify-content: space-between;
}

.payment-step {
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
    padding: 1.5rem 0.75rem !important;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.payment-step::after {
    content: '';
    position: absolute;
    top: 30px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: #e3e6f0;
    z-index: 1;
}

.payment-step:last-child::after {
    display: none;
}

.payment-step.active {
    color: #4e73df;
}

.payment-step.completed {
    color: #1cc88a;
}

.payment-step.completed::after {
    background: #1cc88a;
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f8f9fc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-weight: 700;
    position: relative;
    z-index: 3;
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
    font-size: 1.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    color: #858796;
}

.payment-step.active .step-number {
    background: #4e73df;
    color: white;
    border-color: #4e73df;
    box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.25), 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: scale(1.1);
}

.payment-step.completed .step-number {
    background: #1cc88a;
    color: white;
    border-color: #1cc88a;
    box-shadow: 0 0 0 4px rgba(28, 200, 138, 0.25), 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.step-title {
    font-weight: 700;
    font-size: 0.9rem;
    margin-top: 0.25rem;
    text-align: center;
}

/* Billing Option Styling */
.billing-option {
    position: relative;
}

.billing-option-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.billing-option-label {
    display: block;
    cursor: pointer;
    width: 100%;
    height: 100%;
    margin: 0;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.billing-option-inner {
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    height: 100%;
}

.billing-option-input:checked + .billing-option-label .billing-option-inner {
    border-color: #4e73df;
    background-color: #f8f9ff;
    box-shadow: 0 0 0 1px #4e73df;
}

.billing-option-input:focus + .billing-option-label .billing-option-inner {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.billing-title {
    font-weight: 600;
    font-size: 1.1rem;
}

.billing-check {
    color: #4e73df;
    font-size: 1.2rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.billing-option-input:checked + .billing-option-label .billing-check {
    opacity: 1;
}

.billing-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #5a5c69;
}

.billing-price .currency {
    font-size: 1rem;
    font-weight: 500;
    vertical-align: super;
    margin-right: 2px;
}

.billing-price .period {
    font-size: 0.9rem;
    font-weight: 400;
    color: #858796;
}

/* Payment Method Styling */
.payment-method-option {
    position: relative;
}

.payment-method-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.payment-method-label {
    display: block;
    cursor: pointer;
    text-align: center;
    padding: 1.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.payment-method-label:hover {
    background-color: #f8f9fa;
    transform: translateY(-3px);
}

.payment-method-input:checked + .payment-method-label {
    border-color: #4e73df;
    background-color: #f8f9ff;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    transform: translateY(-5px);
}

.payment-method-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #4e73df;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.payment-icon-custom {
    background-color: transparent;
    padding: 0;
    overflow: hidden;
}

.payment-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 50%;
}

.payment-method-input:checked + .payment-method-label .payment-method-icon {
    transform: scale(1.1);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.payment-method-title {
    font-weight: 600;
    transition: all 0.3s ease;
}

.payment-method-input:checked + .payment-method-label .payment-method-title {
    color: #4e73df;
}

/* Payment Instructions Styling */
.payment-instruction-block {
    display: none;
}

/* QR Code Styling */
.qr-code-container {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.qr-code-img {
    transition: all 0.3s ease;
}

.qr-code-img:hover {
    transform: scale(1.05);
}

.qr-zoom-image {
    transition: transform 0.2s ease;
    transform-origin: center;
}

.qr-zoom-container {
    overflow: hidden;
    padding: 20px;
}

/* Icon Circle */
.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

/* Ribbon Styling */
.pricing-card {
    position: relative;
    overflow: hidden;
}

.ribbon {
    width: 150px;
    height: 150px;
    overflow: hidden;
    position: absolute;
}

.ribbon span {
    position: absolute;
    display: block;
    width: 225px;
    padding: 5px 0;
    background-color: #f6c23e;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    color: #fff;
    font-size: 0.75rem;
    text-transform: uppercase;
    text-align: center;
    font-weight: 700;
    transform: rotate(-45deg);
}

.ribbon-top-right {
    top: -10px;
    right: -10px;
}

.ribbon-top-right span {
    left: -25px;
    top: 30px;
    transform: rotate(45deg);
}

/* Price Tag Styling */
.price-tag {
    font-size: 2rem;
    font-weight: 700;
    color: #5a5c69;
}

.price-tag .currency {
    font-size: 1.2rem;
    font-weight: 500;
    vertical-align: super;
    margin-right: 2px;
}

.price-tag .period {
    font-size: 1rem;
    font-weight: 400;
    color: #858796;
}

/* Feature List Styling */
.feature-list {
    list-style: none;
    margin-bottom: 0;
}

.feature-list li {
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.feature-list li:last-child {
    margin-bottom: 0;
}

/* Pulse Animation */
@keyframes pulse {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
    }
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}

.pulse-dot {
    animation: pulse 2s infinite;
}

.pulse-button {
    position: relative;
    overflow: visible;
}

.pulse-button::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50px;
    box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.7);
    animation: pulse-button 1.5s infinite;
}

@keyframes pulse-button {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(78, 115, 223, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(78, 115, 223, 0);
    }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .payment-steps-card {
        margin-bottom: 1.5rem;
    }

    .payment-step .step-number {
        width: 40px;
        height: 40px;
        font-size: 1rem;
        margin-bottom: 8px;
    }

    .step-title {
        font-size: 0.75rem;
    }

    .payment-step {
        padding: 1.25rem 0.5rem !important;
    }

    .payment-step::after {
        top: 25px;
    }

    .billing-price {
        font-size: 1.2rem;
    }

    .payment-method-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }

    .payment-method-label {
        padding: 1rem 0.5rem;
    }

    .payment-instruction-block .card-body {
        padding: 1rem !important;
    }

    .qr-code-container {
        padding: 0.5rem !important;
    }

    .qr-code-img {
        max-height: 150px !important;
    }

    .icon-circle {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .payment-steps::before {
        height: 2px;
    }

    .payment-step {
        padding: 0.5rem 0.25rem !important;
    }

    .payment-step .step-number {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
        margin-bottom: 4px;
    }

    .step-title {
        font-size: 0.7rem;
        white-space: nowrap;
    }

    .billing-option-inner {
        padding: 0.75rem !important;
    }

    .billing-title {
        font-size: 0.9rem;
    }

    .billing-price {
        font-size: 1.1rem;
        margin-bottom: 0.5rem !important;
    }

    .billing-price .currency {
        font-size: 0.8rem;
    }

    .billing-price .period {
        font-size: 0.7rem;
    }

    .billing-details p {
        font-size: 0.75rem;
    }

    .savings-tag .badge {
        font-size: 0.65rem;
    }

    .payment-method-title {
        font-size: 0.8rem;
    }

    .payment-method-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .alert .fs-3 {
        font-size: 1.5rem !important;
    }
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
        const instructionBlocks = document.querySelectorAll('.payment-instruction-block');
        const paymentForm = document.querySelector('form');
        const submitButton = document.getElementById('submit-payment-btn');
        const paymentSteps = document.querySelectorAll('.payment-step');
        const billingOptions = document.querySelectorAll('.billing-option-input');

        // No animations for cards

        // Track the currently displayed instruction block
        let currentInstructionBlock = null;

        // Function to show the appropriate instruction block without animation
        function showInstructions(methodValue) {
            const targetInstructionBlock = methodValue ? document.getElementById(methodValue + '_instructions') : null;

            // If the selected method is already showing, do nothing
            if (targetInstructionBlock === currentInstructionBlock) {
                return; // Already showing this instruction block
            }

            // Hide all instruction blocks first
            instructionBlocks.forEach(block => {
                block.style.display = 'none';
            });

            // Show the selected payment method instructions
            if (targetInstructionBlock) {
                // Show the block immediately
                targetInstructionBlock.style.display = 'block';

                // Scroll to the instructions
                targetInstructionBlock.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                // Update reference
                currentInstructionBlock = targetInstructionBlock;
            } else {
                currentInstructionBlock = null;
            }
        }

        // Function to update payment steps
        function updatePaymentSteps(activeStep) {
            paymentSteps.forEach((step, index) => {
                step.classList.remove('active', 'completed');

                if (index + 1 === activeStep) {
                    step.classList.add('active');
                } else if (index + 1 < activeStep) {
                    step.classList.add('completed');
                    step.querySelector('.step-number').innerHTML = '<i class="fas fa-check"></i>';
                } else {
                    step.querySelector('.step-number').textContent = index + 1;
                }
            });
        }

        // Add event listeners to payment method inputs
        paymentMethodInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    showInstructions(this.value);
                    // Update payment steps to show step 2 as active
                    updatePaymentSteps(2);
                }
            });
        });

        // Add event listeners to billing options
        billingOptions.forEach(option => {
            option.addEventListener('change', function() {
                if (this.checked) {
                    // Update payment steps
                    updatePaymentSteps(1);

                    // Update the QR modal amount if it's open
                    const amountText = document.getElementById('qrAmountText');
                    if (amountText) {
                        if (this.value === 'monthly') {
                            // Get the monthly price from the DOM
                            const monthlyPrice = document.querySelector('#monthly').closest('.billing-option').querySelector('.amount').textContent.trim();
                            amountText.textContent = '₱' + monthlyPrice;
                        } else if (this.value === 'yearly') {
                            // Get the yearly price from the DOM
                            const yearlyPrice = document.querySelector('#yearly').closest('.billing-option').querySelector('.amount').textContent.trim();
                            amountText.textContent = '₱' + yearlyPrice;
                        }
                    }
                }
            });
        });

        // Initialize payment steps
        updatePaymentSteps(1);

        // Initialize payment method selection
        // Only check if there's a pre-selected payment method (e.g., from form validation errors)
        const checkedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (checkedPaymentMethod) {
            // If there's a pre-selected method, show its instructions immediately
            showInstructions(checkedPaymentMethod.value);
            // Update payment steps to show step 2 as active
            updatePaymentSteps(2);
        }

        // Don't auto-select the first payment method

        // Prevent double submission and add loading animation
        paymentForm.addEventListener('submit', function(e) {
            // Validate form
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPaymentMethod) {
                e.preventDefault();

                // Show a more user-friendly error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show mt-3';
                errorAlert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="me-3 fs-4"><i class="fas fa-exclamation-circle"></i></div>
                        <div>
                            <strong>Payment Method Required</strong><br>
                            Please select a payment method to continue.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                // Insert the alert at the top of the payment methods section
                const paymentMethodsSection = document.querySelector('.payment-method-option').closest('.mb-4');
                paymentMethodsSection.insertBefore(errorAlert, paymentMethodsSection.firstChild);

                // Scroll to the payment methods section
                paymentMethodsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

                return false;
            }

            // If the form is already submitting, prevent additional submissions
            if (submitButton.disabled) {
                e.preventDefault();
                return false;
            }

            // Step 3 is already updated by the reference number input

            // Disable the submit button and change text
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing Payment...';

            // Add a simple loading overlay
            const formOverlay = document.createElement('div');
            formOverlay.className = 'position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 d-flex align-items-center justify-content-center';
            formOverlay.style.zIndex = '9999';
            formOverlay.innerHTML = `
                <div class="card border-0 shadow-lg p-4">
                    <div class="text-center mb-3">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <h5 class="text-center mb-3">Processing Your Payment</h5>
                    <p class="text-muted mb-0">Please wait while we process your payment information...</p>
                </div>
            `;
            document.body.appendChild(formOverlay);

            // Allow the form to submit
            return true;
        });

        // Add hover effects to QR codes
        document.querySelectorAll('.qr-code-img').forEach(img => {
            img.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });

            img.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Add event listener to reference number input to update step 3
        const referenceNumberInput = document.getElementById('reference_number');
        if (referenceNumberInput) {
            // Function to check reference number and update step 3
            function checkReferenceNumber() {
                const value = referenceNumberInput.value.trim();
                if (value.length > 0) {
                    // If reference number has a value, mark step 3 as active and completed
                    updatePaymentSteps(3);
                    // Show the pulse animation on the submit button
                    document.getElementById('submit-payment-btn').classList.add('pulse-button');
                    // Make step 3 completed
                    paymentSteps[2].classList.add('completed');
                    paymentSteps[2].querySelector('.step-number').innerHTML = '<i class="fas fa-check"></i>';
                } else {
                    // If reference number is empty, go back to step 2 if a payment method is selected
                    const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
                    if (selectedPaymentMethod) {
                        updatePaymentSteps(2);
                    } else {
                        updatePaymentSteps(1);
                    }
                    // Hide the pulse animation on the submit button
                    document.getElementById('submit-payment-btn').classList.remove('pulse-button');
                    // Remove completed state from step 3
                    paymentSteps[2].classList.remove('completed');
                    paymentSteps[2].querySelector('.step-number').textContent = '3';
                }
            }

            // Add event listeners for input changes
            referenceNumberInput.addEventListener('input', checkReferenceNumber);
            referenceNumberInput.addEventListener('change', checkReferenceNumber);
            referenceNumberInput.addEventListener('keyup', checkReferenceNumber);

            // Check initial state
            checkReferenceNumber();
        }
    });
</script>
<script>
    // QR Code Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded for QR modal');
        const qrModal = document.getElementById('qrModal');
        console.log('QR Modal element:', qrModal);
        if (qrModal) {
            let currentZoom = 1;
            let currentQrSrc = '';
            const zoomStep = 0.1;
            const maxZoom = 3;
            const minZoom = 0.5;

            // Get elements
            const modalImage = qrModal.querySelector('.qr-zoom-image');
            const zoomInBtn = qrModal.querySelector('.zoom-in-btn');
            const zoomOutBtn = qrModal.querySelector('.zoom-out-btn');
            const zoomResetBtn = qrModal.querySelector('.zoom-reset-btn');
            const downloadBtn = qrModal.querySelector('.download-qr-btn');

            // Zoom in function
            function zoomIn() {
                if (currentZoom < maxZoom) {
                    currentZoom += zoomStep;
                    modalImage.style.transform = `scale(${currentZoom})`;
                }
            }

            // Zoom out function
            function zoomOut() {
                if (currentZoom > minZoom) {
                    currentZoom -= zoomStep;
                    modalImage.style.transform = `scale(${currentZoom})`;
                }
            }

            // Reset zoom function
            function resetZoom() {
                currentZoom = 1;
                modalImage.style.transform = 'scale(1)';
            }

            // Add event listeners for zoom buttons
            zoomInBtn.addEventListener('click', zoomIn);
            zoomOutBtn.addEventListener('click', zoomOut);
            zoomResetBtn.addEventListener('click', resetZoom);

            // Download QR code
            downloadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const link = document.createElement('a');
                link.href = currentQrSrc;
                link.download = 'qr-code.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            // Modal show event
            qrModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const qrSrc = button.getAttribute('data-qr-src');
                const qrTitle = button.getAttribute('data-qr-title');

                const modalTitle = qrModal.querySelector('.modal-title');

                // Debug
                console.log('QR Modal opened');
                console.log('QR Source:', qrSrc);
                console.log('QR Title:', qrTitle);

                // Set modal content
                const modalHeader = document.getElementById('qrModalHeader');
                const downloadBtn = document.getElementById('qrDownloadBtn');

                // Set the modal header and download button color based on payment method
                if (qrTitle && qrTitle.includes('GCash')) {
                    // Primary color for GCash
                    modalHeader.style.background = '#4e73df';
                    downloadBtn.style.background = '#4e73df';
                    document.getElementById('qrPaymentAmount').className = 'alert alert-primary rounded-3 border-0 mb-3';
                } else if (qrTitle && qrTitle.includes('PayMaya')) {
                    // Green color for PayMaya
                    modalHeader.style.background = '#1c9e77';
                    downloadBtn.style.background = '#1c9e77';
                    document.getElementById('qrPaymentAmount').style.background = '#e8f5f0';
                    document.getElementById('qrPaymentAmount').style.color = '#1c9e77';
                } else if (qrTitle && qrTitle.includes('Bank')) {
                    // BDO blue for Bank
                    modalHeader.style.background = '#204399';
                    downloadBtn.style.background = '#204399';
                    document.getElementById('qrPaymentAmount').style.background = '#e9ecf6';
                    document.getElementById('qrPaymentAmount').style.color = '#204399';
                }

                // Get the selected billing cycle and update the payment amount
                const selectedBillingCycle = document.querySelector('input[name="billing_cycle"]:checked');
                const amountText = document.getElementById('qrAmountText');

                if (selectedBillingCycle) {
                    if (selectedBillingCycle.value === 'monthly') {
                        // Get the monthly price from the DOM
                        const monthlyPrice = document.querySelector('#monthly').closest('.billing-option').querySelector('.amount').textContent.trim();
                        amountText.textContent = '₱' + monthlyPrice;
                    } else if (selectedBillingCycle.value === 'yearly') {
                        // Get the yearly price from the DOM
                        const yearlyPrice = document.querySelector('#yearly').closest('.billing-option').querySelector('.amount').textContent.trim();
                        amountText.textContent = '₱' + yearlyPrice;
                    }
                }

                modalTitle.innerHTML = '<i class="fas fa-qrcode me-2"></i>' + (qrTitle || 'QR Code');
                if (qrSrc) {
                    modalImage.src = qrSrc;
                    currentQrSrc = qrSrc;
                } else {
                    console.error('No QR source found');
                }

                // Reset zoom when opening modal
                resetZoom();
            });

            // Add keyboard shortcuts for zooming
            qrModal.addEventListener('keydown', function(e) {
                if (e.key === '+' || e.key === '=') {
                    zoomIn();
                    e.preventDefault();
                } else if (e.key === '-' || e.key === '_') {
                    zoomOut();
                    e.preventDefault();
                } else if (e.key === '0') {
                    resetZoom();
                    e.preventDefault();
                }
            });

            // Add mouse wheel zoom support
            modalImage.addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY < 0) {
                    zoomIn();
                } else {
                    zoomOut();
                }
            });

            // Initialize Bootstrap modal
            const bsModal = new bootstrap.Modal(qrModal);

            // Add click handlers to all QR code images
            document.querySelectorAll('.qr-code-img').forEach(img => {
                img.addEventListener('click', function() {
                    const qrSrc = this.getAttribute('data-qr-src');
                    const qrTitle = this.getAttribute('data-qr-title');

                    console.log('QR image clicked manually');
                    console.log('QR Source:', qrSrc);
                    console.log('QR Title:', qrTitle);

                    // Set modal content
                    const modalTitle = qrModal.querySelector('.modal-title');
                    const modalImage = qrModal.querySelector('.qr-zoom-image');
                    const modalHeader = document.getElementById('qrModalHeader');
                    const downloadBtn = document.getElementById('qrDownloadBtn');

                    // Set the modal header and download button color based on payment method
                    if (qrTitle.includes('GCash')) {
                        // Primary color for GCash
                        modalHeader.style.background = '#4e73df';
                        downloadBtn.style.background = '#4e73df';
                        document.getElementById('qrPaymentAmount').className = 'alert alert-primary rounded-3 border-0 mb-3';
                    } else if (qrTitle.includes('PayMaya')) {
                        // Green color for PayMaya
                        modalHeader.style.background = '#1c9e77';
                        downloadBtn.style.background = '#1c9e77';
                        document.getElementById('qrPaymentAmount').style.background = '#e8f5f0';
                        document.getElementById('qrPaymentAmount').style.color = '#1c9e77';
                    } else if (qrTitle.includes('Bank')) {
                        // BDO blue for Bank
                        modalHeader.style.background = '#204399';
                        downloadBtn.style.background = '#204399';
                        document.getElementById('qrPaymentAmount').style.background = '#e9ecf6';
                        document.getElementById('qrPaymentAmount').style.color = '#204399';
                    }

                    // Get the selected billing cycle and update the payment amount
                    const selectedBillingCycle = document.querySelector('input[name="billing_cycle"]:checked');
                    const amountText = document.getElementById('qrAmountText');

                    if (selectedBillingCycle) {
                        if (selectedBillingCycle.value === 'monthly') {
                            // Get the monthly price from the DOM
                            const monthlyPrice = document.querySelector('#monthly').closest('.billing-option').querySelector('.amount').textContent.trim();
                            amountText.textContent = '₱' + monthlyPrice;
                        } else if (selectedBillingCycle.value === 'yearly') {
                            // Get the yearly price from the DOM
                            const yearlyPrice = document.querySelector('#yearly').closest('.billing-option').querySelector('.amount').textContent.trim();
                            amountText.textContent = '₱' + yearlyPrice;
                        }
                    }

                    modalTitle.innerHTML = '<i class="fas fa-qrcode me-2"></i>' + (qrTitle || 'QR Code');
                    modalImage.src = qrSrc;
                    currentQrSrc = qrSrc;

                    // Reset zoom
                    resetZoom();

                    // Show modal
                    bsModal.show();
                });
            });
        }
    });
</script>
@endpush

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="modal-header text-white border-0" id="qrModalHeader">
                <h5 class="modal-title fw-bold" id="qrModalLabel"><i class="fas fa-qrcode me-2"></i>QR Code</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-8 p-4 text-center d-flex align-items-center justify-content-center bg-light">
                        <div class="qr-zoom-container position-relative">
                            <div class="bg-white p-4 rounded-3 shadow-sm d-inline-block">
                                <img src="" alt="QR Code" class="img-fluid qr-zoom-image" style="max-height: 60vh; display: inline-block;">
                            </div>
                            <div class="zoom-controls position-absolute bottom-0 start-50 translate-middle-x mb-3 bg-white rounded-pill shadow px-3 py-2">
                                <button type="button" class="btn btn-sm btn-outline-primary zoom-in-btn" title="Zoom In">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary zoom-reset-btn mx-2" title="Reset Zoom">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary zoom-out-btn" title="Zoom Out">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 p-4 border-start">
                        <h6 class="fw-bold mb-3">QR Code Instructions</h6>
                        <div class="alert alert-primary rounded-3 border-0 mb-3" id="qrPaymentAmount">
                            <div class="d-flex align-items-center">
                                <div class="me-3 fs-4"><i class="fas fa-money-bill-wave"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1">Payment Amount</h6>
                                    <p class="mb-0 fs-5 fw-bold" id="qrAmountText">₱0.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <p class="mb-3">Scan this QR code using your payment app to quickly make a payment.</p>
                            <ol class="ps-3 mb-0">
                                <li class="mb-2">Open your payment app</li>
                                <li class="mb-2">Select the scan QR code option</li>
                                <li class="mb-2">Point your camera at this QR code</li>
                                <li>Complete the payment in your app</li>
                            </ol>
                        </div>
                        <div class="alert alert-warning rounded-3 border-0 mb-4">
                            <div class="d-flex">
                                <div class="me-3 fs-4"><i class="fas fa-exclamation-triangle"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1">Important</h6>
                                    <p class="mb-0 small">After making the payment, don't forget to enter the reference number below to complete your subscription.</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn rounded-pill download-qr-btn text-white" id="qrDownloadBtn">
                                <i class="fas fa-download me-2"></i> Download QR Code
                            </a>
                            <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
