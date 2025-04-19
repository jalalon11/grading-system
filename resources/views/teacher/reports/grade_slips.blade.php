@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Generate Grade Slips</h2>
                <a href="{{ route('teacher.reports.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Reports
                </a>
            </div>
            <p class="text-muted">Generate grade slips for students in your advisory sections.</p>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Grade Slip Options</h5>
                </div>
                <div class="card-body">
                    @if($sections->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            You don't have any advisory sections assigned to you. Grade slips can only be generated for sections where you are the adviser.
                        </div>
                    @else
                        <form action="{{ route('teacher.reports.generate-grade-slips') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                    <select name="section_id" id="section_id" class="form-select @error('section_id') is-invalid @enderror" required>
                                        <option value="">Select a section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }} - {{ $section->grade_level }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Only sections where you are the adviser are shown.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="quarter" class="form-label">Quarter <span class="text-danger">*</span></label>
                                    <select name="quarter" id="quarter" class="form-select @error('quarter') is-invalid @enderror" required>
                                        <option value="">Select a quarter</option>
                                        @foreach($quarters as $key => $value)
                                            <option value="{{ $key }}" {{ old('quarter') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                        <option value="all" {{ old('quarter') == 'all' ? 'selected' : '' }}>
                                            All Quarters
                                        </option>
                                    </select>
                                    @error('quarter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="transmutation_table" class="form-label">Transmutation Table</label>
                                    <select class="form-select" id="transmutation_table" name="transmutation_table">
                                        <option value="1">Table 1: DepEd Transmutation Table</option>
                                        <option value="2">Table 2: Grades 1-10 & Non-Core TVL</option>
                                        <option value="3">Table 3: SHS Core & Work Immersion</option>
                                        <option value="4">Table 4: SHS Academic Track</option>
                                    </select>
                                    <div class="form-text">Select the transmutation table to apply to the grades.</div>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-file-alt me-2"></i> Generate Grade Slips
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">About Grade Slips</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle text-primary me-2"></i> What are Grade Slips?</h6>
                            <p>Grade slips are individual reports that show a student's grades for all subjects in a specific quarter. They provide a comprehensive view of a student's academic performance.</p>

                            <h6><i class="fas fa-user-check text-success me-2"></i> Who can generate Grade Slips?</h6>
                            <p>Only section advisers can generate grade slips for students in their advisory sections.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calculator text-warning me-2"></i> How are grades calculated?</h6>
                            <p>Grades are calculated based on the approved grades from subject teachers. The system uses the same calculation method as the consolidated grades report.</p>

                            <h6><i class="fas fa-print text-info me-2"></i> How to print Grade Slips?</h6>
                            <p>After generating grade slips, you can preview each student's grade slip and use your browser's print function to print them individually or in bulk.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
