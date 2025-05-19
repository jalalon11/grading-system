@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Registration Keys Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing Registration Keys</h4>
                            <p class="text-muted">
                                Registration keys are used to add new teachers to your school. This tutorial explains how to generate, manage, and distribute registration keys.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-key text-primary me-2"></i>
                            Understanding Registration Keys
                        </h5>
                        <div class="tutorial-content">
                            <p>Registration keys are unique codes that allow new teachers to register and join your school. There are two types of registration keys:</p>
                            <ul>
                                <li><strong>Teacher Keys:</strong> Used to register regular teacher accounts. These can be generated directly by Teacher Admins.</li>
                                <li><strong>Teacher Admin Keys:</strong> Used to register Teacher Admin accounts. These must be requested from system administrators.</li>
                            </ul>
                            <p>Key features of registration keys:</p>
                            <ul>
                                <li>Each key is unique and can only be used once</li>
                                <li>Keys are specific to your school and cannot be used for other schools</li>
                                <li>Keys do not expire, but they can be revoked if needed</li>
                                <li>Keys are automatically marked as "Used" once a teacher completes registration</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Generating Teacher Registration Keys
                        </h5>
                        <div class="tutorial-content">
                            <p>To generate registration keys for new teachers:</p>
                            <ol>
                                <li>Go to the "School" page in the Teacher Admin panel</li>
                                <li>Click the "Generate Registration Key" button</li>
                                <li>In the modal that appears:
                                    <ul>
                                        <li>Select "Teacher" as the key type</li>
                                        <li>Choose how many keys to generate (1-10)</li>
                                    </ul>
                                </li>
                                <li>Click "Generate Keys" to create the registration keys</li>
                            </ol>
                            <p>The newly generated keys will appear in the Registration Keys table, where you can copy them to share with new teachers.</p>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Generate keys only when you're ready to distribute them to new teachers. This helps you keep track of which keys have been assigned to which teachers.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-user-shield text-info me-2"></i>
                            Requesting Teacher Admin Keys
                        </h5>
                        <div class="tutorial-content">
                            <p>Teacher Admin registration keys must be requested from system administrators:</p>
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
                            <p>System administrators will review your request and, if approved, will provide you with a Teacher Admin registration key through the support ticket conversation.</p>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>The number of Teacher Admin accounts allowed depends on your subscription plan. Contact support if you need to increase this limit.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-table text-warning me-2"></i>
                            Managing Registration Keys
                        </h5>
                        <div class="tutorial-content">
                            <p>The Registration Keys table shows all keys that have been generated for your school:</p>
                            <ul>
                                <li><strong>Key:</strong> The unique registration code</li>
                                <li><strong>Type:</strong> Teacher or Teacher Admin</li>
                                <li><strong>Status:</strong> Available or Used</li>
                                <li><strong>Generated By:</strong> The admin who generated the key</li>
                                <li><strong>Generated Date:</strong> When the key was created</li>
                                <li><strong>Used By:</strong> The teacher who used the key (if applicable)</li>
                                <li><strong>Used Date:</strong> When the key was used (if applicable)</li>
                            </ul>
                            <p>You can manage your registration keys in several ways:</p>
                            <ul>
                                <li><strong>Copy Key:</strong> Click the copy icon next to a key to copy it to your clipboard</li>
                                <li><strong>Bulk Copy:</strong> Select multiple keys and click "Copy Selected" to copy all selected keys</li>
                                <li><strong>Filter Keys:</strong> Use the filter options to show only Available or Used keys</li>
                                <li><strong>Search:</strong> Use the search box to find specific keys</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-share-alt text-danger me-2"></i>
                            Distributing Registration Keys
                        </h5>
                        <div class="tutorial-content">
                            <p>Once you've generated registration keys, you need to distribute them to new teachers:</p>
                            <ol>
                                <li>Copy the registration key from the Registration Keys table</li>
                                <li>Share the key with the new teacher via email, messaging app, or in person</li>
                                <li>Provide the teacher with the registration URL: <code>https://yourschool.domain.com/register</code></li>
                                <li>Instruct the teacher to:
                                    <ul>
                                        <li>Go to the registration page</li>
                                        <li>Fill in their personal information</li>
                                        <li>Enter the registration key when prompted</li>
                                        <li>Complete the registration process</li>
                                    </ul>
                                </li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>For security reasons, it's best to distribute each key individually to the specific teacher who will use it. Avoid sharing keys in group messages or public forums.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Troubleshooting Registration Issues
                        </h5>
                        <div class="tutorial-content">
                            <p>If teachers encounter issues when using registration keys:</p>
                            <ul>
                                <li><strong>"Invalid Key" Error:</strong> Verify that the key is being entered exactly as provided, with no extra spaces or characters</li>
                                <li><strong>"Key Already Used" Error:</strong> Check the Registration Keys table to confirm if the key has already been used</li>
                                <li><strong>"School Not Found" Error:</strong> Ensure the teacher is using the correct registration URL for your school</li>
                                <li><strong>"Registration Closed" Error:</strong> Check if your school's subscription is active</li>
                            </ul>
                            <p>If problems persist, create a support ticket with the specific error message and the registration key being used.</p>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.tutorial', 'payments') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Payments
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
</style>

<!-- Include the tutorial navigation CSS -->
<link rel="stylesheet" href="{{ asset('css/tutorial-nav.css') }}">
@endsection
