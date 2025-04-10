@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">
                <i class="fas fa-bullhorn text-primary me-2"></i>
                {{ isset($announcement) ? 'Edit Announcement' : 'Create Announcement' }}
            </h2>
            <p class="text-muted">
                {{ isset($announcement) ? 'Update the details of an existing announcement.' : 'Create a new announcement to display on the login page.' }}
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}" method="POST">
                @csrf
                @if(isset($announcement))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="title" class="form-label fw-medium">Announcement Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                        value="{{ old('title', isset($announcement) ? $announcement->title : '') }}" required>
                    @error('title')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-medium">Announcement Content <span class="text-danger">*</span></label>
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="previewBtn">
                            <i class="fas fa-eye me-1"></i> Preview
                        </button>
                    </div>
                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                        rows="10" required>{{ old('content', isset($announcement) ? $announcement->content : '') }}</textarea>
                    @error('content')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <div class="form-text mt-2">
                        <p class="mb-1"><strong>Formatting Tips:</strong></p>
                        <ul class="mb-0 ps-3">
                            <li>Use line breaks to separate paragraphs</li>
                            <li>Use emojis like 📅, 📣, 💡, ✓, ✅ at the beginning of lines for special formatting</li>
                            <li>Use • for bullet points</li>
                            <li>Use *italic* or **bold** for emphasis</li>
                            <li>Use ### for section headers</li>
                            <li>Use ### ANNOUNCEMENT: for announcement headers</li>
                            <li>URLs (like https://example.com or www.example.com) will automatically become clickable links</li>
                        </ul>
                    </div>
                </div>

                <!-- Preview Modal -->
                <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="previewModalLabel">
                                    <i class="fas fa-eye me-2"></i> <span id="previewTitle">Announcement Preview</span>
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="previewContent" class="announcement-content"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', isset($announcement) && $announcement->is_active ? 'checked' : '') }}>
                        <label class="form-check-label" for="is_active">
                            Show this announcement on the login page
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Announcements
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ isset($announcement) ? 'Update' : 'Save' }} Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    /* Announcement Content Styling for Preview */
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
        // Preview functionality
        const previewBtn = document.getElementById('previewBtn');
        const contentTextarea = document.getElementById('content');
        const titleInput = document.getElementById('title');
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewTitle = document.getElementById('previewTitle');
        const previewContent = document.getElementById('previewContent');

        previewBtn.addEventListener('click', function() {
            const title = titleInput.value.trim() || 'Announcement Preview';
            const content = contentTextarea.value;

            previewTitle.textContent = title;
            previewContent.innerHTML = formatAnnouncementContent(content);

            previewModal.show();
        });

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