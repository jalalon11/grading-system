@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="header-icon-container me-3 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width: 48px; height: 48px;">
                                    <i class="fas fa-headset text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Support Tickets</h5>
                                    <p class="text-muted mb-0 mt-1 small">Get help from our support team</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('teacher-admin.support.create') }}" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i>
                                New Support Ticket
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Mobile Card View (visible on small screens) -->
                    <div class="d-md-none px-3 pt-3">
                        @forelse($tickets as $ticket)
                            <div class="card mb-4 border-0 rounded-4 shadow-sm ticket-card {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'has-unread' : '' }}">
                                <div class="card-body p-0">
                                    <!-- Card Header with Priority Indicator -->
                                    <div class="ticket-card-header p-3 d-flex justify-content-between align-items-center border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="ticket-icon me-3 d-flex align-items-center justify-content-center rounded-circle {{ $ticket->status == 'closed' ? 'bg-secondary' : ($ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning' : 'bg-info')) }}" style="width: 40px; height: 40px; color: white; box-shadow: 0 3px 6px rgba(0,0,0,0.1);">
                                                <i class="fas fa-headset"></i>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold text-dark">#{{ $ticket->id }}</span>
                                                    @if($ticket->unreadMessagesCountForSchool() > 0)
                                                        <span class="badge bg-danger rounded-pill ms-2 animate__animated animate__pulse animate__infinite">{{ $ticket->unreadMessagesCountForSchool() }}</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted mt-1">
                                                    Created {{ $ticket->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge {{ $ticket->status == 'open' ? 'bg-success' : ($ticket->status == 'in_progress' ? 'bg-primary' : 'bg-secondary') }} rounded-pill px-3 py-2 d-inline-flex align-items-center">
                                                <i class="fas {{ $ticket->status == 'open' ? 'fa-circle-check' : ($ticket->status == 'in_progress' ? 'fa-spinner fa-spin' : 'fa-lock') }} me-1"></i>
                                                <span>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="p-3">
                                        <div class="mb-3">
                                            <h6 class="fw-bold text-truncate mb-2">{{ $ticket->subject }}</h6>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2" style="width: 24px; height: 24px; background-color: #3498db; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                    <span style="font-size: 12px;">{{ substr($ticket->user->name, 0, 1) }}</span>
                                                </div>
                                                <span class="small text-muted">{{ $ticket->user->name }}</span>
                                            </div>
                                        </div>

                                        <!-- Priority Badge -->
                                        <div class="mb-3">
                                            <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning text-dark' : 'bg-info') }} rounded-pill px-3 py-2">
                                                <i class="fas {{ $ticket->priority == 'high' ? 'fa-exclamation-circle' : ($ticket->priority == 'medium' ? 'fa-exclamation' : 'fa-info-circle') }} me-1"></i>
                                                {{ ucfirst($ticket->priority) }} Priority
                                            </span>
                                        </div>

                                        <!-- Card Footer -->
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="small text-muted d-flex align-items-center">
                                                <div class="position-relative me-2">
                                                    <div class="activity-indicator {{ $ticket->status != 'closed' && $ticket->last_reply_at && $ticket->last_reply_at->diffInHours() < 24 ? 'recent' : '' }}" style="width: 8px; height: 8px;"></div>
                                                </div>
                                                <span>{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'No activity' }}</span>
                                            </div>
                                            <a href="{{ route('teacher-admin.support.show', $ticket->id) }}" class="btn {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'btn-primary' : 'btn-outline-primary' }} px-3 shadow-sm">
                                                <i class="fas {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'fa-envelope' : 'fa-eye' }} me-1"></i>
                                                {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'View New' : 'View' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="empty-state-header bg-primary bg-opacity-10 p-4 text-center">
                                        <div class="empty-state-icon mb-3 bg-white text-primary d-flex align-items-center justify-content-center rounded-circle mx-auto shadow-sm" style="width: 80px; height: 80px;">
                                            <i class="fas fa-inbox fa-2x"></i>
                                        </div>
                                        <h5 class="text-primary mb-0">No Support Tickets Found</h5>
                                    </div>
                                    <div class="p-4 text-center">
                                        <p class="text-muted mb-4">Create a new support ticket to get help from our system administrators. We typically respond within 24 hours.</p>
                                        <a href="{{ route('teacher-admin.support.create') }}" class="btn btn-primary px-4 shadow-sm">
                                            <i class="fas fa-plus-circle me-2"></i>
                                            Create Support Ticket
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop Table View (visible on medium screens and up) -->
                    <div class="table-responsive d-none d-md-block px-4 pt-4">
                        <table class="table table-hover align-middle mb-0 modern-table">
                            <thead>
                                <tr>
                                    <th class="ps-3 py-3 border-bottom">Ticket</th>
                                    <th class="py-3 border-bottom">Subject</th>
                                    <th class="py-3 border-bottom">Created By</th>
                                    <th class="py-3 border-bottom">Status</th>
                                    <th class="py-3 border-bottom">Priority</th>
                                    <th class="py-3 border-bottom">Last Activity</th>
                                    <th class="text-end pe-3 py-3 border-bottom">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr class="ticket-row {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'has-unread' : '' }}">
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="ticket-icon me-3 d-flex align-items-center justify-content-center rounded-circle {{ $ticket->status == 'closed' ? 'bg-secondary' : ($ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning' : 'bg-info')) }}" style="width: 44px; height: 44px; color: white; box-shadow: 0 3px 6px rgba(0,0,0,0.1);">
                                                    <i class="fas fa-headset"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">#{{ $ticket->id }}</div>
                                                    <div class="small text-muted">Created {{ $ticket->created_at->format('M d, Y') }}</div>
                                                </div>
                                                @if($ticket->unreadMessagesCountForSchool() > 0)
                                                    <div class="ms-2 position-relative">
                                                        <span class="badge bg-danger rounded-pill animate__animated animate__pulse animate__infinite">{{ $ticket->unreadMessagesCountForSchool() }}</span>
                                                        <div class="notification-pulse"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-truncate fw-medium" style="max-width: 250px;" title="{{ $ticket->subject }}">
                                                {{ $ticket->subject }}
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2 shadow-sm" style="width: 32px; height: 32px; background-color: #3498db; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white;">
                                                    <span style="font-size: 14px;">{{ substr($ticket->user->name, 0, 1) }}</span>
                                                </div>
                                                <span>{{ $ticket->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge {{ $ticket->status == 'open' ? 'bg-success' : ($ticket->status == 'in_progress' ? 'bg-primary' : 'bg-secondary') }} rounded-pill px-3 py-2 d-inline-flex align-items-center">
                                                <i class="fas {{ $ticket->status == 'open' ? 'fa-circle-check' : ($ticket->status == 'in_progress' ? 'fa-spinner fa-spin' : 'fa-lock') }} me-1"></i>
                                                <span>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning text-dark' : 'bg-info') }} rounded-pill px-3 py-2 d-inline-flex align-items-center">
                                                <i class="fas {{ $ticket->priority == 'high' ? 'fa-exclamation-circle' : ($ticket->priority == 'medium' ? 'fa-exclamation' : 'fa-info-circle') }} me-1"></i>
                                                <span>{{ ucfirst($ticket->priority) }}</span>
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative me-2">
                                                    <div class="activity-indicator {{ $ticket->status != 'closed' && $ticket->last_reply_at && $ticket->last_reply_at->diffInHours() < 24 ? 'recent' : '' }}"></div>
                                                </div>
                                                <span>{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'No activity' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3 py-3">
                                            <a href="{{ route('teacher-admin.support.show', $ticket->id) }}" class="btn {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'btn-primary' : 'btn-outline-primary' }} px-3 shadow-sm">
                                                <i class="fas {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'fa-envelope' : 'fa-eye' }} me-1"></i>
                                                {{ $ticket->unreadMessagesCountForSchool() > 0 ? 'View' : 'View' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state py-5">
                                                <div class="empty-state-container bg-primary bg-opacity-10 py-5 px-4 rounded-4">
                                                    <div class="empty-state-icon mb-4 bg-white text-primary d-flex align-items-center justify-content-center rounded-circle mx-auto shadow-sm" style="width: 100px; height: 100px;">
                                                        <i class="fas fa-inbox fa-3x"></i>
                                                    </div>
                                                    <h4 class="text-primary mb-3">No Support Tickets Found</h4>
                                                    <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">Create a new support ticket to get help from our system administrators. We typically respond within 24 hours to all support requests.</p>
                                                    <a href="{{ route('teacher-admin.support.create') }}" class="btn btn-primary px-4 py-2 shadow-sm">
                                                        <i class="fas fa-plus-circle me-2"></i>
                                                        Create Support Ticket
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4 mb-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modern Card Styling */
    .ticket-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .ticket-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .ticket-card.has-unread {
        border-left: 4px solid #0d6efd;
    }

    .ticket-card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Table Styling */
    .modern-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
        font-size: 0.9rem;
    }

    .ticket-row {
        transition: all 0.2s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .ticket-row:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }

    .ticket-row.has-unread {
        background-color: rgba(13, 110, 253, 0.05);
        border-left: 4px solid #0d6efd;
    }

    /* Activity Indicator */
    .activity-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #adb5bd;
        display: inline-block;
        position: relative;
        flex-shrink: 0;
        vertical-align: middle;
    }

    .activity-indicator.recent {
        background-color: #20c997;
        box-shadow: 0 0 0 4px rgba(32, 201, 151, 0.2);
        animation: activity-pulse 2s infinite;
    }

    /* Container for activity indicator to ensure proper positioning */
    .position-relative .activity-indicator {
        top: 0;
        left: 0;
    }

    @keyframes activity-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(32, 201, 151, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(32, 201, 151, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(32, 201, 151, 0);
        }
    }

    /* Notification Pulse */
    .notification-pulse {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: rgba(220, 53, 69, 0.3);
        animation: pulse 2s infinite;
        z-index: -1;
    }

    @keyframes pulse {
        0% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
        70% {
            transform: translate(-50%, -50%) scale(1.5);
            opacity: 0;
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0;
        }
    }

    /* Header Icon Container */
    .header-icon-container {
        box-shadow: 0 0.25rem 0.5rem rgba(13, 110, 253, 0.15);
    }

    /* Empty State Styling */
    .empty-state-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .empty-state-icon {
        transition: all 0.3s ease;
    }

    .empty-state-icon:hover {
        transform: scale(1.05);
    }

    /* Button Styling */
    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    /* Pagination Styling */
    .pagination {
        border-radius: 50px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .page-item:first-child .page-link {
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
    }

    .page-item:last-child .page-link {
        border-top-right-radius: 50px;
        border-bottom-right-radius: 50px;
    }

    .page-link {
        border: none;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background-color: #e9ecef;
        transform: scale(1.05);
        z-index: 3;
    }

    .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
        font-weight: bold;
    }
</style>
@endpush
