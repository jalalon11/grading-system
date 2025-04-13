@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1">Learning Resource Materials</h4>
                            <p class="mb-0 opacity-75">Access educational resources and teaching materials</p>
                        </div>
                        <div class="avatar bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-book-reader fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resource Search and Filters -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="resourceSearch" placeholder="Search for learning resources...">
                <button class="btn btn-primary" type="button">Search</button>
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select shadow-sm" id="quarterFilter">
                <option value="all" {{ $quarterFilter == 'all' ? 'selected' : '' }}>All Quarters</option>
                <option value="1" {{ $quarterFilter == '1' ? 'selected' : '' }}>1st Quarter</option>
                <option value="2" {{ $quarterFilter == '2' ? 'selected' : '' }}>2nd Quarter</option>
                <option value="3" {{ $quarterFilter == '3' ? 'selected' : '' }}>3rd Quarter</option>
                <option value="4" {{ $quarterFilter == '4' ? 'selected' : '' }}>4th Quarter</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select shadow-sm" id="categoryFilter">
                <option value="all" selected>All Categories</option>
                @if(isset($categories) && count($categories) > 0)
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->resources_count }})</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <!-- Quarter Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt text-primary me-2"></i> Resources by Quarter</h6>
                        <span class="badge bg-primary rounded-pill">{{ $resources->count() }} Total Resources</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="quarter-stat text-center px-3 py-2 rounded-3 {{ $quarterFilter == '1' ? 'bg-info bg-opacity-10' : '' }}" onclick="document.getElementById('quarterFilter').value='1'; document.getElementById('quarterFilter').dispatchEvent(new Event('change'));">
                            <h5 class="mb-0 fw-bold">{{ $resourcesByQuarter[1] ?? 0 }}</h5>
                            <span class="small text-muted">1st Quarter</span>
                        </div>
                        <div class="quarter-stat text-center px-3 py-2 rounded-3 {{ $quarterFilter == '2' ? 'bg-info bg-opacity-10' : '' }}" onclick="document.getElementById('quarterFilter').value='2'; document.getElementById('quarterFilter').dispatchEvent(new Event('change'));">
                            <h5 class="mb-0 fw-bold">{{ $resourcesByQuarter[2] ?? 0 }}</h5>
                            <span class="small text-muted">2nd Quarter</span>
                        </div>
                        <div class="quarter-stat text-center px-3 py-2 rounded-3 {{ $quarterFilter == '3' ? 'bg-info bg-opacity-10' : '' }}" onclick="document.getElementById('quarterFilter').value='3'; document.getElementById('quarterFilter').dispatchEvent(new Event('change'));">
                            <h5 class="mb-0 fw-bold">{{ $resourcesByQuarter[3] ?? 0 }}</h5>
                            <span class="small text-muted">3rd Quarter</span>
                        </div>
                        <div class="quarter-stat text-center px-3 py-2 rounded-3 {{ $quarterFilter == '4' ? 'bg-info bg-opacity-10' : '' }}" onclick="document.getElementById('quarterFilter').value='4'; document.getElementById('quarterFilter').dispatchEvent(new Event('change'));">
                            <h5 class="mb-0 fw-bold">{{ $resourcesByQuarter[4] ?? 0 }}</h5>
                            <span class="small text-muted">4th Quarter</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resource Materials Grid with Filters -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                    <div class="d-flex align-items-center">
                        <span class="me-3 fw-bold text-primary"><i class="fas fa-link me-2"></i> Available Resources</span>
                        <!-- Category Pills -->
                        <div class="d-none d-md-flex category-pills">
                            <button class="btn btn-sm btn-outline-primary rounded-pill active me-2" data-category="all">All</button>
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories->take(5) as $category)
                                    <button class="btn btn-sm btn-outline-{{ $category->color }} rounded-pill me-2" data-category="{{ $category->id }}">
                                        <i class="fas fa-{{ $category->icon }} me-1"></i> {{ $category->name }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <select class="form-select form-select-sm me-2" id="resourceSort" style="width: auto;">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="name">Name (A-Z)</option>
                            <option value="popular">Most Popular</option>
                        </select>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary active" data-bs-toggle="tooltip" title="Grid View">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if(isset($resources) && count($resources) > 0)
                        <div class="row g-4" id="resourcesGrid">
                            @foreach($resources as $resource)
                                <div class="col-md-6 col-lg-4" data-category="{{ $resource->category_id }}" data-quarter="{{ $resource->quarter }}">
                                    <div class="resource-card h-100 border rounded-3 overflow-hidden position-relative">
                                        <div class="resource-header bg-{{ $resource->category_color }} bg-opacity-10 p-3 border-bottom d-flex align-items-center">
                                            <div class="resource-icon rounded-circle bg-white shadow-sm p-2 me-2">
                                                <i class="fas fa-{{ $resource->icon ?? 'file-alt' }} text-{{ $resource->category_color }}"></i>
                                            </div>
                                            <div class="resource-title">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="badge bg-{{ $resource->category_color }} bg-opacity-15 text-white small">
                                                        {{ $resource->category_name }}
                                                    </span>
                                                    @if($resource->quarter)
                                                        <span class="badge bg-info bg-opacity-15 text-white small">
                                                            <i class="fas fa-calendar-alt me-1"></i> {{ $resource->quarter_name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <h5 class="fw-bold mb-0">{{ $resource->title }}</h5>
                                            </div>
                                        </div>
                                        <div class="resource-content p-3">
                                            <div class="resource-description mb-3" style="min-height: 4.5em;">
                                                <p class="text-muted mb-0">{{ Str::limit($resource->description, 120) }}</p>
                                            </div>
                                            <div class="resource-meta d-flex justify-content-between align-items-center text-muted small mb-3">
                                                <span><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($resource->created_at)->format('M d, Y') }}</span>
                                                {{-- <span><i class="fas fa-share-alt me-1"></i> Share</span> --}}
                                            </div>
                                            {{-- <div class="d-grid">
                                                <a href="{{ $resource->url }}" target="_blank" class="btn btn-primary">
                                                    <i class="fas fa-external-link-alt me-2"></i> Access Resource
                                                </a>
                                            </div> --}}
                                        </div>
                                        <div class="resource-overlay">
                                            <div class="resource-overlay-content text-center p-4">
                                                <h5 class="text-white mb-3">{{ $resource->title }}</h5>
                                                <p class="text-white-50 mb-4">{{ Str::limit($resource->description, 150) }}</p>
                                                <a href="{{ $resource->url }}" target="_blank" class="btn btn-light rounded-pill px-4">
                                                    <i class="fas fa-external-link-alt me-2"></i> Open Resource
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state mb-4">
                                <i class="fas fa-books fa-4x text-muted opacity-25 mb-3"></i>
                                <h5 class="text-muted">No Resource Materials Available</h5>
                                <p class="text-muted col-md-6 mx-auto">Resource materials will be added by administrators soon. Check back later for educational resources.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('styles')
<style>
    /* Resource Cards */
    .resource-card {
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
    }

    .resource-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .resource-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.9));
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .resource-card:hover .resource-overlay {
        opacity: 1;
        visibility: visible;
    }

    /* Category Cards */
    .category-card {
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .category-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Category Pills */
    .category-pills {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 5px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-pills::-webkit-scrollbar {
        display: none;
    }

    /* Empty state */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Quarter stats */
    .quarter-stat {
        flex: 1;
        margin: 0 5px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .quarter-stat:hover {
        background-color: rgba(13, 110, 253, 0.05);
        cursor: pointer;
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Filters
        const categoryFilter = document.getElementById('categoryFilter');
        const quarterFilter = document.getElementById('quarterFilter');
        const categoryButtons = document.querySelectorAll('.category-pills button');

        // Category filter change
        categoryFilter.addEventListener('change', function() {
            filterResources(this.value);
        });

        // Quarter filter change
        quarterFilter.addEventListener('change', function() {
            // If URL has category parameter, preserve it
            const urlParams = new URLSearchParams(window.location.search);
            const categoryParam = urlParams.get('category');

            let url = '{{ route('teacher.resources.index') }}?quarter=' + this.value;
            if (categoryParam) {
                url += '&category=' + categoryParam;
            }

            window.location = url;
        });

        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                // Filter resources
                const category = this.getAttribute('data-category');
                filterResources(category);

                // Update select as well
                if (categoryFilter) {
                    categoryFilter.value = category;
                }
            });
        });

        // Check for category parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        const categoryParam = urlParams.get('category');

        if (categoryParam) {
            // Set the dropdown value
            if (categoryFilter) {
                categoryFilter.value = categoryParam;
            }

            // Set the active button
            categoryButtons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.getAttribute('data-category') === categoryParam) {
                    btn.classList.add('active');
                }
            });

            // Filter the resources
            filterResources(categoryParam);
        }

        // Search functionality
        const searchInput = document.getElementById('resourceSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const resources = document.querySelectorAll('#resourcesGrid > div');

                resources.forEach(resource => {
                    const title = resource.querySelector('.resource-title h5').textContent.toLowerCase();
                    const description = resource.querySelector('.resource-description p').textContent.toLowerCase();

                    if (title.includes(searchTerm) || description.includes(searchTerm)) {
                        resource.style.display = '';
                    } else {
                        resource.style.display = 'none';
                    }
                });
            });
        }

        // Sort functionality
        const sortSelect = document.getElementById('resourceSort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                sortResources(this.value);
            });
        }
    });

    // Filter resources by category
    function filterResources(category) {
        const resources = document.querySelectorAll('#resourcesGrid > div');
        const quarterFilter = document.getElementById('quarterFilter').value;

        resources.forEach(resource => {
            let showByCategory = category === 'all' || category === 'null' || resource.getAttribute('data-category') === category;
            let showByQuarter = quarterFilter === 'all' || resource.getAttribute('data-quarter') === quarterFilter ||
                               (quarterFilter === 'null' && !resource.getAttribute('data-quarter'));

            if (showByCategory && showByQuarter) {
                resource.style.display = '';
            } else {
                resource.style.display = 'none';
            }
        });
    }

    // Filter by category from category cards
    function filterByCategory(categoryId) {
        // Update select
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.value = categoryId;
        }

        // Update active button
        const categoryButtons = document.querySelectorAll('.category-pills button');
        categoryButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-category') == categoryId) {
                btn.classList.add('active');
            }
        });

        // Filter resources
        filterResources(categoryId);

        // Scroll to resources
        document.querySelector('#resourcesGrid').scrollIntoView({ behavior: 'smooth' });
    }

    // Sort resources
    function sortResources(sortBy) {
        const resourcesGrid = document.getElementById('resourcesGrid');
        const resources = Array.from(resourcesGrid.children);

        resources.sort((a, b) => {
            if (sortBy === 'newest') {
                // Compare by date (assuming data-date attribute)
                const dateA = new Date(a.querySelector('.resource-meta span:first-child').textContent);
                const dateB = new Date(b.querySelector('.resource-meta span:first-child').textContent);
                return dateB - dateA;
            } else if (sortBy === 'oldest') {
                const dateA = new Date(a.querySelector('.resource-meta span:first-child').textContent);
                const dateB = new Date(b.querySelector('.resource-meta span:first-child').textContent);
                return dateA - dateB;
            } else if (sortBy === 'name') {
                const titleA = a.querySelector('.resource-title h5').textContent;
                const titleB = b.querySelector('.resource-title h5').textContent;
                return titleA.localeCompare(titleB);
            } else if (sortBy === 'popular') {
                // This would require a data attribute for popularity or clicks
                return 0;
            }
            return 0;
        });

        // Re-append sorted resources
        resources.forEach(resource => {
            resourcesGrid.appendChild(resource);
        });
    }
</script>
@endpush