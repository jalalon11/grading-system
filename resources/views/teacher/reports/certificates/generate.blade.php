@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-award me-2"></i>Academic Excellence Certificates
                    </h4>
                    <div>
                        <a href="{{ route('teacher.reports.certificates.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1">{{ $section->name }} (Grade {{ $section->grade_level }})</h5>
                            <p class="text-muted mb-0">{{ $quarter == 'Q1' ? '1st' : ($quarter == 'Q2' ? '2nd' : ($quarter == 'Q3' ? '3rd' : '4th')) }} Quarter</p>
                        </div>
                        <div>
                            <span class="badge bg-primary">School Year: {{ $section->school_year }}</span>
                        </div>
                    </div>

                    @if(empty($awards['highest_honors']) && empty($awards['high_honors']) && empty($awards['honors']))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No students qualify for academic excellence awards in this quarter.
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-muted mb-0">
                                    The following students have qualified for academic excellence awards based on their performance in {{ $quarter == 'Q1' ? '1st' : ($quarter == 'Q2' ? '2nd' : ($quarter == 'Q3' ? '3rd' : '4th')) }} Quarter.
                                    Click on a student's name to preview and print their certificate.
                                </p>
                            </div>
                        </div>

                        @if(!empty($awards['highest_honors']))
                            <div class="mb-4">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-medal me-2"></i>With Highest Honors (98-100)
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Average</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($awards['highest_honors'] as $index => $data)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $data['student']->surname_first }}</td>
                                                    <td>{{ $data['average'] }}</td>
                                                    <td>
                                                        <a href="{{ route('teacher.reports.certificates.preview', [
                                                            'student_id' => $data['student']->id,
                                                            'section_id' => $section->id,
                                                            'quarter' => $quarter,
                                                            'award_type' => 'highest_honors',
                                                            'average' => $data['average']
                                                        ]) }}" class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="fas fa-eye me-1"></i> Preview
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if(!empty($awards['high_honors']))
                            <div class="mb-4">
                                <h5 class="fw-bold text-success mb-3">
                                    <i class="fas fa-medal me-2"></i>With High Honors (95-97)
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Average</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($awards['high_honors'] as $index => $data)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $data['student']->surname_first }}</td>
                                                    <td>{{ $data['average'] }}</td>
                                                    <td>
                                                        <a href="{{ route('teacher.reports.certificates.preview', [
                                                            'student_id' => $data['student']->id,
                                                            'section_id' => $section->id,
                                                            'quarter' => $quarter,
                                                            'award_type' => 'high_honors',
                                                            'average' => $data['average']
                                                        ]) }}" class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="fas fa-eye me-1"></i> Preview
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if(!empty($awards['honors']))
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold text-info mb-0">
                                        <i class="fas fa-medal me-2"></i>With Honors (90-94)
                                    </h5>
                                    <a href="{{ route('teacher.reports.certificates.bulk-preview', ['section_id' => $section->id, 'quarter' => $quarter]) }}" class="btn btn-success shadow-sm certificate-generate-btn" target="_blank">
                                        <i class="fas fa-certificate me-2 fa-fw"></i> Generate All Certificates
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Average</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($awards['honors'] as $index => $data)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $data['student']->surname_first }}</td>
                                                    <td>{{ $data['average'] }}</td>
                                                    <td>
                                                        <a href="{{ route('teacher.reports.certificates.preview', [
                                                            'student_id' => $data['student']->id,
                                                            'section_id' => $section->id,
                                                            'quarter' => $quarter,
                                                            'award_type' => 'honors',
                                                            'average' => $data['average']
                                                        ]) }}" class="btn btn-sm btn-primary" target="_blank">
                                                            <i class="fas fa-eye me-1"></i> Preview
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .certificate-generate-btn {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 10px 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.12);
    }

    .certificate-generate-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.18);
        background: linear-gradient(45deg, #20c997, #28a745);
    }

    .certificate-generate-btn i {
        font-size: 1.1rem;
    }
</style>
@endpush
