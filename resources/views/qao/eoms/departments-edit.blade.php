@extends('adminlte::page')

@section('title', 'Edit Department')

@section('content_header')
    <h1>Edit Department</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Department Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('qao.eoms.departments.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Department Name:</label>
                        <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="college_id">College:</label>
                        <select name="college_id" class="form-control" required>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}" {{ $department->college_id == $college->id ? 'selected' : '' }}>{{ $college->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="programs">Programs:</label>
                        <input type="number" name="programs" class="form-control" value="{{ $department->programs }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="head">Head:</label>
                        <input type="text" name="head" class="form-control" value="{{ $department->head }}" required>
                    </div>
                </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Department</button>
                    <a href="{{ route('qao.eoms.departments') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@stop