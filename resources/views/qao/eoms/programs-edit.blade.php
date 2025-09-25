@extends('adminlte::page')

@section('title', 'Edit Program')

@section('content_header')
    <h1>Edit Program</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Program</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('qao.eoms.programs.update', $program->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Program Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $program->name) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department_id">Department</label>
                        <select name="department_id" class="form-control" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $program->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="level">Level</label>
                        <input type="text" name="level" class="form-control" value="{{ old('level', $program->level) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="accreditation">Accreditation</label>
                        <input type="text" name="accreditation" class="form-control" value="{{ old('accreditation', $program->accreditation) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="coordinator">Coordinator</label>
                        <input type="text" name="coordinator" class="form-control" value="{{ old('coordinator', $program->coordinator) }}" required>
                    </div>
                </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Program</button>
                    <a href="{{ route('qao.eoms.programs') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@stop