@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Attendance Details - {{ $section->name }} ({{ \Carbon\Carbon::parse($date)->format('M d, Y') }})</span>
                    <div>
                        <a href="{{ route('teacher.attendances.edit', ['attendance' => $section->id, 'date' => $date]) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('teacher.attendances.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Attendance Information</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">Section:</dt>
                                    <dd class="col-sm-8">{{ $section->name }}</dd>

                                    <dt class="col-sm-4">Date:</dt>
                                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</dd>

                                    <dt class="col-sm-4">Total Students:</dt>
                                    <dd class="col-sm-8">{{ count($students) }}</dd>

                                    <dt class="col-sm-4">Present:</dt>
                                    <dd class="col-sm-8">{{ $presentCount }}</dd>

                                    <dt class="col-sm-4">Absent:</dt>
                                    <dd class="col-sm-8">{{ $absentCount }}</dd>

                                    <dt class="col-sm-4">Attendance Rate:</dt>
                                    <dd class="col-sm-8">
                                        @if(count($students) > 0)
                                            {{ round(($presentCount / count($students)) * 100, 2) }}%
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body bg-light">
                                        <h5 class="card-title">Attendance Summary</h5>
                                        <div class="d-flex justify-content-around text-center">
                                            <div>
                                                <h3 class="text-success">{{ $presentCount }}</h3>
                                                <p>Present</p>
                                            </div>
                                            <div>
                                                <h3 class="text-danger">{{ $absentCount }}</h3>
                                                <p>Absent</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Student Attendance Details</h5>
                    @if(count($students) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Student ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td>{{ $student->student_id }}</td>
                                            <td>
                                                @if($attendanceData[$student->id] == 'present')
                                                    <span class="badge bg-success">Present</span>
                                                @else
                                                    <span class="badge bg-danger">Absent</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No students found in this section.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 