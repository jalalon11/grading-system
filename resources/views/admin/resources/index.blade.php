@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Enhanced Page Header with Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-10">
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1">Learning Resource Management</h4>
                            <p class="mb-0 opacity-75">Organize and distribute educational resources to teachers</p>
                        </div>
                        <div class="d-flex">
                            <button type="button" class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-folder-plus me-1"></i> New Category
                            </button>
                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                                <i class="fas fa-plus-circle me-1"></i> New Resource
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 mt-2 me-2 opacity-15">
                    <i class="fas fa-file-alt fa-4x text-primary"></i>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="fas fa-file-alt fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 text-uppercase small fw-bold">Total Resources</h6>
                                <h2 class="fw-bold mb-0 counter-value">{{ $totalResources ?? 0 }}</h2>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-sm btn-outline-primary rounded-pill" type="button" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                                <i class="fas fa-plus-circle me-1"></i> Add New
                            </button>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <i class="fas fa-chart-line me-1"></i> Resource Management
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 mt-2 me-2 opacity-15">
                    <i class="fas fa-tags fa-4x text-success"></i>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="fas fa-tags fa-lg text-success"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 text-uppercase small fw-bold">Resource Categories</h6>
                                <h2 class="fw-bold mb-0 counter-value">{{ $totalCategories ?? 0 }}</h2>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-sm btn-outline-success rounded-pill" type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus-circle me-1"></i> Add New
                            </button>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                <i class="fas fa-folder me-1"></i> Category Management
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 mt-2 me-2 opacity-15">
                    <i class="fas fa-award fa-4x text-info"></i>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                <i class="fas fa-award fa-lg text-info"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0 text-uppercase small fw-bold">Most Accessed Resource</h6>
                                <h2 class="fw-bold mb-0 counter-value">{{ $mostUsedCount ?? 0 }}</h2>
                                <p class="text-muted small mt-1">{{ Str::limit($mostUsedTitle ?? 'None', 28) }}</p>
                            </div>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button class="btn btn-sm btn-outline-info rounded-pill" type="button" id="viewResourceStats">
                                <i class="fas fa-chart-bar me-1"></i> Analytics
                            </button>
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                <i class="fas fa-eye me-1"></i> Resource Analytics
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Tabs Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-0 border-bottom-0">
                    <ul class="nav nav-tabs nav-tabs-modern border-0" id="resourceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-4 py-3" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button" role="tab" aria-controls="resources" aria-selected="true">
                                <i class="fas fa-link me-2"></i> Resource Links
                                <span class="badge rounded-pill bg-primary ms-2">{{ $totalResources ?? 0 }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                                <i class="fas fa-tags me-2"></i> Categories
                                <span class="badge rounded-pill bg-success ms-2">{{ $totalCategories ?? 0 }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content p-4" id="resourceTabsContent">
                        <!-- Resources Tab -->
                        <div class="tab-pane fade show active" id="resources" role="tabpanel" aria-labelledby="resources-tab">
                            <!-- Search & Filter Controls -->
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <div class="input-group shadow-sm">
                                        <span class="input-group-text bg-white border-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-0 py-2" id="resourceSearchInput" placeholder="Search resources...">
                                        <button class="btn btn-primary" type="button">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select shadow-sm" id="resourceQuarterFilter" onchange="window.location = '{{ route('admin.resources.index') }}?quarter=' + this.value">
                                        <option value="all" {{ $quarterFilter == 'all' ? 'selected' : '' }}>All Quarters</option>
                                        <option value="1" {{ $quarterFilter == '1' ? 'selected' : '' }}>1st Quarter</option>
                                        <option value="2" {{ $quarterFilter == '2' ? 'selected' : '' }}>2nd Quarter</option>
                                        <option value="3" {{ $quarterFilter == '3' ? 'selected' : '' }}>3rd Quarter</option>
                                        <option value="4" {{ $quarterFilter == '4' ? 'selected' : '' }}>4th Quarter</option>
                                        <option value="null" {{ $quarterFilter == 'null' ? 'selected' : '' }}>Unassigned</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select shadow-sm" id="resourceCategoryFilter">
                                        <option value="all">All Categories</option>
                                        @if(isset($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select shadow-sm" id="resourceStatusFilter">
                                        <option value="all">All Status</option>
                                        <option value="active">Active Only</option>
                                        <option value="inactive">Inactive Only</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Resources Table with Enhanced Design -->
                            <div class="table-responsive resource-table">
                                <table class="table table-hover align-middle border-0" id="resourcesTable">
                                    <thead class="table-light text-uppercase text-secondary small">
                                        <tr>
                                            <th scope="col" class="ps-4" style="width: 30%;">Resource</th>
                                            <th scope="col" style="width: 12%;">Category</th>
                                            <th scope="col" style="width: 10%;">Quarter</th>
                                            <th scope="col" style="width: 18%;">URL</th>
                                            <th scope="col" style="width: 10%;">Status</th>
                                            <th scope="col" style="width: 10%;">Analytics</th>
                                            <th scope="col" class="text-end pe-4" style="width: 10%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($resources) && count($resources) > 0)
                                            @foreach($resources as $resource)
                                                <tr class="resource-row" data-id="{{ $resource->id }}" data-category="{{ $resource->category_id }}" data-quarter="{{ $resource->quarter }}" data-status="{{ $resource->is_active ? 'active' : 'inactive' }}">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <div class="resource-icon rounded-circle bg-{{ $resource->category_color ?? 'primary' }} bg-opacity-10 p-2 me-3 shadow-sm">
                                                                <i class="fas fa-{{ $resource->icon ?? 'file-alt' }} text-{{ $resource->category_color ?? 'primary' }}"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 fw-bold">{{ $resource->title }}</h6>
                                                                <p class="text-muted small mb-0">{{ Str::limit($resource->description, 60) }}</p>
                                                                <span class="badge bg-light text-secondary small">
                                                                    <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($resource->created_at)->format('M d, Y') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $resource->category_color ?? 'light' }} bg-opacity-10 text-{{ $resource->category_color ?? 'dark' }} px-3 py-2 rounded-pill">
                                                            <i class="fas fa-{{ $resource->icon ?? 'folder' }} me-1"></i> {{ $resource->category_name ?? 'Uncategorized' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($resource->quarter)
                                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                                                <i class="fas fa-calendar-alt me-1"></i> {{ $resource->quarter_name }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                                                <i class="fas fa-question-circle me-1"></i> Unassigned
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="url-container text-truncate" style="max-width: 200px;">
                                                                <a href="{{ $resource->url }}" target="_blank" class="text-decoration-none link-primary">
                                                                    {{ $resource->url }}
                                                                </a>
                                                            </div>
                                                            <button class="btn btn-sm btn-link copy-link" data-url="{{ $resource->url }}" data-bs-toggle="tooltip" title="Copy URL">
                                                                <i class="fas fa-copy text-muted"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch ms-2">
                                                            <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                                                data-id="{{ $resource->id }}"
                                                                {{ $resource->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label small {{ $resource->is_active ? 'text-success' : 'text-danger' }}">
                                                                {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2 text-center">
                                                                <span class="d-block fw-bold">{{ $resource->click_count ?? 0 }}</span>
                                                                <span class="small text-muted">Clicks</span>
                                                            </div>
                                                            <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                                                                @php
                                                                    $percentage = $mostUsedCount > 0 ? min(100, ($resource->click_count / $mostUsedCount) * 100) : 0;
                                                                @endphp
                                                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-outline-primary edit-resource-btn"
                                                                data-id="{{ $resource->id }}"
                                                                data-title="{{ $resource->title }}"
                                                                data-description="{{ $resource->description }}"
                                                                data-url="{{ $resource->url }}"
                                                                data-category="{{ $resource->category_id }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editResourceModal">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-resource-btn"
                                                                data-id="{{ $resource->id }}"
                                                                data-title="{{ $resource->title }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <div class="py-5">
                                                        <div class="mb-3">
                                                            <i class="fas fa-file-alt text-muted fa-4x mb-3 opacity-25"></i>
                                                        </div>
                                                        <h5 class="text-muted mb-3">No Resources Available</h5>
                                                        <p class="text-muted col-md-6 mx-auto mb-4">Start by adding educational resources for teachers to access.</p>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                                                            <i class="fas fa-plus-circle me-2"></i> Add Your First Resource
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if(isset($resources) && $resources instanceof \Illuminate\Pagination\LengthAwarePaginator && $resources->hasPages())
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $resources->links() }}
                                </div>
                            @endif
                        </div>

                        <!-- Categories Tab -->
                        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 fw-bold">Manage Categories</h5>
                                    <span class="badge bg-success ms-2 rounded-pill">{{ $totalCategories ?? 0 }} Categories</span>
                                </div>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus-circle me-2"></i> Add New Category
                                </button>
                            </div>

                            <!-- Categories Cards Display -->
                            @if(isset($categories) && count($categories) > 0)
                                <div class="row g-4 mb-4">
                                    @foreach($categories as $category)
                                        <div class="col-lg-3 col-md-6">
                                            <div class="card h-100 border-0 shadow-sm category-card position-relative">
                                                <div class="card-status-indicator position-absolute top-0 end-0 m-2">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input category-status-toggle" type="checkbox" role="switch"
                                                            data-id="{{ $category->id }}"
                                                            {{ $category->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="category-header bg-{{ $category->color }} bg-opacity-15 p-4 text-center border-bottom">
                                                        <div class="avatar mx-auto mb-3 rounded-circle bg-white shadow p-3" style="width: 80px; height: 80px;">
                                                            <i class="fas fa-{{ $category->icon }} fa-2x text-{{ $category->color }}"></i>
                                                        </div>
                                                        <h5 class="fw-bold mb-1">{{ $category->name }}</h5>
                                                        <span class="badge bg-{{ $category->color }} px-3 py-2 rounded-pill">
                                                            {{ $category->resources_count ?? 0 }} Resources
                                                        </span>
                                                    </div>
                                                    <div class="category-details p-4">
                                                        <div class="mb-3">
                                                            <div class="text-muted small mb-1 fw-bold">DESCRIPTION</div>
                                                            <p class="mb-0">{{ Str::limit($category->description, 80) ?: 'No description provided.' }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="text-muted small mb-1 fw-bold">ICON</div>
                                                            <code class="bg-light px-2 py-1 rounded">{{ $category->icon }}</code>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="text-muted small mb-1 fw-bold">COLOR</div>
                                                            <div class="d-flex align-items-center">
                                                                <span class="color-swatch bg-{{ $category->color }} rounded me-2" style="width: 20px; height: 20px;"></span>
                                                                <span class="badge rounded-pill bg-{{ $category->color }}">{{ $category->color }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-4">
                                                            <button type="button" class="btn btn-sm btn-outline-{{ $category->color }} edit-category-btn"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}"
                                                                data-description="{{ $category->description }}"
                                                                data-icon="{{ $category->icon }}"
                                                                data-color="{{ $category->color }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editCategoryModal">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger delete-category-btn"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}"
                                                                data-resource-count="{{ $category->resources_count ?? 0 }}">
                                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-tags text-muted fa-4x opacity-25"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">No Categories Available</h5>
                                    <p class="text-muted col-md-6 mx-auto mb-4">Categories help organize your learning resources. Create your first category to get started.</p>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus-circle me-2"></i> Create First Category
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="addResourceForm" action="{{ route('admin.resources.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addResourceModalLabel"><i class="fas fa-plus-circle me-2"></i> Add New Resource</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="Enter resource title">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quarter" class="form-label fw-bold">Quarter <span class="text-danger">*</span></label>
                        <select class="form-select" id="quarter" name="quarter" required>
                            <option value="">Select Quarter</option>
                            <option value="1">1st Quarter</option>
                            <option value="2">2nd Quarter</option>
                            <option value="3">3rd Quarter</option>
                            <option value="4">4th Quarter</option>
                        </select>
                        <div class="form-text">Assign this resource to a specific quarter of the school year</div>
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label fw-bold">URL <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                            <input type="url" class="form-control" id="url" name="url" required placeholder="https://example.com/resource">
                        </div>
                        <div class="form-text">Enter the full URL including http:// or https://</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a brief description of this resource"></textarea>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">Active (immediately available to teachers)</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Edit Resource Modal -->
<div class="modal fade" id="editResourceModal" tabindex="-1" aria-labelledby="editResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editResourceForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editResourceModalLabel"><i class="fas fa-edit me-2"></i> Edit Resource</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quarter" class="form-label fw-bold">Quarter <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_quarter" name="quarter" required>
                            <option value="">Select Quarter</option>
                            <option value="1">1st Quarter</option>
                            <option value="2">2nd Quarter</option>
                            <option value="3">3rd Quarter</option>
                            <option value="4">4th Quarter</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_url" class="form-label fw-bold">URL <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                            <input type="url" class="form-control" id="edit_url" name="url" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="addCategoryForm" action="{{ route('admin.resource-categories.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-folder-plus me-2"></i> Add New Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Enter category name">
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="category_description" name="description" rows="2" placeholder="Enter category description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label fw-bold">Icon (FontAwesome) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-icons"></i></span>
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="e.g. book, file-alt, link" required>
                        </div>
                        <div class="form-text">Enter a FontAwesome icon name without the 'fa-' prefix</div>
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label fw-bold">Color <span class="text-danger">*</span></label>
                        <select class="form-select" id="color" name="color" required>
                            <option value="primary">Primary (Blue)</option>
                            <option value="secondary">Secondary (Gray)</option>
                            <option value="success">Success (Green)</option>
                            <option value="danger">Danger (Red)</option>
                            <option value="warning">Warning (Yellow)</option>
                            <option value="info">Info (Cyan)</option>
                            <option value="dark">Dark (Black)</option>
                        </select>
                        <div class="mt-2" id="colorPreview">
                            <span class="badge rounded-pill bg-primary px-3 py-2">Primary</span>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="category_is_active" name="is_active" checked>
                        <label class="form-check-label" for="category_is_active">Active (immediately available for use)</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editCategoryForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="editCategoryModalLabel"><i class="fas fa-edit me-2"></i> Edit Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="edit_category_description" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_icon" class="form-label fw-bold">Icon (FontAwesome) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-icons"></i></span>
                            <input type="text" class="form-control" id="edit_icon" name="icon" required>
                        </div>
                        <div class="form-text">Enter a FontAwesome icon name without the 'fa-' prefix</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_color" class="form-label fw-bold">Color <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_color" name="color" required>
                            <option value="primary">Primary (Blue)</option>
                            <option value="secondary">Secondary (Gray)</option>
                            <option value="success">Success (Green)</option>
                            <option value="danger">Danger (Red)</option>
                            <option value="warning">Warning (Yellow)</option>
                            <option value="info">Info (Cyan)</option>
                            <option value="dark">Dark (Black)</option>
                        </select>
                        <div class="mt-2" id="editColorPreview">
                            <span class="badge rounded-pill bg-primary px-3 py-2">Primary</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Enhanced Tabs */
    .nav-tabs-modern {
        border-bottom: none;
    }

    .nav-tabs-modern .nav-link {
        border: none;
        position: relative;
        font-weight: 600;
    }

    .nav-tabs-modern .nav-link.active {
        color: var(--bs-primary);
        background-color: transparent;
    }

    .nav-tabs-modern .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--bs-primary);
        border-radius: 3px 3px 0 0;
    }

    .nav-tabs-modern .nav-link:not(.active) {
        color: var(--bs-gray-600);
    }

    .nav-tabs-modern .nav-link:hover:not(.active) {
        border: none;
        color: var(--bs-gray-800);
    }

    /* Resource Cards */
    .resource-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .category-card {
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Dashboard Stats */
    .counter-value {
        font-size: 2.2rem;
        font-weight: 700;
        line-height: 1;
    }

    /* Animation for rotation */
    .rotate-15 {
        transform: rotate(15deg);
    }

    /* Table enhancements */
    .resource-table th {
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }

    .resource-row {
        transition: all 0.2s ease;
    }

    .resource-row:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.05);
    }

    /* Opacity utility */
    .opacity-15 {
        opacity: 0.15;
    }

    /* Transition delay utility */
    .transition-delay-1 {
        transition-delay: 0.1s;
    }

    .transition-delay-2 {
        transition-delay: 0.2s;
    }

    .transition-delay-3 {
        transition-delay: 0.3s;
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

        // Resource filters
        const resourceSearch = document.getElementById('resourceSearchInput');
        const categoryFilter = document.getElementById('resourceCategoryFilter');
        const statusFilter = document.getElementById('resourceStatusFilter');

        function applyFilters() {
            const searchTerm = resourceSearch ? resourceSearch.value.toLowerCase() : '';
            const category = categoryFilter ? categoryFilter.value : 'all';
            const status = statusFilter ? statusFilter.value : 'all';

            document.querySelectorAll('.resource-row').forEach(row => {
                const title = row.querySelector('h6').textContent.toLowerCase();
                const description = row.querySelector('p').textContent.toLowerCase();
                const rowCategory = row.getAttribute('data-category');
                const rowStatus = row.getAttribute('data-status');

                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm) || searchTerm === '';
                const matchesCategory = category === 'all' || rowCategory === category;
                const matchesStatus = status === 'all' || rowStatus === status;

                if (matchesSearch && matchesCategory && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (resourceSearch) resourceSearch.addEventListener('keyup', applyFilters);
        if (categoryFilter) categoryFilter.addEventListener('change', applyFilters);
        if (statusFilter) statusFilter.addEventListener('change', applyFilters);

        // Color preview
        const colorSelect = document.getElementById('color');
        const colorPreview = document.getElementById('colorPreview');
        const editColorSelect = document.getElementById('edit_color');
        const editColorPreview = document.getElementById('editColorPreview');

        if (colorSelect && colorPreview) {
            colorSelect.addEventListener('change', function() {
                const color = this.value;
                colorPreview.innerHTML = `<span class="badge rounded-pill bg-${color} px-3 py-2">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
            });
        }

        if (editColorSelect && editColorPreview) {
            editColorSelect.addEventListener('change', function() {
                const color = this.value;
                editColorPreview.innerHTML = `<span class="badge rounded-pill bg-${color} px-3 py-2">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
            });
        }

        // Copy URL functionality
        document.querySelectorAll('.copy-link').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                navigator.clipboard.writeText(url).then(() => {
                    const tooltip = bootstrap.Tooltip.getInstance(this);
                    this.setAttribute('data-bs-original-title', 'Copied!');
                    tooltip.show();

                    setTimeout(() => {
                        this.setAttribute('data-bs-original-title', 'Copy URL');
                    }, 1500);
                });
            });
        });

        // Edit Resource
        const editResourceBtns = document.querySelectorAll('.edit-resource-btn');
        editResourceBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                const url = this.getAttribute('data-url');
                const categoryId = this.getAttribute('data-category');
                const quarter = this.closest('tr').getAttribute('data-quarter');

                document.getElementById('edit_title').value = title;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_url').value = url;
                document.getElementById('edit_category_id').value = categoryId;
                document.getElementById('edit_quarter').value = quarter || '';

                document.getElementById('editResourceForm').action = `/admin/resources/${id}`;
            });
        });

        // Delete Resource
        const deleteResourceBtns = document.querySelectorAll('.delete-resource-btn');
        deleteResourceBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');

                if (confirm(`Are you sure you want to delete "${title}"? This action cannot be undone.`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/resources/${id}`;
                    form.style.display = 'none';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // Edit Category
        const editCategoryBtns = document.querySelectorAll('.edit-category-btn');
        editCategoryBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                const icon = this.getAttribute('data-icon');
                const color = this.getAttribute('data-color');

                document.getElementById('edit_name').value = name;
                document.getElementById('edit_category_description').value = description;
                document.getElementById('edit_icon').value = icon;
                document.getElementById('edit_color').value = color;

                // Update color preview
                if (editColorPreview) {
                    editColorPreview.innerHTML = `<span class="badge rounded-pill bg-${color} px-3 py-2">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                }

                document.getElementById('editCategoryForm').action = `/admin/resource-categories/${id}`;
            });
        });

        // Delete Category
        const deleteCategoryBtns = document.querySelectorAll('.delete-category-btn');
        deleteCategoryBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const resourceCount = parseInt(this.getAttribute('data-resource-count'));

                let message = `Are you sure you want to delete category "${name}"?`;
                if (resourceCount > 0) {
                    message += ` This category contains ${resourceCount} resources which will become uncategorized.`;
                }
                message += " This action cannot be undone.";

                if (confirm(message)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/resource-categories/${id}`;
                    form.style.display = 'none';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // Toggle Resource Status
        const statusToggles = document.querySelectorAll('.status-toggle');
        statusToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const status = this.checked;
                const label = this.nextElementSibling;

                if (label) {
                    label.textContent = status ? 'Active' : 'Inactive';
                    label.className = `form-check-label small ${status ? 'text-success' : 'text-danger'}`;
                }

                fetch(`/admin/resources/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    // Update data attribute for filtering
                    const row = this.closest('.resource-row');
                    if (row) {
                        row.setAttribute('data-status', status ? 'active' : 'inactive');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !status; // Revert toggle if error
                    if (label) {
                        label.textContent = !status ? 'Active' : 'Inactive';
                        label.className = `form-check-label small ${!status ? 'text-success' : 'text-danger'}`;
                    }
                });
            });
        });

        // Toggle Category Status
        const categoryStatusToggles = document.querySelectorAll('.category-status-toggle');
        categoryStatusToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const status = this.checked;

                fetch(`/admin/resource-categories/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !status; // Revert toggle if error
                });
            });
        });
    });
</script>
@endpush