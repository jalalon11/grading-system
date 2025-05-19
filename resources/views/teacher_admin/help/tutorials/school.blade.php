@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">School Management Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing Your School</h4>
                            <p class="text-muted">
                                The School Management module allows you to view and manage your school's information, teachers, sections, and subscription status.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-school text-primary me-2"></i>
                            School Overview
                        </h5>
                        <div class="tutorial-content">
                            <p>The School page provides a comprehensive overview of your school:</p>
                            <ol>
                                <li>Go to "School" in the Teacher Admin Panel</li>
                                <li>View the School Information card which displays:
                                    <ul>
                                        <li>School name and logo</li>
                                        <li>School division</li>
                                        <li>Address and contact information</li>
                                        <li>Subscription status and expiry date</li>
                                    </ul>
                                </li>
                            </ol>
                            <p>The page also shows key statistics:</p>
                            <ul>
                                <li>Total number of teachers</li>
                                <li>Total number of sections</li>
                                <li>Total number of students</li>
                                <li>Active and inactive counts for each category</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-edit text-success me-2"></i>
                            Updating School Information
                        </h5>
                        <div class="tutorial-content">
                            <p>To update your school's information:</p>
                            <ol>
                                <li>Click the "Edit School Info" button on the School page</li>
                                <li>Update the necessary fields:
                                    <ul>
                                        <li>School name</li>
                                        <li>Address</li>
                                        <li>Contact information</li>
                                        <li>School logo (55kb-2mb size, will be stored on Cloudflare R2)</li>
                                    </ul>
                                </li>
                                <li>Click "Save Changes" to update the information</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>The school logo appears on reports, grade slips, and certificates, so choose a clear, professional image.</span>
                            </div>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-info"></i>
                                <span>Updating the School Details will be only available once every 60 days.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chalkboard-teacher text-info me-2"></i>
                            Managing Teachers
                        </h5>
                        <div class="tutorial-content">
                            <p>The Teachers tab shows all teachers registered in your school:</p>
                            <ul>
                                <li>View teacher information including name, email, and status</li>
                                <li>See which sections and subjects each teacher is assigned to</li>
                                <li>Monitor teacher activity and last login dates</li>
                            </ul>
                            <p>As a Teacher Admin, you can:</p>
                            <ul>
                                <li>Generate registration keys for new teachers (see Registration Keys section)</li>
                                <li>Assign teachers to sections and subjects</li>
                                <li>View teacher workloads and assignments</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Regular teachers cannot access the Teacher Admin features unless they are specifically designated as Teacher Admins.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-key text-warning me-2"></i>
                            Registration Keys
                        </h5>
                        <div class="tutorial-content">
                            <p>To generate registration use the support ticket to request keys for new teachers</p>
                            <ol>
                                <li>Only System Administrators can generate Teacher & Teacher Admin registration keys.</li>
                                <li>Go to support and request for a Teacher & Teacher Admin registration key.</li>
                                <li>Wait for the System Administrator to approve your request.</li>
                                <li>Once approved, you will receive the keys via support ticket.</li>
                            </ol>
                            <p>Managing registration keys:</p>
                            <ul>
                                <li>View all generated keys in the Registration Keys table</li>
                                <li>See which keys have been used and which are still available</li>
                                <li>Copy keys to share with new teachers</li>
                                <li>Use the "Bulk Copy" feature to copy multiple keys at once</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Registration keys are one-time use only. Once a teacher registers with a key, it cannot be used again.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-users text-danger me-2"></i>
                            Student Overview
                        </h5>
                        <div class="tutorial-content">
                            <p>The Students tab provides a school-wide view of all students:</p>
                            <ul>
                                <li>Filter students by grade level or section</li>
                                <li>Search for specific students by name or ID</li>
                                <li>View student status (active or disabled)</li>
                                <li>See student distribution across sections</li>
                            </ul>
                            <p>The student list shows:</p>
                            <ul>
                                <li>Student name (sorted by surname)</li>
                                <li>Section assignment</li>
                                <li>Gender</li>
                                <li>Guardian information</li>
                                <li>Status indicators (active/disabled)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-credit-card text-primary me-2"></i>
                            Subscription Management
                        </h5>
                        <div class="tutorial-content">
                            <p>Monitor and manage your school's subscription:</p>
                            <ul>
                                <li>View current subscription status (Trial, Active, or Expired)</li>
                                <li>See subscription expiry date and days remaining</li>
                                <li>Check subscription type (K-6, 7-12, or K-12)</li>
                                <li>View payment history</li>
                            </ul>
                            <p>When your subscription is nearing expiration:</p>
                            <ol>
                                <li>A warning notification will appear on your dashboard</li>
                                <li>Click "Renew Subscription" to go to the payment page</li>
                                <li>Select your preferred payment method and subscription period</li>
                                <li>Complete the payment process</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Annual subscriptions offer a 20% discount compared to monthly payments. If your subscription expires, school access will be limited to the payment page only.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with school management:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Use the Support feature to contact system administrators</li>
                                <li>Refer to the documentation provided during your onboarding</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.index') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Back to Help Center
                        </a>
                        <a href="{{ route('teacher-admin.help.tutorial', 'sections') }}" class="tutorial-nav-btn next">
                            Next: Sections Management <i class="fas fa-arrow-right"></i>
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
