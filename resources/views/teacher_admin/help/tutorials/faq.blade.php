@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Frequently Asked Questions</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Teacher Admin Module FAQs</h4>
                            <p class="text-muted">
                                Find answers to common questions about using the Teacher Admin Module.
                            </p>
                        </div>
                    </div>

                    <div class="accordion" id="faqAccordion">
                        <!-- General Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">General Questions</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        How many Teacher Admins can a school have?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>A school can have multiple Teacher Admins, but the number is typically limited based on your subscription plan. Having multiple Teacher Admins allows for better distribution of administrative responsibilities.</p>
                                        <p>To add a new Teacher Admin:</p>
                                        <ol>
                                            <li>Contact the System Administrator through the Support feature</li>
                                            <li>Request a Teacher Admin registration key</li>
                                            <li>Once approved, you'll receive a registration key that can be used to register a new Teacher Admin account</li>
                                        </ol>
                                        <p>Note that all Teacher Admins have the same level of access to administrative features.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        How do I update our school information?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To update your school information:</p>
                                        <ol>
                                            <li>Go to the "School" page in the Teacher Admin panel</li>
                                            <li>Click the "Edit School Info" button</li>
                                            <li>Update the necessary fields (school name, address, contact information, etc.)</li>
                                            <li>To update the school logo, click "Choose File" and select a new image (55kb-2mb size)</li>
                                            <li>Click "Save Changes" to update the information</li>
                                        </ol>
                                        <p>The updated information will be reflected throughout the system, including on reports, grade slips, and certificates.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subscription Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Subscription and Payments</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        What happens when our subscription expires?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>When your subscription expires:</p>
                                        <ul>
                                            <li>Access to most system features will be restricted</li>
                                            <li>Teachers and students will not be able to log in</li>
                                            <li>Teacher Admins will only have access to the payment page</li>
                                            <li>All your data is preserved and will be accessible again once the subscription is renewed</li>
                                        </ul>
                                        <p>To avoid service interruption, it's recommended to renew your subscription at least 3 days before the expiration date. You'll receive email reminders as the expiration date approaches.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        How long does it take for a payment to be approved?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Payment approval times vary depending on the payment method:</p>
                                        <ul>
                                            <li><strong>GCash and PayMaya payments:</strong> Typically approved within 1-24 hours</li>
                                            <li><strong>Bank transfers:</strong> Usually take 1-2 business days to be approved</li>
                                        </ul>
                                        <p>After submitting a payment, you can check its status on the Payments page. If a payment remains pending for longer than the expected time, please contact support for assistance.</p>
                                        <p>Once approved, your subscription will be automatically extended, and you'll receive a confirmation email with the new expiration date.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teacher Management Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Teacher Management</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        How do I add new teachers to our school?
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To add new teachers to your school:</p>
                                        <ol>
                                            <li>Go to the "School" page in the Teacher Admin panel</li>
                                            <li>Click the "Generate Registration Key" button</li>
                                            <li>Select "Teacher" as the key type</li>
                                            <li>Choose how many keys to generate (for bulk generation)</li>
                                            <li>Click "Generate Keys"</li>
                                            <li>Share the generated registration key(s) with the new teacher(s)</li>
                                            <li>The teacher will use this key during the registration process</li>
                                        </ol>
                                        <p>Registration keys are one-time use only. Once a teacher registers with a key, it cannot be used again.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        Can I disable a teacher account?
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Yes, you can disable a teacher account if a teacher is no longer with your school or needs to be temporarily deactivated:</p>
                                        <ol>
                                            <li>Go to the "School" page in the Teacher Admin panel</li>
                                            <li>Click on the "Teachers" tab</li>
                                            <li>Find the teacher in the list</li>
                                            <li>Click the "Disable" button in the Actions column</li>
                                            <li>Confirm the action when prompted</li>
                                        </ol>
                                        <p>When a teacher account is disabled:</p>
                                        <ul>
                                            <li>The teacher can no longer log in to the system</li>
                                            <li>Their assignments (sections, subjects) remain in the system but are marked as unassigned</li>
                                            <li>Historical data (grades, attendance records) is preserved</li>
                                            <li>You can reactivate the account at any time if needed</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Support Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Support</h5>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        How do I contact system administrators for help?
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To contact system administrators for help:</p>
                                        <ol>
                                            <li>Click on "Support" in the sidebar menu</li>
                                            <li>Click "New Support Ticket"</li>
                                            <li>Select a category for your issue (Technical Issue, Billing Question, Feature Request, etc.)</li>
                                            <li>Enter a clear subject line</li>
                                            <li>Describe your issue in detail in the message box</li>
                                            <li>Attach screenshots if relevant (optional)</li>
                                            <li>Click "Submit Ticket"</li>
                                        </ol>
                                        <p>You'll receive a notification when an administrator responds to your ticket. You can view all your support tickets and their status on the Support page.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingEight">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                        How do I request a Teacher Admin registration key?
                                    </button>
                                </h2>
                                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To request a Teacher Admin registration key:</p>
                                        <ol>
                                            <li>Go to the Support page</li>
                                            <li>Create a new support ticket</li>
                                            <li>Select "Registration Key Request" as the category</li>
                                            <li>In your message, include:
                                                <ul>
                                                    <li>The reason for needing an additional Teacher Admin</li>
                                                    <li>The name and email of the person who will use the key</li>
                                                    <li>Your school name and ID</li>
                                                </ul>
                                            </li>
                                            <li>Submit the ticket</li>
                                        </ol>
                                        <p>System administrators will review your request and, if approved, will provide you with a Teacher Admin registration key. This key can then be used during the registration process to create a new Teacher Admin account for your school.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.tutorial', 'support') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Support
                        </a>
                        <a href="{{ route('teacher-admin.help.index') }}" class="tutorial-nav-btn next">
                            Back to Help Center <i class="fas fa-arrow-right"></i>
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

    .accordion-button:not(.collapsed) {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>

<!-- Include the tutorial navigation CSS -->
<link rel="stylesheet" href="{{ asset('css/tutorial-nav.css') }}">
@endsection
