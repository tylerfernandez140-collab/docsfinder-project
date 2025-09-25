@extends('adminlte::page')

@section('title', 'Edit Course')

@section('content_header')
    <h1>Edit Course</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Course</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('qao.eoms.courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="code">Course Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $course->code) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="title">Course Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="program_id">Program</label>
                        <select name="program_id" class="form-control" required>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ old('program_id', $course->program_id) == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="units">Units</label>
                        <input type="number" name="units" class="form-control" value="{{ old('units', $course->units) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="instructor">Instructor</label>
                        <input type="text" name="instructor" class="form-control" value="{{ old('instructor', $course->instructor) }}" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="{{ route('qao.eoms.courses') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
@stop