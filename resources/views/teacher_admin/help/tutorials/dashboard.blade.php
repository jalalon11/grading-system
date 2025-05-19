@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Admin Dashboard Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Teacher Admin Dashboard Overview</h4>
                            <p class="text-muted">
                                The Teacher Admin Dashboard provides a comprehensive overview of your school's academic operations and subscription status.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-school text-primary me-2"></i>
                            School Information
                        </h5>
                        <div class="tutorial-content">
                            <p>The top section displays important information about your school:</p>
                            <ul>
                                <li><strong>School Name and Logo:</strong> Your school's identification</li>
                                <li><strong>Subscription Status:</strong> Current status of your school's subscription</li>
                                <li><strong>Subscription Expiry:</strong> When your current subscription will expire</li>
                                <li><strong>Days Remaining:</strong> Number of days left in your current subscription period</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>If your subscription is expiring soon, a warning notification will appear with a direct link to the payment page.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chart-bar text-success me-2"></i>
                            School Statistics
                        </h5>
                        <div class="tutorial-content">
                            <p>The statistics section provides a quick overview of your school's numbers:</p>
                            <ul>
                                <li><strong>Total Teachers:</strong> Number of teachers registered in your school</li>
                                <li><strong>Total Sections:</strong> Number of active sections across all grade levels</li>
                                <li><strong>Total Students:</strong> Total student population in your school</li>
                                <li><strong>Total Subjects:</strong> Number of subjects being taught</li>
                            </ul>
                            <p>Each statistic card is clickable and will take you to the relevant management page.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-tasks text-info me-2"></i>
                            Quick Actions
                        </h5>
                        <div class="tutorial-content">
                            <p>The Quick Actions panel provides shortcuts to common administrative tasks:</p>
                            <ul>
                                <li><strong>Add New Section:</strong> Create a new section for your school</li>
                                <li><strong>Add New Subject:</strong> Create a new subject for your curriculum</li>
                                <li><strong>Make Payment:</strong> Process a subscription payment</li>
                                <li><strong>Contact Support:</strong> Get help from system administrators</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Use these quick action buttons to save time navigating through the menu.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-bell text-danger me-2"></i>
                            Notifications and Alerts
                        </h5>
                        <div class="tutorial-content">
                            <p>The dashboard displays important notifications:</p>
                            <ul>
                                <li><strong>Payment Reminders:</strong> Alerts about upcoming or overdue payments</li>
                                <li><strong>System Announcements:</strong> Important updates from system administrators</li>
                                <li><strong>Support Tickets:</strong> Updates on your open support requests</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with the Teacher Admin Dashboard:</p>
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
                        <a href="{{ route('teacher-admin.help.tutorial', 'school') }}" class="tutorial-nav-btn next">
                            Next: School Management <i class="fas fa-arrow-right"></i>
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
