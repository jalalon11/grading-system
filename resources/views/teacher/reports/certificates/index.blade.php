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
                    <a href="{{ route('teacher.reports.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Reports
                    </a>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Generate academic excellence certificates for students who have achieved outstanding performance in a quarter.
                        Certificates are awarded to students who have attained an average of at least 90 and passed all learning areas.
                        <strong>Note:</strong> Only class advisers can generate certificates for their advisory sections.
                    </p>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($sections->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            You are not currently assigned as an adviser to any section. Only class advisers can generate certificates for their advisory sections.
                        </div>
                    @else
                        <form action="{{ route('teacher.reports.certificates.generate') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="section_id" class="form-label">Section</label>
                                    <select name="section_id" id="section_id" class="form-select" required>
                                        <option value="">Select a section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }} - {{ $section->grade_level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="quarter" class="form-label">Quarter</label>
                                    <select name="quarter" id="quarter" class="form-select" required>
                                        <option value="">Select a quarter</option>
                                        <option value="Q1">Quarter 1</option>
                                        <option value="Q2">Quarter 2</option>
                                        <option value="Q3">Quarter 3</option>
                                        <option value="Q4">Quarter 4</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Generate Certificates
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">Academic Excellence Award Categories</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Award</th>
                                        <th>Average Grade per Quarter</th>
                                        <th>Requirements</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>With Highest Honors<br><em>May Pinakamataas na Karangalan</em></td>
                                        <td class="text-center">98-100</td>
                                        <td rowspan="3">
                                            <ul class="mb-0 ps-3">
                                                <li>Student must have passed all learning areas</li>
                                                <li>Average is calculated as a whole number following DepEd Order No. 8, s. 2015</li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>With High Honors<br><em>May Mataas na Karangalan</em></td>
                                        <td class="text-center">95-97</td>
                                    </tr>
                                    <tr>
                                        <td>With Honors<br><em>May Karangalan</em></td>
                                        <td class="text-center">90-94</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
