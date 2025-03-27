@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Edit Attendance - {{ $section->name }} ({{ \Carbon\Carbon::parse($date)->format('M d, Y') }})</span>
                    <a href="{{ route('teacher.attendances.index') }}" class="btn btn-secondary">Back to List</a>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teacher.attendances.update', $section->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="section_id" value="{{ $section->id }}">
                        
                        <div class="mb-4">
                            <div class="alert alert-info">
                                Editing attendance for {{ $section->name }} on {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                            </div>
                        </div>
                        
                        @if(count($students) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Student ID</th>
                                            <th width="150">Present</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            <tr>
                                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                <td>{{ $student->student_id }}</td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" id="present_{{ $student->id }}" value="present" {{ $attendanceData[$student->id] == 'present' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="present_{{ $student->id }}">Present</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" id="absent_{{ $student->id }}" value="absent" {{ $attendanceData[$student->id] == 'absent' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="absent_{{ $student->id }}">Absent</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                No students found in this section. <a href="{{ route('teacher.students.create') }}">Add students</a>
                            </div>
                        @endif
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Update Attendance</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 