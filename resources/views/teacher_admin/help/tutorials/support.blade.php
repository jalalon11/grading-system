@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Support System Tutorial</h5>
                    <a href="{{ route('teacher-admin.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Using the Support System</h4>
                            <p class="text-muted">
                                The Support System allows you to communicate directly with system administrators for help with technical issues, billing questions, and feature requests.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-ticket-alt text-primary me-2"></i>
                            Creating a Support Ticket
                        </h5>
                        <div class="tutorial-content">
                            <p>To create a new support ticket:</p>
                            <ol>
                                <li>Click on "Support" in the sidebar menu</li>
                                <li>Click the "New Support Ticket" button</li>
                                <li>Fill in the ticket details:
                                    <ul>
                                        <li><strong>Category:</strong> Select the appropriate category for your issue (Technical Issue, Billing Question, Feature Request, etc.)</li>
                                        <li><strong>Subject:</strong> Enter a clear, concise subject that describes your issue</li>
                                        <li><strong>Message:</strong> Provide a detailed description of your issue or question</li>
                                        <li><strong>Attachments (optional):</strong> Upload screenshots or other files that help explain your issue</li>
                                    </ul>
                                </li>
                                <li>Click "Submit Ticket" to create the ticket</li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Be as specific as possible when describing your issue. Include steps to reproduce the problem, error messages you've seen, and any troubleshooting steps you've already tried.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-comments text-success me-2"></i>
                            Communicating with Support
                        </h5>
                        <div class="tutorial-content">
                            <p>Once you've created a ticket, you can communicate with system administrators through the ticket's message thread:</p>
                            <ol>
                                <li>Go to the Support page</li>
                                <li>Click on the ticket you want to view</li>
                                <li>Read the administrator's response</li>
                                <li>Type your reply in the message box at the bottom of the page</li>
                                <li>Click "Send" to add your message to the thread</li>
                            </ol>
                            <p>The chat interface works in real-time, with messages refreshing automatically. You'll see status indicators for each message:</p>
                            <ul>
                                <li><strong>Sent:</strong> Your message has been sent to the server</li>
                                <li><strong>Delivered:</strong> Your message has been delivered to the administrator</li>
                                <li><strong>Read:</strong> The administrator has read your message</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>All times shown in the support system use Philippines (Manila) time zone.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-list-alt text-info me-2"></i>
                            Managing Your Support Tickets
                        </h5>
                        <div class="tutorial-content">
                            <p>The Support page shows a list of all your support tickets:</p>
                            <ul>
                                <li><strong>Open Tickets:</strong> Active tickets that are still being addressed</li>
                                <li><strong>Closed Tickets:</strong> Tickets that have been resolved</li>
                            </ul>
                            <p>For each ticket, you can see:</p>
                            <ul>
                                <li>Ticket ID</li>
                                <li>Subject</li>
                                <li>Category</li>
                                <li>Status (Open, In Progress, Resolved, Closed)</li>
                                <li>Created Date</li>
                                <li>Last Updated Date</li>
                            </ul>
                            <p>To manage your tickets:</p>
                            <ul>
                                <li><strong>View a ticket:</strong> Click on the ticket subject to open the conversation</li>
                                <li><strong>Close a ticket:</strong> Click the "Close Ticket" button if your issue has been resolved</li>
                                <li><strong>Reopen a ticket:</strong> If you need to follow up on a closed ticket, click "Reopen Ticket"</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-bell text-warning me-2"></i>
                            Notifications
                        </h5>
                        <div class="tutorial-content">
                            <p>You'll receive notifications when there are updates to your support tickets:</p>
                            <ul>
                                <li><strong>In-App Notifications:</strong> A notification badge will appear on the Support menu item in the sidebar</li>
                                <li><strong>Email Notifications:</strong> You'll receive an email when an administrator responds to your ticket</li>
                            </ul>
                            <p>To manage your notification preferences:</p>
                            <ol>
                                <li>Click on your profile picture in the top-right corner</li>
                                <li>Select "My Profile" from the dropdown menu</li>
                                <li>Click on the "Notifications" tab</li>
                                <li>Adjust your notification settings as needed</li>
                                <li>Click "Save Changes" to update your preferences</li>
                            </ol>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-key text-danger me-2"></i>
                            Requesting Teacher Admin Registration Keys
                        </h5>
                        <div class="tutorial-content">
                            <p>To request a Teacher Admin registration key:</p>
                            <ol>
                                <li>Create a new support ticket</li>
                                <li>Select "Registration Key Request" as the category</li>
                                <li>In your message, include:
                                    <ul>
                                        <li>The reason for needing an additional Teacher Admin</li>
                                        <li>The name and email of the person who will use the key</li>
                                        <li>Your school name and ID</li>
                                    </ul>
                                </li>
                                <li>Submit the ticket</li>
                            </ol>
                            <p>System administrators will review your request and, if approved, will provide you with a Teacher Admin registration key through the support ticket conversation.</p>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Registration keys for regular teachers can be generated directly from the School page without contacting support. Only Teacher Admin keys require approval from system administrators.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Common Support Requests
                        </h5>
                        <div class="tutorial-content">
                            <p>Here are some common types of support requests and the information to include:</p>
                            <ul>
                                <li><strong>Technical Issues:</strong> Describe the problem, steps to reproduce, error messages, browser/device information</li>
                                <li><strong>Billing Questions:</strong> Include payment reference numbers, transaction dates, and screenshots of receipts if available</li>
                                <li><strong>Feature Requests:</strong> Clearly explain the feature you'd like to see added and how it would benefit your school</li>
                                <li><strong>Data Corrections:</strong> Provide the specific data that needs correction, including IDs, names, and the correct information</li>
                                <li><strong>Account Issues:</strong> Include the affected user's name, email, and the specific problem they're experiencing</li>
                            </ul>
                            <p>Providing complete information in your initial request helps administrators resolve your issue more quickly.</p>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher-admin.help.tutorial', 'registration_keys') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Registration Keys
                        </a>
                        <a href="{{ route('teacher-admin.help.tutorial', 'faq') }}" class="tutorial-nav-btn next">
                            Next: FAQ <i class="fas fa-arrow-right"></i>
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
