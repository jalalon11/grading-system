@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Manage Attendance</span>
                    <a href="{{ route('teacher.attendances.create') }}" class="btn btn-primary">Record Attendance</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('teacher.attendances.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label for="section_id" class="form-label">Section</label>
                                <select class="form-select" id="section_id" name="section_id">
                                    <option value="">All Sections</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-secondary w-100">Filter</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Section</th>
                                    <th>Students Present</th>
                                    <th>Students Absent</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $date => $dateGroup)
                                    @foreach ($dateGroup as $sectionId => $attendance)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                            <td>{{ $attendance['section_name'] }}</td>
                                            <td>{{ $attendance['present_count'] }}</td>
                                            <td>{{ $attendance['absent_count'] }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('teacher.attendances.show', ['attendance' => $sectionId, 'date' => $date]) }}" class="btn btn-info btn-sm">View Details</a>
                                                    <a href="{{ route('teacher.attendances.edit', ['attendance' => $sectionId, 'date' => $date]) }}" class="btn btn-primary btn-sm">Edit</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No attendance records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 