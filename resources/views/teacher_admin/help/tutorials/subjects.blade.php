@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Subjects Management Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Managing School Subjects</h4>
                            <p class="text-muted">
                                The Subjects Management module allows you to create, edit, and manage academic subjects, assign teachers, and configure subject settings.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-list text-primary me-2"></i>
                            Viewing Subjects
                        </h5>
                        <div class="tutorial-content">
                            <p>The Subjects page displays all subjects in your school:</p>
                            <ul>
                                <li>View subject name, code, and grade level</li>
                                <li>See subject type (Regular, MAPEH, or Special)</li>
                                <li>Check active/inactive status</li>
                                <li>Filter subjects by grade level</li>
                                <li>Search for specific subjects by name or code</li>
                            </ul>
                            <p>The subjects list is organized by grade level, making it easy to navigate through your curriculum.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Creating New Subjects
                        </h5>
                        <div class="tutorial-content">
                            <p>To create a new subject:</p>
                            <ol>
                                <li>Click the "Add Subject" button at the top of the Subjects page</li>
                                <li>Fill in the required information:
                                    <ul>
                                        <li>Subject Name (e.g., "Mathematics", "English", "Science")</li>
                                        <li>Subject Code (e.g., "MATH", "ENG", "SCI")</li>
                                        <li>Grade Level (select from dropdown)</li>
                                        <li>Subject Type (Regular, MAPEH, or Special)</li>
                                        <li>Description (optional)</li>
                                    </ul>
                                </li>
                                <li>Click "Save" to create the subject</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>For MAPEH subjects, create the main MAPEH subject first, then you can add its components (Music, Arts, P.E., Health) separately.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-edit text-info me-2"></i>
                            Editing Subjects
                        </h5>
                        <div class="tutorial-content">
                            <p>To edit an existing subject:</p>
                            <ol>
                                <li>Find the subject in the list</li>
                                <li>Click the "Edit" button (pencil icon) in the Actions column</li>
                                <li>Update the necessary information:
                                    <ul>
                                        <li>Subject Name</li>
                                        <li>Subject Code</li>
                                        <li>Grade Level</li>
                                        <li>Description</li>
                                    </ul>
                                </li>
                                <li>Click "Update" to save your changes</li>
                            </ol>
                            <p><strong>Note:</strong> Changing a subject's grade level will affect which sections it can be assigned to.</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-chalkboard-teacher text-warning me-2"></i>
                            Assigning Teachers to Subjects
                        </h5>
                        <div class="tutorial-content">
                            <p>To assign teachers to a subject:</p>
                            <ol>
                                <li>Find the subject in the list</li>
                                <li>Click the "Assign Teachers" button in the Actions column</li>
                                <li>In the modal that appears:
                                    <ul>
                                        <li>Select the sections where this subject is taught</li>
                                        <li>For each selected section, assign a teacher from the dropdown</li>
                                        <li>You can assign different teachers to the same subject for different sections</li>
                                    </ul>
                                </li>
                                <li>Click "Save Assignments" to confirm</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>When assigning teachers to MAPEH components, make sure to assign teachers to each component (Music, Arts, P.E., Health) separately.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-cogs text-danger me-2"></i>
                            Configuring MAPEH Subjects
                        </h5>
                        <div class="tutorial-content">
                            <p>MAPEH (Music, Arts, Physical Education, and Health) subjects require special configuration:</p>
                            <ol>
                                <li>Create the main MAPEH subject with type "MAPEH"</li>
                                <li>Create each component subject (Music, Arts, P.E., Health) with type "MAPEH Component"</li>
                                <li>Link the components to the main MAPEH subject:
                                    <ul>
                                        <li>Edit each component subject</li>
                                        <li>Select the main MAPEH subject as the parent</li>
                                        <li>Save the changes</li>
                                    </ul>
                                </li>
                            </ol>
                            <p><strong>How MAPEH grading works:</strong></p>
                            <ul>
                                <li>Teachers enter grades for each component separately</li>
                                <li>The system automatically calculates the average of all components</li>
                                <li>The final MAPEH grade is the average of all component grades</li>
                                <li>All components must have grades entered for the MAPEH grade to be calculated</li>
                            </ul>
                        </div>
                    </div>

                    <!-- <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-toggle-on text-primary me-2"></i>
                            Activating and Deactivating Subjects
                        </h5>
                        <div class="tutorial-content">
                            <p>To change a subject's active status:</p>
                            <ol>
                                <li>Find the subject in the list</li>
                                <li>Click the "Activate" or "Deactivate" button in the Actions column</li>
                                <li>Confirm the action when prompted</li>
                            </ol>
                            <p><strong>Effects of deactivating a subject:</strong></p>
                            <ul>
                                <li>The subject will not appear in dropdown menus for new assignments</li>
                                <li>Existing grades for the subject are preserved</li>
                                <li>The subject will not appear in new reports</li>
                                <li>The subject can be reactivated at any time if needed</li>
                            </ul>
                            <p>Deactivating subjects is useful when your curriculum changes or when certain subjects are not offered in the current school year.</p>
                        </div>
                    </div> -->

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-eye text-success me-2"></i>
                            Viewing Subject Details
                        </h5>
                        <div class="tutorial-content">
                            <p>To view detailed information about a subject:</p>
                            <ol>
                                <li>Find the subject in the list</li>
                                <li>Click the "View" button (eye icon) in the Actions column</li>
                            </ol>
                            <p>The subject details page shows:</p>
                            <ul>
                                <li>Basic subject information</li>
                                <li>Sections where the subject is taught</li>
                                <li>Teachers assigned to the subject</li>
                                <li>For MAPEH subjects, the component subjects</li>
                            </ul>
                            <p>From this page, you can also:</p>
                            <ul>
                                <li>Quickly access the edit subject form</li>
                                <li>Assign teachers to the subject</li>
                                <li>View grade distributions for the subject</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with subjects management:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Use the Support feature to contact system administrators</li>
                                <li>Refer to the documentation provided during your onboarding</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.tutorial', 'sections') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Sections Management
                        </a>
                        <a href="{{ route('teacher-admin.help.tutorial', 'reports') }}" class="tutorial-nav-btn next">
                            Next: Reports <i class="fas fa-arrow-right"></i>
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
