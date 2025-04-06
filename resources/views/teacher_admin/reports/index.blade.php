@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-bar mr-2 text-primary"></i> Reports
            </h1>
            <p class="text-muted">Generate various reports for your sections</p>
        </div>
        <div>
            <a href="{{ route('teacher-admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-card">
                <div class="card-body d-flex flex-column text-center p-4">
                    <div class="mb-4 report-icon-container">
                        <i class="fas fa-table fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold">Consolidated Grades</h5>
                    <p class="card-text text-muted flex-grow-1">Generate a consolidated grading sheet showing all subject grades for students in a section.</p>
                    <a href="{{ route('teacher-admin.reports.consolidated-grades') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-right me-2"></i>Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    .report-icon-container {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(var(--bs-primary-rgb), 0.1);
    }
</style>
@endsection
