@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payments Management Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing School Subscription Payments</h4>
                            <p class="text-muted">
                                The Payments module allows you to manage your school's subscription payments, view payment history, and handle billing information.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Understanding Subscription Plans
                        </h5>
                        <div class="tutorial-content">
                            <p>The system offers different subscription plans based on your school's grade levels:</p>
                            <ul>
                                <li><strong>K-6 Plan:</strong> For elementary schools (Kindergarten to Grade 6)
                                    <ul>
                                        <li>Monthly: ₱2,500</li>
                                        <li>Annual: ₱22,000 (20% discount)</li>
                                    </ul>
                                </li>
                                <li><strong>7-12 Plan:</strong> For high schools (Grades 7 to 12)
                                    <ul>
                                        <li>Monthly: ₱2,500</li>
                                        <li>Annual: ₱22,000 (20% discount)</li>
                                    </ul>
                                </li>
                                <li><strong>K-12 Plan:</strong> For complete K-12 schools
                                    <ul>
                                        <li>Monthly: ₱5,700</li>
                                        <li>Annual: ₱51,300 (20% discount)</li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Annual subscriptions offer a 20% discount compared to monthly payments, providing significant savings for your school.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-credit-card text-success me-2"></i>
                            Making a Payment
                        </h5>
                        <div class="tutorial-content">
                            <p>To make a subscription payment:</p>
                            <ol>
                                <li>Go to "Payments" in the Teacher Admin Panel</li>
                                <li>Click the "Make Payment" button</li>
                                <li>Select your subscription plan:
                                    <ul>
                                        <li>Choose between Monthly or Annual billing</li>
                                        <li>Confirm the subscription type (K-6, 7-12, or K-12)</li>
                                    </ul>
                                </li>
                                <li>Select your payment method:
                                    <ul>
                                        <li>GCash</li>
                                        <li>PayMaya</li>
                                        <li>BDO Bank Transfer</li>
                                    </ul>
                                </li>
                                <li>Follow the payment instructions for your chosen method:
                                    <ul>
                                        <li>For GCash and PayMaya: Scan the QR code or use the provided account number</li>
                                        <li>For BDO: Use the provided account details for bank transfer</li>
                                    </ul>
                                </li>
                                <li>Enter the payment details:
                                    <ul>
                                        <li>Reference Number (from your payment receipt)</li>
                                        <li>Amount Paid</li>
                                        <li>Payment Date</li>
                                        <li>Upload proof of payment (screenshot or photo)</li>
                                    </ul>
                                </li>
                                <li>Click "Submit Payment" to complete the process</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Always keep your payment receipt and reference number until the payment is confirmed by the system administrators.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-history text-info me-2"></i>
                            Viewing Payment History
                        </h5>
                        <div class="tutorial-content">
                            <p>To view your school's payment history:</p>
                            <ol>
                                <li>Go to "Payments" in the Teacher Admin Panel</li>
                                <li>Scroll down to the "Payment History" section</li>
                            </ol>
                            <p>The Payment History table shows:</p>
                            <ul>
                                <li>Payment Date</li>
                                <li>Reference Number</li>
                                <li>Amount</li>
                                <li>Payment Method</li>
                                <li>Status (Pending, Approved, or Rejected)</li>
                                <li>Subscription Period (the dates covered by the payment)</li>
                            </ul>
                            <p>Click on any payment to view its details, including:</p>
                            <ul>
                                <li>Full payment information</li>
                                <li>Proof of payment image</li>
                                <li>Admin notes (for approved or rejected payments)</li>
                                <li>Option to download payment receipt (for approved payments)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-check-circle text-warning me-2"></i>
                            Payment Approval Process
                        </h5>
                        <div class="tutorial-content">
                            <p>After submitting a payment, it goes through an approval process:</p>
                            <ol>
                                <li>Payment Status: "Pending" - Your payment has been submitted and is awaiting review</li>
                                <li>System administrators verify the payment details and proof of payment</li>
                                <li>Payment Status Updated:
                                    <ul>
                                        <li>"Approved" - Payment has been verified and subscription has been extended</li>
                                        <li>"Rejected" - Payment could not be verified (with explanation in admin notes)</li>
                                    </ul>
                                </li>
                                <li>You will receive a notification when your payment status changes</li>
                            </ol>
                            <p>Typical approval times:</p>
                            <ul>
                                <li>GCash and PayMaya payments: 1-24 hours</li>
                                <li>Bank transfers: 1-2 business days</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>If your payment is rejected, check the admin notes for the reason and submit a new payment with the correct information.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-receipt text-danger me-2"></i>
                            Downloading Payment Receipts
                        </h5>
                        <div class="tutorial-content">
                            <p>To download an official receipt for an approved payment:</p>
                            <ol>
                                <li>Go to "Payments" in the Teacher Admin Panel</li>
                                <li>Find the approved payment in the Payment History table</li>
                                <li>Click on the payment to view its details</li>
                                <li>Click the "Download Receipt" button</li>
                                <li>A PDF receipt will be generated for your records</li>
                            </ol>
                            <p>The official receipt includes:</p>
                            <ul>
                                <li>School information</li>
                                <li>Payment details</li>
                                <li>Subscription information</li>
                                <li>System-generated receipt number</li>
                                <li>Digital verification stamp</li>
                            </ul>
                            <p>This receipt can be used for your school's accounting and record-keeping purposes.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-exclamation-triangle text-primary me-2"></i>
                            Handling Subscription Expiration
                        </h5>
                        <div class="tutorial-content">
                            <p>When your subscription is nearing expiration:</p>
                            <ul>
                                <li>You will see a warning notification on your dashboard</li>
                                <li>The remaining days will be displayed with a countdown</li>
                                <li>Email reminders will be sent to all Teacher Admins</li>
                            </ul>
                            <p>If your subscription expires:</p>
                            <ul>
                                <li>School access will be limited to the payment page only</li>
                                <li>Teachers and students will not be able to access the system</li>
                                <li>All data is preserved and will be accessible again once the subscription is renewed</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>To avoid service interruption, make your renewal payment at least 3 days before the expiration date.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with payments:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Use the Support feature to contact system administrators</li>
                                <li>For urgent payment issues, contact the support team directly</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.tutorial', 'reports') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Reports
                        </a>
                        <a href="{{ route('teacher-admin.help.tutorial', 'registration_keys') }}" class="tutorial-nav-btn next">
                            Next: Registration Keys <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tutorial-section {
        border-left: 3px solid #e9ecef;
        padding-left: 20px;
    }

    .tutorial-heading {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .tutorial-content {
        color: #555;
    }

    .tutorial-tip {
        background-color: #fff8e1;
        border-left: 3px solid #ffc107;
        padding: 10px 15px;
        margin-top: 15px;
        border-radius: 4px;
        display: flex;
        align-items: center;
    }

    .tutorial-tip i {
        margin-right: 10px;
        font-size: 18px;
    }
</style>

<!-- Include the tutorial navigation CSS -->
<link rel="stylesheet" href="{{ asset('css/tutorial-nav.css') }}">
@endsection
