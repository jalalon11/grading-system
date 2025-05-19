@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Teacher Admin Reports Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing School-Wide Reports</h4>
                            <p class="text-muted">
                                The Teacher Admin Reports module allows you to generate and manage school-wide reports, including consolidated grades and administrative reports.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Reports Overview
                        </h5>
                        <div class="tutorial-content">
                            <p>The Reports page provides access to various school-wide reports:</p>
                            <ul>
                                <li>Consolidated Grade Reports</li>
                                <li>School Performance Reports</li>
                                <li>Attendance Summary Reports</li>
                                <li>Teacher Performance Reports</li>
                                <li>Grade Level Analysis</li>
                            </ul>
                            <p>These reports provide a comprehensive view of your school's academic performance and can be used for administrative decision-making and reporting to stakeholders.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-file-alt text-success me-2"></i>
                            Consolidated Grade Reports
                        </h5>
                        <div class="tutorial-content">
                            <p>Consolidated Grade Reports combine grades from all subjects for a section:</p>
                            <ol>
                                <li>Go to "Reports" > "Consolidated Grades"</li>
                                <li>Select the parameters:
                                    <ul>
                                        <li>Section (select from dropdown)</li>
                                        <li>Quarter (Q1, Q2, Q3, Q4, or All)</li>
                                        <li>Include Attendance (Yes/No)</li>
                                    </ul>
                                </li>
                                <li>Click "Generate Report"</li>
                            </ol>
                            <p>The Consolidated Grade Report includes:</p>
                            <ul>
                                <li>All students in the selected section</li>
                                <li>Grades for all subjects</li>
                                <li>General Average calculation</li>
                                <li>Attendance summary (if selected)</li>
                                <li>Teacher signatures and approval status</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Only grades that have been approved by subject teachers will appear in the consolidated report. Check with teachers if any subjects are missing.</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-clipboard-check text-danger me-2"></i>
                            Attendance Summary Reports
                        </h5>
                        <div class="tutorial-content">
                            <p>Attendance Summary Reports provide school-wide attendance analytics:</p>
                            <ol>
                                <li>Go to "Reports" > "Attendance Summary"</li>
                                <li>Select the parameters:
                                    <ul>
                                        <li>Date Range</li>
                                        <li>Grade Level or Section (optional)</li>
                                    </ul>
                                </li>
                                <li>Click "Generate Report"</li>
                            </ol>
                            <p>The Attendance Summary Report includes:</p>
                            <ul>
                                <li>Overall attendance rate for the school</li>
                                <li>Breakdown by grade level and section</li>
                                <li>Identification of days with unusually low attendance</li>
                                <li>List of students with attendance concerns</li>
                                <li>Trend analysis showing attendance patterns over time</li>
                            </ul>
                            <p>This report helps identify attendance issues that may require intervention at the school level.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-print text-primary me-2"></i>
                            Printing and Exporting Reports
                        </h5>
                        <div class="tutorial-content">
                            <p>All reports can be printed or exported for record-keeping and sharing:</p>
                            <ol>
                                <li>Generate the desired report</li>
                                <li>Click the "Print" button at the top of the report</li>
                                <li>In the browser print dialog:
                                    <ul>
                                        <li>Select your printer or choose "Save as PDF"</li>
                                        <li>Adjust paper size and orientation as needed</li>
                                        <li>Set margins to "Minimal" for best results</li>
                                    </ul>
                                </li>
                                <li>Click "Print" or "Save"</li>
                            </ol>
                            <p>For data exports:</p>
                            <ul>
                                <li>Some reports offer an "Export to Excel" option</li>
                                <li>Click this button to download the data in spreadsheet format</li>
                                <li>Exported data can be used for further analysis or integration with other systems</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>When printing reports for official purposes, use high-quality paper and check that all information is clearly visible in the printout.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with reports:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Use the Support feature to contact system administrators</li>
                                <li>Refer to the documentation provided during your onboarding</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.tutorial', 'subjects') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Subjects Management
                        </a>
                        <a href="{{ route('teacher-admin.help.tutorial', 'payments') }}" class="tutorial-nav-btn next">
                            Next: Payments <i class="fas fa-arrow-right"></i>
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
