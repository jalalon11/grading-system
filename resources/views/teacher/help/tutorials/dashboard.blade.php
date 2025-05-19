@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dashboard Tutorial</h5>
                    <a href="{{ route('teacher.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Teacher Dashboard Overview</h4>
                            <p class="text-muted">
                                The dashboard is your central hub for accessing all features and viewing important information at a glance.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Dashboard Statistics
                        </h5>
                        <div class="tutorial-content">
                            <p>The top section of your dashboard displays key statistics:</p>
                            <ul>
                                <li><strong>Total Students:</strong> The number of students you are teaching across all sections</li>
                                <li><strong>Active Sections:</strong> The number of sections you are currently assigned to</li>
                                <li><strong>Subjects Taught:</strong> The number of subjects you are currently teaching</li>
                                <li><strong>Attendance Rate:</strong> The average attendance rate across all your sections</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Click on any statistic card to navigate directly to the related section.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-users text-success me-2"></i>
                            Recent Sections
                        </h5>
                        <div class="tutorial-content">
                            <p>The Recent Sections panel shows your most recently accessed sections:</p>
                            <ul>
                                <li>View section details including grade level and number of students</li>
                                <li>Click on a section to quickly access its student list</li>
                                <li>Use the "View All" button to see all your assigned sections</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chart-pie text-info me-2"></i>
                            Performance Charts
                        </h5>
                        <div class="tutorial-content">
                            <p>The dashboard includes several charts to help you visualize important data:</p>
                            <ul>
                                <li><strong>Attendance Trends:</strong> Shows attendance patterns over time</li>
                                <li><strong>Grade Distribution:</strong> Displays the distribution of grades across your classes</li>
                                <li><strong>Top Performing Students:</strong> Highlights students with the highest grades</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Hover over chart elements to see detailed information.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-danger me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with the dashboard:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Contact your school's Teacher Admin</li>
                                <li>Use the Support feature to submit a help request</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher.help.index') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Back to Help Center
                        </a>
                        <a href="{{ route('teacher.help.tutorial', 'students') }}" class="tutorial-nav-btn next">
                            Next: Students Tutorial <i class="fas fa-arrow-right"></i>
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
