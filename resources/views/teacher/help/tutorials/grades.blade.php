@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Grades Management Tutorial</h5>
                    <a href="{{ route('teacher.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing Student Grades</h4>
                            <p class="text-muted">
                                The Grades module allows you to record, calculate, and manage student grades for different subjects and quarters.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-cog text-primary me-2"></i>
                            Understanding Grade Components
                        </h5>
                        <div class="tutorial-content">
                            <p>The grading system uses three main assessment types:</p>
                            <ul>
                                <li><strong>Written Works (WW):</strong> Quizzes, tests, and other written assessments (up to 7 per quarter)</li>
                                <li><strong>Performance Tasks (PT):</strong> Projects, presentations, and hands-on activities (up to 8 per quarter)</li>
                                <li><strong>Quarterly Assessment (QA):</strong> Final exam for the quarter (1 per quarter)</li>
                            </ul>
                            <p>Each assessment type has a specific weight in the final grade calculation:</p>
                            <ul>
                                <li>Written Works: 30%</li>
                                <li>Performance Tasks: 50%</li>
                                <li>Quarterly Assessment: 20%</li>
                            </ul>
                            <p><strong>Important:</strong> The grade components and their weights can vary depending on the subject. Some subjects may have different component weights based on their specific requirements. Always check the subject's configuration before entering grades.</p>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>For MAPEH subjects (Music, Arts, P.E., Health), each component is calculated separately and then averaged to get the final MAPEH grade.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-sliders-h text-success me-2"></i>
                            Assessment Setup
                        </h5>
                        <div class="tutorial-content">
                            <p>Before entering grades, you need to set up your assessments:</p>
                            <ol>
                                <li>Go to "Grades" > "Assessment Setup"</li>
                                <li>Select the Subject and Quarter</li>
                                <li>Configure each assessment type:
                                    <ul>
                                        <li>Add titles for each Written Work (e.g., "Quiz 1", "Midterm Exam")</li>
                                        <li>Add titles for each Performance Task (e.g., "Project 1", "Presentation")</li>
                                        <li>Set the maximum score for each assessment</li>
                                    </ul>
                                </li>
                                <li>Click "Save Assessment Setup" to confirm</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>You can modify your assessment setup at any time during the quarter, but it's best to set it up at the beginning.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-table text-warning me-2"></i>
                            Entering Grades
                        </h5>
                        <div class="tutorial-content">
                            <p>To enter grades for your students:</p>
                            <ol>
                                <li>Go to "Grades" > "Enter Grades"</li>
                                <li>Select the Section, Subject, Quarter, and Assessment</li>
                                <li>Enter scores for all students in the section at once</li>
                                <li>Click "Save All Grades" to record the entries</li>
                            </ol>
                            <p>The system will automatically calculate:</p>
                            <ul>
                                <li>Total scores for each assessment type</li>
                                <li>Percentage scores (total score ÷ total max score)</li>
                                <li>Weighted scores based on the component percentages</li>
                                <li>Final grade using the transmutation table</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>You can enter grades for an entire class at once, making it efficient to record quiz scores or other assessments.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-calculator text-danger me-2"></i>
                            Grade Calculation and Transmutation
                        </h5>
                        <div class="tutorial-content">
                            <p>Grades are calculated using this process:</p>
                            <ol>
                                <li>Raw scores are converted to percentages for each component</li>
                                <li>Percentages are multiplied by their respective weights</li>
                                <li>Weighted scores are added to get the Initial Grade</li>
                                <li>The Initial Grade is converted to a Transmuted Grade using the official transmutation table</li>
                            </ol>
                            <p>The system displays:</p>
                            <ul>
                                <li>Weighted scores with 1 decimal place</li>
                                <li>Average grades as whole numbers</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>The transmutation table converts percentage scores to the 100-point scale used for official grades.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Grade Approval Process
                        </h5>
                        <div class="tutorial-content">
                            <p>Once you've completed entering all grades for a quarter:</p>
                            <ol>
                                <li>Go to "Grade Approvals" in Dashboard Quick Actions</li>
                                <li>Select the Section and Subject</li>
                                <li>Review the grades for accuracy</li>
                                <li>Check the "Approve" checkbox</li>
                                <li>Click "Submit Approvals"</li>
                            </ol>
                            <p><strong>Important notes about grade approval:</strong></p>
                            <ul>
                                <li>Approved grades are locked and cannot be modified without special permission</li>
                                <li>Only approved grades appear in consolidated reports</li>
                                <li>The approval status is visible to Teacher Admins</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with grades management:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Contact your school's Teacher Admin</li>
                                <li>Use the Support feature to submit a help request</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher.help.tutorial', 'students') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Students Tutorial
                        </a>
                        <a href="{{ route('teacher.help.tutorial', 'attendance') }}" class="tutorial-nav-btn next">
                            Next: Attendance Tutorial <i class="fas fa-arrow-right"></i>
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
