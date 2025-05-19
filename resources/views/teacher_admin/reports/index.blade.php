@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 py-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>Reports Dashboard
                    </h4>
                    <a href="{{ route('teacher-admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                    </a>
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted mb-4">Select a report type from the options below to generate detailed student performance records.</p>

                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-4">
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

                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-body d-flex flex-column text-center p-4">
                                    <div class="mb-4 report-icon-container" style="background-color: rgba(40, 167, 69, 0.1);">
                                        <i class="fas fa-clipboard-check fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title fw-bold">Attendance Summary</h5>
                                    <p class="card-text text-muted flex-grow-1">Generate comprehensive attendance reports with analytics and identify attendance patterns across the school.</p>
                                    <a href="{{ route('teacher-admin.reports.attendance-summary') }}" class="btn btn-success mt-3">
                                        <i class="fas fa-arrow-right me-2"></i>Generate Report
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .report-icon-container {
        background-color: rgba(13, 110, 253, 0.1);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
</style>
@endsection
