@extends('adminlte::page')

@section('title', 'Courses')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Courses</h1>
        <a href="{{ route('eoms.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Course
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Program</th>
                        <th>Units</th>
                        <th>Instructor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->program->name }}</td>
                                    <td>{{ $course->units }}</td>
                                    <td>{{ $course->instructor }}</td>
                                    <td>
                                        <a href="{{ route('qao.eoms.courses.show', $course->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('qao.eoms.courses.edit', $course->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('qao.eoms.courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
