@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-headset text-primary me-2"></i>
                                Support Tickets
                            </h5>
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
                    <div class="d-md-none">
                        @forelse($tickets as $ticket)
                            <div class="card mb-3 {{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'border-primary' : 'border-light' }} shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="ticket-icon me-2 d-flex align-items-center justify-content-center rounded-circle {{ $ticket->status == 'closed' ? 'bg-secondary' : ($ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning' : 'bg-info')) }}" style="width: 32px; height: 32px; color: white;">
                                                <i class="fas fa-headset"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">#{{ $ticket->id }}</div>
                                            </div>
                                            @if($ticket->unreadMessagesCount(Auth::id()) > 0)
                                                <span class="badge bg-danger rounded-pill ms-2 animate__animated animate__pulse animate__infinite">{{ $ticket->unreadMessagesCount(Auth::id()) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="badge {{ $ticket->status == 'open' ? 'bg-success' : ($ticket->status == 'in_progress' ? 'bg-primary' : 'bg-secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                            <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning text-dark' : 'bg-info') }} ms-1">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="fw-medium text-truncate">{{ $ticket->subject }}</div>
                                        <div class="small text-muted d-flex align-items-center">
                                            <span class="me-2">{{ $ticket->school->name }}</span>
                                            <span>•</span>
                                            <span class="ms-2">{{ $ticket->user->name }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'N/A' }}
                                        </div>
                                        <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm {{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'btn-primary' : 'btn-outline-primary' }}">
                                            <i class="fas {{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'fa-envelope' : 'fa-eye' }} me-1"></i>
                                            {{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'View New' : 'View' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-5 text-center">
                                    <div class="empty-state-icon mb-3 bg-light text-primary d-flex align-items-center justify-content-center rounded-circle mx-auto" style="width: 80px; height: 80px;">
                                        <i class="fas fa-inbox fa-2x"></i>
                                    </div>
                                    <h5 class="text-muted">No Support Tickets Found</h5>
                                    <p class="text-muted">Support tickets from teacher admins will appear here.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop Table View (visible on medium screens and up) -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Ticket</th>
                                    <th>School</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Last Activity</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr class="{{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'table-active' : '' }}">
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="ticket-icon me-3 d-flex align-items-center justify-content-center rounded-circle {{ $ticket->status == 'closed' ? 'bg-secondary' : ($ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning' : 'bg-info')) }}" style="width: 40px; height: 40px; color: white;">
                                                    <i class="fas fa-headset"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">#{{ $ticket->id }}</div>
                                                    <div class="small text-muted">{{ $ticket->user->name }}</div>
                                                </div>
                                                @if($ticket->unreadMessagesCount(Auth::id()) > 0)
                                                    <span class="badge bg-danger rounded-pill ms-2 animate__animated animate__pulse animate__infinite">{{ $ticket->unreadMessagesCount(Auth::id()) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($ticket->school->logo_path)
                                                    <img src="{{ $ticket->school->logo_url }}" alt="{{ $ticket->school->name }}" class="me-2 rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                                                @else
                                                    <div class="me-2 rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; color: white;">
                                                        <i class="fas fa-school" style="font-size: 12px;"></i>
                                                    </div>
                                                @endif
                                                <span>{{ $ticket->school->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $ticket->subject }}">
                                                {{ $ticket->subject }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $ticket->status == 'open' ? 'bg-success' : ($ticket->status == 'in_progress' ? 'bg-primary' : 'bg-secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $ticket->priority == 'high' ? 'bg-danger' : ($ticket->priority == 'medium' ? 'bg-warning text-dark' : 'bg-info') }}">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-muted me-2 small"></i>
                                                <span>{{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm {{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'btn-primary' : 'btn-outline-primary' }}">
                                                <i class="fas {{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'fa-envelope' : 'fa-eye' }} me-1"></i>
                                                {{ $ticket->unreadMessagesCount(Auth::id()) > 0 ? 'View New Messages' : 'View Ticket' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center py-5">
                                                <div class="empty-state-icon mb-3 bg-light text-primary d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-inbox fa-2x"></i>
                                                </div>
                                                <h5 class="text-muted">No Support Tickets Found</h5>
                                                <p class="text-muted">Support tickets from teacher admins will appear here.</p>
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
