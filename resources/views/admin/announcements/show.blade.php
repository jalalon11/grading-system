@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">
                <i class="fas fa-bullhorn text-primary me-2"></i>
                Announcement Details
            </h2>
            <p class="text-muted">View the details of this announcement.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group">
                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $announcement->title }}</h5>
                    <span class="badge {{ $announcement->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $announcement->is_active ? 'Active' : 'Hidden' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="announcement-content mb-4" id="formattedContent">
                        <!-- Content will be formatted by JavaScript -->
                    </div>

                    <div class="announcement-meta text-muted">
                        <small>
                            <i class="fas fa-calendar-alt me-1"></i> Created: {{ $announcement->created_at->format('F d, Y h:i A') }}
                        </small>
                        <br>
                        <small>
                            <i class="fas fa-clock me-1"></i> Last Updated: {{ $announcement->updated_at->format('F d, Y h:i A') }}
                        </small>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('admin.announcements.toggle-status', $announcement) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $announcement->is_active ? 'warning' : 'success' }}">
                                <i class="fas fa-{{ $announcement->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                                {{ $announcement->is_active ? 'Hide' : 'Show' }} Announcement
                            </button>
                        </form>

                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Delete Announcement
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i>Preview</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">This is how the announcement will appear on the login page:</p>

                    <div class="announcement-preview p-4 border rounded bg-light">
                        <div class="modal-header border-bottom pb-3">
                            <h5 class="modal-title">{{ $announcement->title }}</h5>
                            <button type="button" class="btn-close" disabled></button>
                        </div>
                        <div class="modal-body py-3">
                            <div id="previewContent" class="announcement-content">
                                <!-- Content will be formatted by JavaScript -->
                            </div>
                        </div>
                        <div class="modal-footer border-top pt-3">
                            <button type="button" class="btn btn-secondary" disabled>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    /* Announcement Content Styling */
    .announcement-content {
        white-space: pre-line;
    }

    .announcement-content p {
        margin-bottom: 1rem;
    }

    .announcement-content h4 {
        margin-bottom: 1.25rem;
        color: #2980b9;
    }

    .announcement-content h5 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: #2980b9;
        font-weight: 600;
    }

    .announcement-content ul,
    .announcement-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
    }

    .announcement-content li {
        margin-bottom: 0.5rem;
    }

    .announcement-content strong {
        font-weight: 600;
    }

    .announcement-content .d-flex {
        margin-bottom: 0.5rem;
    }

    .announcement-content .fas {
        font-size: 0.9rem;
    }

    .announcement-content ul {
        margin-top: 0.5rem;
    }

    /* Special styling for checkmark lists */
    .announcement-content .ps-3 {
        padding-left: 0.75rem !important;
    }

    .announcement-content .ps-3 i {
        width: 16px;
        text-align: center;
    }

    /* Link styling */
    .announcement-content a {
        color: #3498db;
        text-decoration: none;
        word-break: break-word;
    }

    .announcement-content a:hover {
        text-decoration: underline;
        color: #2980b9;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format the announcement content
        const content = `{{ $announcement->content }}`;
        document.getElementById('formattedContent').innerHTML = formatAnnouncementContent(content);
        document.getElementById('previewContent').innerHTML = formatAnnouncementContent(content);

        // Delete confirmation
        const deleteForm = document.querySelector('.delete-form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this announcement?')) {
                    this.submit();
                }
            });
        }

        function formatAnnouncementContent(content) {
            if (!content) return '';

            // First, handle any HTML entities to prevent double-encoding
            let formatted = content.replace(/&/g, '&amp;')
                                  .replace(/</g, '&lt;')
                                  .replace(/>/g, '&gt;');

            // Handle hashtag announcements at the beginning of lines
            formatted = formatted.replace(/(^|\n)(#+)\s*ANNOUNCEMENT:\s*([^\n]+)/gi,
                '$1<h4 class="text-primary fw-bold"><i class="fas fa-bullhorn me-2"></i>$3</h4>');

            // Replace line breaks with <br> tags
            formatted = formatted.replace(/\n/g, '<br>');

            // Format bullet points and check marks
            formatted = formatted.replace(/(^|<br>)•\s+([^<]+)/g, '$1<li>$2</li>');
            formatted = formatted.replace(/(^|<br>)✓\s+([^<]+)/g,
                '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-check text-success me-2"></i><div>$2</div></div>');
            formatted = formatted.replace(/(^|<br>)✅\s+([^<]+)/g,
                '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-check-square text-success me-2"></i><div>$2</div></div>');

            // Format special icons
            formatted = formatted.replace(/(^|<br>)📅\s+([^<]+)/g,
                '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-calendar-alt text-primary me-2"></i><div>$2</div></div>');
            formatted = formatted.replace(/(^|<br>)📣\s+([^<]+)/g,
                '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-bullhorn text-warning me-2"></i><div>$2</div></div>');
            formatted = formatted.replace(/(^|<br>)💡\s+([^<]+)/g,
                '$1<div class="d-flex align-items-start mb-1 ps-3"><i class="fas fa-lightbulb text-warning me-2"></i><div>$2</div></div>');

            // Wrap consecutive <li> elements in <ul> tags
            if (formatted.includes('<li>')) {
                // Find all sequences of <li> elements
                formatted = formatted.replace(/(<li>[^<]+<\/li>)+/g, function(match) {
                    return '<ul class="mb-3">' + match + '</ul>';
                });
            }

            // Add emphasis to text between asterisks
            formatted = formatted.replace(/\*\*([^*<]+)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/\*([^*<]+)\*/g, '<em>$1</em>');

            // Format section headers (###)
            formatted = formatted.replace(/(^|<br>)###\s+([^<\n]+)/g,
                '$1<h5 class="mt-3 mb-2 fw-bold text-primary">$2</h5>');

            // Handle emojis in text
            formatted = formatted.replace(/🎉/g, '<i class="fas fa-party-horn text-primary"></i>');
            formatted = formatted.replace(/🚀/g, '<i class="fas fa-rocket text-primary"></i>');

            // Convert URLs to clickable links
            // First, handle URLs with protocol (http://, https://)
            formatted = formatted.replace(
                /(https?:\/\/[^\s<]+)(?![^<>]*>|[^<>]*<\/a>)/gi,
                '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
            );

            // Then, handle URLs without protocol (www.example.com)
            formatted = formatted.replace(
                /(?<![\/"'>])\b(www\.[^\s<]+\.[^\s<]+)(?![^<>]*>|[^<>]*<\/a>)/gi,
                '<a href="http://$1" target="_blank" rel="noopener noreferrer">$1</a>'
            );

            return formatted;
        }
    });
</script>
@endpush


