@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Students Management Tutorial</h5>
                    <a href="{{ route('teacher.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing Students</h4>
                            <p class="text-muted">
                                The Students module allows you to manage student records, view student information, and track academic progress.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-list text-primary me-2"></i>
                            Viewing Student Lists
                        </h5>
                        <div class="tutorial-content">
                            <p>The Students page displays a list of all students in your assigned sections:</p>
                            <ul>
                                <li><strong>Filter by Section:</strong> Use the dropdown to view students from a specific section</li>
                                <li><strong>Search:</strong> Use the search box to find students by name or ID</li>
                                <li><strong>Sort:</strong> Click on column headers to sort the list</li>
                                <li><strong>Pagination:</strong> Navigate through multiple pages of student records</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Students are sorted alphabetically by surname by default.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-user-plus text-success me-2"></i>
                            Adding New Students
                        </h5>
                        <div class="tutorial-content">
                            <p>To add a new student to your section:</p>
                            <ol>
                                <li>Click the "Add Student" button at the top of the Students page</li>
                                <li>Fill in the required information:
                                    <ul>
                                        <li>First Name and Last Name</li>
                                        <li>Student ID (if applicable)</li>
                                        <li>Gender</li>
                                        <li>Section (select from dropdown)</li>
                                        <li>Guardian Name (required)</li>
                                        <li>Guardian Contact (required)</li>
                                    </ul>
                                </li>
                                <li>Click "Save" to add the student</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Make sure to enter accurate guardian information as it's required for communication purposes.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-edit text-info me-2"></i>
                            Editing Student Information
                        </h5>
                        <div class="tutorial-content">
                            <p>To edit a student's information:</p>
                            <ol>
                                <li>Find the student in the list</li>
                                <li>Click the "Edit" button (pencil icon) in the Actions column</li>
                                <li>Update the necessary information</li>
                                <li>Click "Save Changes" to update the record</li>
                            </ol>
                            <p>You can edit the following information:</p>
                            <ul>
                                <li>Personal details (name, gender, etc.)</li>
                                <li>Section assignment</li>
                                <li>Guardian information</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-user-slash text-danger me-2"></i>
                            Disabling Students
                        </h5>
                        <div class="tutorial-content">
                            <p>Instead of deleting students, you can disable them when they are no longer active:</p>
                            <ol>
                                <li>Find the student in the list</li>
                                <li>Click the "Disable" button in the Actions column</li>
                                <li>Confirm the action when prompted</li>
                            </ol>
                            <p><strong>Important notes about disabled students:</strong></p>
                            <ul>
                                <li>Disabled students appear greyed out and at the end of student lists</li>
                                <li>They do not appear in reports, grade pages, or attendance records</li>
                                <li>Their records are preserved in the system for historical purposes</li>
                                <li>You can reactivate a disabled student if needed</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-user-check text-success me-2"></i>
                            Reactivating Students
                        </h5>
                        <div class="tutorial-content">
                            <p>To reactivate a previously disabled student:</p>
                            <ol>
                                <li>Scroll to the bottom of the student list where disabled students appear</li>
                                <li>Find the disabled student (shown in grey)</li>
                                <li>Click the "Reactivate" button in the Actions column</li>
                                <li>Confirm the action when prompted</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Reactivating a student will include them in all active records again, including attendance and grades.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with student management:</p>
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
                        <a href="{{ route('teacher.help.tutorial', 'grades') }}" class="tutorial-nav-btn next">
                            Next: Grades Tutorial <i class="fas fa-arrow-right"></i>
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
