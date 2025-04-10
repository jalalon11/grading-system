@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="fas fa-bullhorn text-primary me-2"></i> Announcements</h2>
            <p class="text-muted">Manage system-wide announcements that appear on the login page.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>New Announcement
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                        <tr>
                            <td class="fw-medium">{{ $announcement->title }}</td>
                            <td>{{ Str::limit($announcement->content, 100) }}</td>
                            <td>
                                @if($announcement->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-secondary">Hidden</span>
                                @endif
                            </td>
                            <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.announcements.toggle-status', $announcement) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $announcement->is_active ? 'warning' : 'success' }}">
                                            <i class="fas fa-{{ $announcement->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-bullhorn text-muted mb-2" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-2">No announcements found</p>
                                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Create Announcement
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm delete
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
