@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Frequently Asked Questions</h5>
                    <a href="{{ route('teacher.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Teacher Module FAQs</h4>
                            <p class="text-muted">
                                Find answers to common questions about using the Teacher Module.
                            </p>
                        </div>
                    </div>

                    <div class="accordion" id="faqAccordion">
                        <!-- General Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">General Questions</h5>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        How do I change my password?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To change your password:</p>
                                        <ol>
                                            <li>Click on your profile picture in the top-right corner</li>
                                            <li>Select "My Profile" from the dropdown menu</li>
                                            <li>Click on the "Security" tab</li>
                                            <li>Enter your current password and your new password</li>
                                            <li>Click "Update Password" to save your changes</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What should I do if I forgot my password?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>If you forgot your password:</p>
                                        <ol>
                                            <li>Click on the "Forgot Password" link on the login page</li>
                                            <li>Enter the email address associated with your account</li>
                                            <li>Check your email for a password reset link</li>
                                            <li>Click the link and follow the instructions to create a new password</li>
                                        </ol>
                                        <p>If you don't receive the email, check your spam folder or contact your school's Teacher Admin for assistance.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Grades Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Grades Management</h5>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        How is the final grade calculated?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>The final grade is calculated using the following components and weights:</p>
                                        <ul>
                                            <li><strong>Written Works (WW):</strong> 30% of the final grade</li>
                                            <li><strong>Performance Tasks (PT):</strong> 50% of the final grade</li>
                                            <li><strong>Quarterly Assessment (QA):</strong> 20% of the final grade</li>
                                        </ul>
                                        <p>For each component, the raw scores are converted to percentages (total score ÷ total max score), then multiplied by their respective weights. The weighted scores are added to get the Initial Grade, which is then converted to a Transmuted Grade using the official transmutation table.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Can I edit grades after they've been approved?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Once grades have been approved, they are locked and cannot be modified directly. If you need to make changes to approved grades:</p>
                                        <ol>
                                            <li>Contact your school's Teacher Admin</li>
                                            <li>Explain the reason for the grade change</li>
                                            <li>The Teacher Admin can unlock the grades for editing</li>
                                            <li>Make your changes and resubmit for approval</li>
                                        </ol>
                                        <p>This approval process ensures the integrity of the grading system and maintains an audit trail of any changes.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Attendance Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Attendance Management</h5>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Can I record attendance for past dates?
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>Yes, you can record attendance for past dates, but there are some limitations:</p>
                                        <ul>
                                            <li>You can record attendance for any date within the current school year</li>
                                            <li>You cannot record attendance for future dates</li>
                                            <li>You cannot record attendance for dates before the start of the school year</li>
                                        </ul>
                                        <p>To record attendance for a past date, simply select the desired date in the date picker when recording attendance.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        What's the difference between "Absent" and "Excused"?
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>The attendance status options have the following meanings:</p>
                                        <ul>
                                            <li><strong>Present:</strong> Student attended the class</li>
                                            <li><strong>Absent:</strong> Student did not attend the class without a valid excuse</li>
                                            <li><strong>Late:</strong> Student arrived late to class</li>
                                            <li><strong>Excused:</strong> Student did not attend the class but has a valid excuse (medical certificate, approved leave, etc.)</li>
                                        </ul>
                                        <p>In attendance reports, "Excused" absences are counted separately from regular absences, which can affect the student's attendance rate differently depending on your school's policies.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reports Questions -->
                        <div class="mb-4">
                            <h5 class="mb-3">Reports</h5>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        Why can't I generate grade slips for my section?
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>There are several reasons why you might not be able to generate grade slips:</p>
                                        <ol>
                                            <li>You are not the designated adviser for the section (only section advisers can generate grade slips)</li>
                                            <li>Not all subject grades have been approved by the subject teachers</li>
                                            <li>The grading period is still ongoing and has not been closed by the Teacher Admin</li>
                                        </ol>
                                        <p>Check with your Teacher Admin to ensure all grades have been submitted and approved, and that you are properly assigned as the section adviser.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingEight">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                        How do I print reports in landscape mode?
                                    </button>
                                </h2>
                                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>To print reports in landscape mode:</p>
                                        <ol>
                                            <li>Generate the report you want to print</li>
                                            <li>Click the "Print" button</li>
                                            <li>In the browser's print dialog, look for "Layout" or "Orientation" settings</li>
                                            <li>Select "Landscape" instead of "Portrait"</li>
                                            <li>Adjust other settings as needed (paper size, margins, etc.)</li>
                                            <li>Click "Print" or "Save as PDF"</li>
                                        </ol>
                                        <p>Some reports are designed specifically for landscape orientation and will automatically suggest this setting when printing.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher.help.tutorial', 'reports') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Reports Tutorial
                        </a>
                        <a href="{{ route('teacher.help.index') }}" class="tutorial-nav-btn next">
                            Back to Help Center <i class="fas fa-arrow-right"></i>
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
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>

<!-- Include the tutorial navigation CSS -->
<link rel="stylesheet" href="{{ asset('css/tutorial-nav.css') }}">
@endsection
