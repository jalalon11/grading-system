@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Manage Subjects</span>
                    <a href="{{ route('teacher.subjects.create') }}" class="btn btn-primary">Add New Subject</a>
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

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject Name</th>
                                    <th>Subject Code</th>
                                    <th>Grade Level</th>
                                    <th>Description</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->grade_level }}</td>
                                        <td>{{ $subject->description }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.subjects.show', $subject->id) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('teacher.subjects.edit', $subject->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                <form action="{{ route('teacher.subjects.destroy', $subject->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No subjects found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $subjects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 