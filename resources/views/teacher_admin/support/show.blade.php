@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">
                            <i class="fas fa-headset text-primary me-2"></i>
                            Support Ticket #{{ $ticket->id }}
                        </h5>
                        <a href="{{ route('teacher-admin.support.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            <span>Back to Tickets</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Conversation Container - Now at the top -->
                    <div class="conversation-container mb-4">
                        <div class="chat-container bg-light rounded-3 border shadow-sm mb-4">

                            <div class="chat-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-comments text-primary me-2"></i>
                                    <h6 class="mb-0 fw-bold">{{ $ticket->subject }}</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="me-2 d-none d-md-inline text-muted small">Ticket #{{ $ticket->id }}</span>
                                    <span class="badge {{ $ticket->status === 'open' ? 'bg-success' : ($ticket->status === 'in_progress' ? 'bg-primary' : 'bg-secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Shared Support Ticket Notice -->
                            <div class="alert alert-info m-3 mb-0 d-flex align-items-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Shared Support:</strong> This ticket is visible to all teacher admins from your school.
                                    <!-- @if(count($schoolTeacherAdmins) > 1)
                                        <span class="d-block mt-1 small">
                                            Teacher Admins:
                                            @foreach($schoolTeacherAdmins as $admin)
                                                <span class="badge bg-secondary me-1">{{ $admin->name }}</span>
                                            @endforeach
                                        </span>
                                    @endif -->
                                </div>
                            </div>

                            <div id="messages-container" class="messages-container p-3 position-relative" style="height: 550px; overflow-y: auto; background-color: #f8f9fa;">
                                <!-- Scroll to bottom button -->
                                <button id="scroll-to-bottom" class="btn btn-sm btn-primary rounded-circle position-absolute d-none" style="bottom: 20px; right: 20px; width: 40px; height: 40px; z-index: 1000;">
                                    <i class="fas fa-arrow-down"></i>
                                </button>


                                @foreach($messages as $message)
                                    @php
                                        $isCurrentUser = $message->user->id === Auth::id();
                                        $isTeacherAdmin = $message->user->role === 'teacher' && $message->user->is_teacher_admin;
                                        $isAdmin = $message->user->role === 'admin';
                                        $avatarColor = $isAdmin ? '#3498db' : ($isTeacherAdmin ? '#6c757d' : '#28a745');
                                    @endphp
                                    <div class="message-wrapper mb-3 {{ $isCurrentUser ? 'teacher-admin-message' : ($isAdmin ? 'admin-message' : 'other-teacher-admin-message') }}" data-message-id="{{ $message->id }}" data-is-read="{{ $message->is_read ? 'true' : 'false' }}">
                                        <!-- Mobile-optimized message layout -->
                                        <div class="d-flex {{ $isCurrentUser ? 'justify-content-end' : 'justify-content-start' }}">
                                            <!-- Avatar for non-current user (hidden on mobile) -->
                                            @if(!$isCurrentUser)
                                                <div class="avatar me-2 d-flex align-items-start d-none d-sm-flex">
                                                    <div class="avatar-circle" style="width: 36px; height: 36px; background-color: {{ $avatarColor }};">
                                                        <span class="initials">{{ substr($message->user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <!-- Mobile avatar (small version) -->
                                                <div class="avatar-mobile me-1 d-flex d-sm-none align-items-start">
                                                    <div class="avatar-circle" style="width: 24px; height: 24px; background-color: {{ $avatarColor }};">
                                                        <span class="initials" style="font-size: 10px;">{{ substr($message->user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="message-content-wrapper {{ $isCurrentUser ? 'text-end admin-message-wrapper' : 'user-message-wrapper' }}" style="max-width: 85%;">
                                                @php
                                                    $bubbleClass = $isCurrentUser ? 'bg-primary text-white' :
                                                                  ($isAdmin ? 'bg-white' :
                                                                  'bg-light border border-secondary');
                                                @endphp
                                                <div class="message-bubble d-inline-block p-3 rounded-3 shadow-sm {{ $bubbleClass }}">
                                                    <div class="message-content">
                                                        {{ $message->message }}
                                                    </div>
                                                </div>
                                                <div class="message-meta mt-1 {{ $isCurrentUser ? 'text-end' : '' }} text-muted small">
                                                    <!-- Username with role indicator -->
                                                    <span class="d-none d-sm-inline">
                                                        {{ $message->user->name }}
                                                        @if($isAdmin)
                                                            <span class="badge bg-primary ms-1">Admin</span>
                                                        @elseif($isTeacherAdmin)
                                                            <span class="badge bg-secondary ms-1">Teacher Admin</span>
                                                        @endif
                                                    </span>
                                                    <span class="d-sm-none">
                                                        {{ Str::limit($message->user->name, 10) }}
                                                        @if($isAdmin)
                                                            <span class="badge bg-primary ms-1" style="font-size: 0.6rem;">Admin</span>
                                                        @elseif($isTeacherAdmin)
                                                            <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">TA</span>
                                                        @endif
                                                    </span> •
                                                    <!-- Timestamp - different format for mobile -->
                                                    <span class="d-none d-sm-inline">{{ $message->created_at->format('M d, Y h:i A') }}</span>
                                                    <span class="d-sm-none">{{ $message->created_at->format('h:i A') }}</span>

                                                    @if($isCurrentUser)
                                                    <span class="message-status-indicators ms-2">
                                                        <!-- Only one status will be visible at a time -->
                                                        <span class="message-status sent {{ $message->is_read ? 'd-none' : '' }}" title="Sent" data-status="sent">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                        <span class="message-status delivered d-none" title="Delivered" data-status="delivered">
                                                            <i class="fas fa-check-double"></i>
                                                        </span>
                                                        <span class="message-status read {{ $message->is_read ? '' : 'd-none' }}" title="Read" data-status="read">
                                                            <i class="fas fa-check-double"></i>
                                                        </span>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($isCurrentUser)
                                                <!-- Desktop avatar -->
                                                <div class="avatar ms-2 d-flex align-items-start d-none d-sm-flex">
                                                    <div class="avatar-circle" style="width: 36px; height: 36px; background-color: {{ $avatarColor }};">
                                                        <span class="initials">{{ substr($message->user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <!-- Mobile avatar (small version) -->
                                                <div class="avatar-mobile ms-1 d-flex d-sm-none align-items-start">
                                                    <div class="avatar-circle" style="width: 24px; height: 24px; background-color: {{ $avatarColor }};">
                                                        <span class="initials" style="font-size: 10px;">{{ substr($message->user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="chat-footer p-2 p-sm-3 bg-white border-top">
                                @if($ticket->status != 'closed')
                                    <form action="{{ route('teacher-admin.support.reply', $ticket->id) }}" method="POST" class="reply-form" id="reply-form">
                                        @csrf
                                        <div class="input-group mobile-input-group">
                                            <textarea name="message" id="message" rows="1" class="form-control @error('message') is-invalid @enderror" placeholder="Type your reply..." required></textarea>
                                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                                <i class="fas fa-paper-plane"></i>
                                                <span class="d-none d-md-inline ms-2">Send</span>
                                            </button>
                                        </div>
                                        @error('message')
                                            <div class="invalid-feedback d-block mt-1 small">{{ $message }}</div>
                                        @enderror
                                    </form>
                                @else
                                    <div class="alert alert-secondary mb-0 py-2">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-lock me-2"></i>
                                                <span class="fw-medium">This ticket is closed</span>
                                            </div>
                                            <a href="#" class="btn btn-sm btn-outline-secondary mt-2 mt-sm-0" onclick="event.preventDefault(); document.getElementById('reopen-form').submit();">
                                                <i class="fas fa-lock-open me-1 d-inline d-sm-none"></i>
                                                <span>Reopen</span>
                                            </a>
                                            <form id="reopen-form" action="{{ route('teacher-admin.support.reply', $ticket->id) }}" method="POST" class="d-none">
                                                @csrf
                                                <input type="hidden" name="message" value="This ticket has been reopened by the teacher admin.">
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Information - Now at the bottom -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white py-3">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        Ticket Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Subject</label>
                                                <p class="mb-0 fw-medium">{{ $ticket->subject }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Created On</label>
                                                <p class="mb-0">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Status</label>
                                                <p class="mb-0">
                                                    @if($ticket->status == 'open')
                                                        <span class="badge bg-success">Open</span>
                                                    @elseif($ticket->status == 'in_progress')
                                                        <span class="badge bg-primary">In Progress</span>
                                                    @else
                                                        <span class="badge bg-secondary">Closed</span>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Priority</label>
                                                <p class="mb-0">
                                                    @if($ticket->priority == 'high')
                                                        <span class="badge bg-danger">High</span>
                                                    @elseif($ticket->priority == 'medium')
                                                        <span class="badge bg-warning text-dark">Medium</span>
                                                    @else
                                                        <span class="badge bg-info">Low</span>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-muted small">Last Reply</label>
                                                <p class="mb-0">{{ $ticket->last_reply_at ? $ticket->last_reply_at->format('M d, Y h:i A') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Mobile-friendly message styling */
    .user-message-wrapper {
        margin-left: 8px;
    }

    .admin-message-wrapper {
        margin-right: 8px;
    }

    /* Styling for different user types */
    .other-teacher-admin-message .message-bubble {
        border-color: #6c757d !important;
    }

    /* Avatar styling */
    .avatar-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }

    .initials {
        font-size: 14px;
    }

    /* Message bubble styling */
    .message-bubble {
        border-radius: 18px;
        word-break: break-word;
        hyphens: auto;
    }

    /* Mobile-specific styles */
    @media (max-width: 576px) {
        /* Smaller message bubbles on mobile */
        .message-bubble {
            padding: 0.625rem !important;
            font-size: 0.95rem;
        }

        /* Wider messages on mobile */
        .message-content-wrapper {
            max-width: 80% !important;
        }

        /* Smaller meta text on mobile */
        .message-meta {
            font-size: 0.7rem !important;
        }

        /* Optimize input group for mobile */
        .mobile-input-group {
            flex-wrap: nowrap !important;
        }

        /* Optimize textarea for mobile */
        .mobile-input-group textarea {
            min-height: 38px;
            font-size: 0.9rem;
            padding: 0.375rem 0.75rem;
        }

        /* Make send button more compact on mobile */
        .mobile-input-group .btn {
            padding: 0.375rem 0.75rem;
            width: 42px;
        }

        /* Adjust spacing for mobile */
        .message-wrapper {
            margin-bottom: 0.75rem !important;
        }

        /* Adjust card padding for mobile */
        .card-body {
            padding: 0.75rem !important;
        }

        /* Adjust container height for mobile */
        #messages-container {
            height: 450px !important;
        }
    }

    /* Improved scrolling for mobile */
    .messages-container {
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }

    /* Message status indicators */
    .message-status-indicators {
        display: inline-flex;
        align-items: center;
        position: relative;
        width: 16px;
        height: 16px;
    }

    .message-status {
        transition: all 0.2s ease-in-out;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .message-status.sent i {
        color: #adb5bd;
    }

    .message-status.delivered i {
        color: #6c757d;
    }

    .message-status.read i {
        color: #0d6efd;
    }

    /* Animation for status changes */
    @keyframes statusPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .message-status.active {
        animation: statusPulse 0.3s ease-in-out;
    }

    /* Scroll to bottom button styling */
    #scroll-to-bottom {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #scroll-to-bottom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }

    #scroll-to-bottom i {
        font-size: 1.2rem;
    }
</style>
@endpush

@push('scripts')
@vite(['resources/js/support-chat.js'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-resize textarea as user types (for mobile)
        const messageInput = document.getElementById('message');
        if (messageInput) {
            // Focus the input when the page loads
            setTimeout(() => {
                messageInput.focus();
            }, 500);

            // Auto-resize the textarea
            messageInput.addEventListener('input', function() {
                // Reset height to auto to get the correct scrollHeight
                this.style.height = 'auto';

                // Set new height based on scrollHeight (with a max height)
                const newHeight = Math.min(this.scrollHeight, 150);
                this.style.height = newHeight + 'px';
            });

            // Handle form submission
            const replyForm = document.getElementById('reply-form');
            if (replyForm) {
                replyForm.addEventListener('submit', function() {
                    // Show loading state on button
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    }
                });
            }
        }

        // Add tap-to-scroll functionality for mobile
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer && window.innerWidth < 768) {
            messagesContainer.addEventListener('click', function(e) {
                // Only trigger if clicking directly on the container (not on messages)
                if (e.target === this) {
                    scrollToBottom(messagesContainer);
                }
            });
        }
    });
</script>
@endpush

<!-- Hidden inputs for JavaScript -->
<input type="hidden" id="ticket-id" value="{{ $ticket->id }}">
<input type="hidden" id="current-user-id" value="{{ Auth::id() }}">
<input type="hidden" id="school-id" value="{{ Auth::user()->school_id }}">
<input type="hidden" id="is-shared-support" value="true">
