@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attendance Management Tutorial</h5>
                    <a href="{{ route('teacher.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing Student Attendance</h4>
                            <p class="text-muted">
                                The Attendance module allows you to record and track daily student attendance, generate reports, and monitor attendance trends.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-clipboard-check text-primary me-2"></i>
                            Recording Daily Attendance
                        </h5>
                        <div class="tutorial-content">
                            <p>To record attendance for a class:</p>
                            <ol>
                                <li>Go to the "Attendance" section from the sidebar</li>
                                <li>Click on "Record Attendance"</li>
                                <li>Select the Section and Date</li>
                                <li>For each student, mark their attendance status:
                                    <ul>
                                        <li><strong>Present:</strong> Student attended the class</li>
                                        <li><strong>Absent:</strong> Student did not attend the class</li>
                                        <li><strong>Late:</strong> Student arrived late to class</li>
                                        <li><strong>Excused:</strong> Student was absent with a valid excuse</li>
                                    </ul>
                                </li>
                                <li>Add optional remarks for individual students if needed</li>
                                <li>Click "Save Attendance" to record the data</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>You can only record attendance once per day for each section. If you need to make changes, use the edit function.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-edit text-success me-2"></i>
                            Editing Attendance Records
                        </h5>
                        <div class="tutorial-content">
                            <p>To edit previously recorded attendance:</p>
                            <ol>
                                <li>Go to the "Attendance" section</li>
                                <li>Click on "View Attendance Records"</li>
                                <li>Select the Section and Date range</li>
                                <li>Find the date you want to edit and click the "Edit" button</li>
                                <li>Make the necessary changes to attendance statuses</li>
                                <li>Click "Update Attendance" to save your changes</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>The system keeps a log of all attendance changes for accountability purposes.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-calendar-alt text-info me-2"></i>
                            Weekly Attendance Summary
                        </h5>
                        <div class="tutorial-content">
                            <p>To view a weekly summary of attendance:</p>
                            <ol>
                                <li>Go to "Attendance" > "Weekly Summary"</li>
                                <li>Select the Section and Week</li>
                                <li>View the attendance patterns for each student across the week</li>
                            </ol>
                            <p>The weekly summary provides:</p>
                            <ul>
                                <li>Day-by-day attendance status for each student</li>
                                <li>Total present, absent, late, and excused days for each student</li>
                                <li>Attendance percentage for each student</li>
                                <li>Class-wide attendance statistics</li>
                            </ul>
                            <p>You can print this summary by clicking the "Print" button at the top of the page.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chart-line text-warning me-2"></i>
                            Monthly Attendance Summary
                        </h5>
                        <div class="tutorial-content">
                            <p>For a broader view of attendance patterns:</p>
                            <ol>
                                <li>Go to "Attendance" > "Monthly Summary"</li>
                                <li>Select the Section and Month</li>
                                <li>View the comprehensive monthly attendance data</li>
                            </ol>
                            <p>The monthly summary shows:</p>
                            <ul>
                                <li>Calendar view with daily attendance status for each student</li>
                                <li>Monthly totals and percentages</li>
                                <li>Attendance trends throughout the month</li>
                                <li>Students with perfect attendance</li>
                                <li>Students with attendance concerns</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>The monthly summary is particularly useful for parent-teacher conferences and identifying students who may need attendance interventions.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chart-pie text-danger me-2"></i>
                            Attendance Analytics
                        </h5>
                        <div class="tutorial-content">
                            <p>The system provides attendance analytics to help you identify patterns:</p>
                            <ul>
                                <li><strong>Attendance Trends:</strong> View how attendance rates change over time</li>
                                <li><strong>Day of Week Analysis:</strong> See if certain days have higher absence rates</li>
                                <li><strong>Individual Student Patterns:</strong> Identify students with recurring attendance issues</li>
                            </ul>
                            <p>To access these analytics:</p>
                            <ol>
                                <li>Go to your Teacher Dashboard</li>
                                <li>Look for the "Attendance Trends" chart</li>
                                <li>Click "View Details" for more in-depth analysis</li>
                            </ol>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            Generating Attendance Reports
                        </h5>
                        <div class="tutorial-content">
                            <p>To generate comprehensive attendance reports:</p>
                            <ol>
                                <li>Go to "Reports" > "Attendance Reports"</li>
                                <li>Select the report type:
                                    <ul>
                                        <li>Daily Attendance Report</li>
                                        <li>Weekly Attendance Summary</li>
                                        <li>Monthly Attendance Summary</li>
                                        <li>Quarterly Attendance Report</li>
                                        <li>Individual Student Attendance</li>
                                    </ul>
                                </li>
                                <li>Set the parameters (section, date range, etc.)</li>
                                <li>Click "Generate Report"</li>
                                <li>Use the "Print" button to print the report or save as PDF</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Attendance reports are often required for school records and may be included in student grade slips.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with attendance management:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Contact your school's Teacher Admin</li>
                                <li>Use the Support feature to submit a help request</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher.help.tutorial', 'grades') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Grades Tutorial
                        </a>
                        <a href="{{ route('teacher.help.tutorial', 'reports') }}" class="tutorial-nav-btn next">
                            Next: Reports Tutorial <i class="fas fa-arrow-right"></i>
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
