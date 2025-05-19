@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reports Tutorial</h5>
                    <a href="{{ route('teacher.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Generating and Managing Reports</h4>
                            <p class="text-muted">
                                The Reports module allows you to generate various academic reports including class records, grade slips, and certificates.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            Class Record Reports
                        </h5>
                        <div class="tutorial-content">
                            <p>Class Record Reports provide a comprehensive view of student grades for a specific subject:</p>
                            <ol>
                                <li>Go to "Reports" > "Class Record"</li>
                                <li>Select the Section, Subject, and Quarter</li>
                                <li>Click "Generate Report"</li>
                            </ol>
                            <p>The Class Record Report includes:</p>
                            <ul>
                                <li>All students in the selected section</li>
                                <li>Scores for each assessment (Written Works, Performance Tasks, Quarterly Assessment)</li>
                                <li>Total scores and percentages for each component</li>
                                <li>Weighted scores and final grades</li>
                                <li>Class statistics (highest, lowest, and average grades)</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>You can edit scores directly from the Class Record Report by clicking on a score cell.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-file-invoice text-success me-2"></i>
                            Grade Slips
                        </h5>
                        <div class="tutorial-content">
                            <p>Grade Slips are official reports of student grades that can be distributed to students and parents:</p>
                            <ol>
                                <li>Go to "Reports" > "Grade Slips"</li>
                                <li>Select the Section and Quarter (or "All Quarters")</li>
                                <li>Choose the Transmutation Table to use</li>
                                <li>Click "Generate Grade Slips"</li>
                            </ol>
                            <p>Grade Slips include:</p>
                            <ul>
                                <li>Student information</li>
                                <li>Grades for all subjects</li>
                                <li>Attendance summary</li>
                                <li>Teacher and principal signature lines</li>
                                <li>School information and logo</li>
                            </ul>
                            <p><strong>Important notes about Grade Slips:</strong></p>
                            <ul>
                                <li>Only section advisers can generate grade slips for their advisory sections</li>
                                <li>All subject grades must be approved before they appear on grade slips</li>
                                <li>The "All Quarters" option generates a comprehensive report card</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>The print layout matches exactly what is shown in the preview, making it easy to prepare official documents.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-certificate text-info me-2"></i>
                            Certificates
                        </h5>
                        <div class="tutorial-content">
                            <p>You can generate various certificates for students:</p>
                            <ol>
                                <li>Go to "Reports" > "Certificates"</li>
                                <li>Select the Certificate Type:
                                    <ul>
                                        <li>Certificate of Recognition</li>
                                        <li>Certificate of Achievement</li>
                                        <li>Certificate of Participation</li>
                                        <li>Honor Roll Certificate</li>
                                    </ul>
                                </li>
                                <li>Select the Section and Student(s)</li>
                                <li>Enter the certificate details (achievement, event, date, etc.)</li>
                                <li>Choose a template style</li>
                                <li>Click "Generate Certificate"</li>
                            </ol>
                            <p>For bulk certificate generation:</p>
                            <ol>
                                <li>Select multiple students</li>
                                <li>Enter the common certificate details</li>
                                <li>Click "Generate Bulk Certificates"</li>
                                <li>Use your browser's print function to print all certificates at once</li>
                            </ol>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chart-bar text-warning me-2"></i>
                            Performance Analysis Reports
                        </h5>
                        <div class="tutorial-content">
                            <p>Performance Analysis Reports help you understand student achievement patterns:</p>
                            <ol>
                                <li>Go to "Reports" > "Performance Analysis"</li>
                                <li>Select the Section, Subject, and Quarter</li>
                                <li>Choose the analysis type:
                                    <ul>
                                        <li>Grade Distribution</li>
                                        <li>Component Analysis</li>
                                        <li>Student Ranking</li>
                                        <li>Comparative Analysis</li>
                                    </ul>
                                </li>
                                <li>Click "Generate Analysis"</li>
                            </ol>
                            <p>These reports include visual charts and graphs to help you identify:</p>
                            <ul>
                                <li>Students who may need additional support</li>
                                <li>Areas where the class is excelling or struggling</li>
                                <li>Performance trends across different assessment types</li>
                                <li>Comparison of current performance with previous quarters</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-print text-danger me-2"></i>
                            Printing Reports
                        </h5>
                        <div class="tutorial-content">
                            <p>To print any report:</p>
                            <ol>
                                <li>Generate the report you need</li>
                                <li>Click the "Print" button at the top of the report</li>
                                <li>In the browser print dialog, adjust settings as needed:
                                    <ul>
                                        <li>Paper size (usually A4)</li>
                                        <li>Orientation (portrait or landscape)</li>
                                        <li>Margins (minimal margins recommended)</li>
                                        <li>Headers and footers (usually turned off)</li>
                                    </ul>
                                </li>
                                <li>Click "Print" or "Save as PDF"</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>For best results, use Chrome or Edge browsers when printing reports. The "Save as PDF" option is useful for digital record-keeping.</span>
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
                                <li>Contact your school's Teacher Admin</li>
                                <li>Use the Support feature to submit a help request</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher.help.tutorial', 'attendance') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Attendance
                        </a>
                        <a href="{{ route('teacher.help.tutorial', 'resources') }}" class="tutorial-nav-btn next">
                            Next: Learning Resource Materials <i class="fas fa-arrow-right"></i>
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
