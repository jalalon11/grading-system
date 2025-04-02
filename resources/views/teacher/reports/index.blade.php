@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-table fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">Class Record</h5>
                                    <p class="card-text small text-muted">Generate a class record showing student grades by component.</p>
                                    <a href="{{ route('teacher.reports.class-record') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-arrow-right me-2"></i>Generate
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- More report types can be added here in the future -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 