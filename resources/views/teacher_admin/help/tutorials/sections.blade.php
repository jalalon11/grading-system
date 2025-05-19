@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sections Management Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing School Sections</h4>
                            <p class="text-muted">
                                The Sections Management module allows you to create, edit, and manage class sections, assign advisers, and link subjects.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-list text-primary me-2"></i>
                            Viewing Sections
                        </h5>
                        <div class="tutorial-content">
                            <p>The Sections page displays all sections in your school:</p>
                            <ul>
                                <li>View section name, grade level, and status</li>
                                <li>See assigned adviser for each section</li>
                                <li>Check student count per section</li>
                                <li>Filter sections by grade level</li>
                                <li>Search for specific sections by name</li>
                            </ul>
                            <p>The sections list is organized by grade level, making it easy to navigate through your school's structure.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Creating New Sections
                        </h5>
                        <div class="tutorial-content">
                            <p>To create a new section:</p>
                            <ol>
                                <li>Click the "Add Section" button at the top of the Sections page</li>
                                <li>Fill in the required information:
                                    <ul>
                                        <li>Section Name (e.g., "Einstein", "1-A", "Grade 3 - Love")</li>
                                        <li>Grade Level (select from dropdown)</li>
                                        <li>Adviser (select a teacher from dropdown)</li>
                                        <li>Description (optional)</li>
                                    </ul>
                                </li>
                                <li>Click "Save" to create the section</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Choose clear, consistent naming conventions for your sections to make them easier to identify and organize.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-edit text-info me-2"></i>
                            Editing Sections
                        </h5>
                        <div class="tutorial-content">
                            <p>To edit an existing section:</p>
                            <ol>
                                <li>Find the section in the list</li>
                                <li>Click the "Edit" button (pencil icon) in the Actions column</li>
                                <li>Update the necessary information:
                                    <ul>
                                        <li>Section Name</li>
                                        <li>Grade Level</li>
                                        <li>Description</li>
                                    </ul>
                                </li>
                                <li>Click "Update" to save your changes</li>
                            </ol>
                            <p><strong>Note:</strong> Changing a section's grade level will affect which subjects can be assigned to it.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-user-tie text-warning me-2"></i>
                            Assigning or Changing Advisers
                        </h5>
                        <div class="tutorial-content">
                            <p>To assign or change a section's adviser:</p>
                            <ol>
                                <li>Find the section in the list</li>
                                <li>Click the "Change Adviser" button in the Actions column</li>
                                <li>Select a teacher from the dropdown list</li>
                                <li>Click "Update Adviser" to confirm</li>
                            </ol>
                            <p><strong>Important notes about section advisers:</strong></p>
                            <ul>
                                <li>Each section must have one designated adviser</li>
                                <li>Only the adviser can generate grade slips for the section</li>
                                <li>Advisers have special access to their advisory section's data</li>
                                <li>A teacher can be an adviser for multiple sections if needed</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-book text-danger me-2"></i>
                            Assigning Subjects to Sections
                        </h5>
                        <div class="tutorial-content">
                            <p>To assign subjects to a section:</p>
                            <ol>
                                <li>Find the section in the list</li>
                                <li>Click the "Assign Subjects" button in the Actions column</li>
                                <li>In the modal that appears:
                                    <ul>
                                        <li>Check the boxes for subjects you want to assign</li>
                                        <li>Only subjects matching the section's grade level will be shown</li>
                                        <li>For each selected subject, you can assign a teacher from the dropdown</li>
                                    </ul>
                                </li>
                                <li>Click "Save Assignments" to confirm</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>For MAPEH subjects, all four components (Music, Arts, P.E., Health) will be automatically assigned together.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-toggle-on text-primary me-2"></i>
                            Activating and Deactivating Sections
                        </h5>
                        <div class="tutorial-content">
                            <p>To change a section's active status:</p>
                            <ol>
                                <li>Find the section in the list</li>
                                <li>Click the "Activate" or "Deactivate" button in the Actions column</li>
                                <li>Confirm the action when prompted</li>
                            </ol>
                            <p><strong>Effects of deactivating a section:</strong></p>
                            <ul>
                                <li>The section will not appear in dropdown menus for new assignments</li>
                                <li>Students in the section will still be accessible but marked as part of an inactive section</li>
                                <li>Historical data (grades, attendance, etc.) is preserved</li>
                                <li>The section can be reactivated at any time if needed</li>
                            </ul>
                            <p>Deactivating sections is useful at the end of a school year when creating new sections for the next year.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-eye text-success me-2"></i>
                            Viewing Section Details
                        </h5>
                        <div class="tutorial-content">
                            <p>To view detailed information about a section:</p>
                            <ol>
                                <li>Find the section in the list</li>
                                <li>Click the "View" button (eye icon) in the Actions column</li>
                            </ol>
                            <p>The section details page shows:</p>
                            <ul>
                                <li>Basic section information</li>
                                <li>Assigned adviser</li>
                                <li>Assigned subjects and their teachers</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with sections management:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Use the Support feature to contact system administrators</li>
                                <li>Refer to the documentation provided during your onboarding</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.tutorial', 'school') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: School Management
                        </a>
                        <a href="{{ route('teacher-admin.help.tutorial', 'subjects') }}" class="tutorial-nav-btn next">
                            Next: Subjects Management <i class="fas fa-arrow-right"></i>
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
