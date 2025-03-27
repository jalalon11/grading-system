@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Edit Subject</span>
                    <a href="{{ route('teacher.subjects.index') }}" class="btn btn-secondary">Back to List</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.subjects.update', $subject->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $subject->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $subject->code) }}" required>
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="grade_level" class="form-label">Grade Level</label>
                            <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                <option value="">Select Grade Level</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('grade_level', $subject->grade_level) == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                                @endfor
                            </select>
                            @error('grade_level')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="section_id" class="form-label">Section</label>
                            <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $subject->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }} ({{ $section->gradeLevel->name ?? 'Grade '.$section->grade_level_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $subject->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 