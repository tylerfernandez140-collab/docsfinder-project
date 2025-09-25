@extends('adminlte::page')

@section('title', 'Edit Faculty')

@section('content_header')
    <h1>Edit Faculty</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Faculty Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('qao.eoms.faculty.update', $faculty->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $faculty->name) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department_id">Department</label>
                        <select name="department_id" class="form-control" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $faculty->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="designation">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $faculty->designation) }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="specialization">Specialization</label>
                        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $faculty->specialization) }}" required>
                    </div>
                </div>
                <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update Faculty</button>
                <a href="{{ route('qao.eoms.faculty') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        </div>
    </div>
@stop