@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-tachometer-alt text-primary me-2"></i> Teacher Admin Dashboard</h2>
            <p class="text-muted">Manage sections, subjects, and teacher assignments for {{ $school->name }}</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-users-class text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $sectionsCount }}</h5>
                        <p class="text-muted mb-0">Sections</p>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('teacher-admin.sections.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-book text-success fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $subjectsCount }}</h5>
                        <p class="text-muted mb-0">Subjects</p>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-chalkboard-teacher text-info fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $teachersCount }}</h5>
                        <p class="text-muted mb-0">Teachers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Sections -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users-class text-primary me-2"></i> Recent Sections</h5>
                        <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> New Section
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentSections as $section)
                                <a href="{{ route('teacher-admin.sections.show', $section) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $section->name }}</h6>
                                            <small class="text-muted">Grade {{ $section->grade_level }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ $section->subjects->count() }} Subjects</span>
                                            @if($section->adviser)
                                                <small class="d-block text-muted">Adviser: {{ $section->adviser->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-2">
                                <i class="fas fa-users-class text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <p class="text-muted mb-0">No sections have been created yet.</p>
                            <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary mt-3">
                                <i class="fas fa-plus-circle me-1"></i> Create Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Subjects -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-book text-success me-2"></i> Recent Subjects</h5>
                        <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle me-1"></i> New Subject
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentSubjects->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentSubjects as $subject)
                                <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $subject->name }}</h6>
                                            <small class="text-muted">Code: {{ $subject->code }}</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-success">{{ $subject->sections->count() }} Sections</span>
                                            <small class="d-block text-muted">{{ $subject->teachers->count() }} Teachers</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-2">
                                <i class="fas fa-book text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <p class="text-muted mb-0">No subjects have been created yet.</p>
                            <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-success mt-3">
                                <i class="fas fa-plus-circle me-1"></i> Create Subject
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 