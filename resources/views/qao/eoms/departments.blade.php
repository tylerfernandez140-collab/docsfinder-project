@extends('adminlte::page')

@section('title', 'Departments')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Departments</h1>
        <a href="{{ route('eoms.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Department
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Department Name</th>
                        <th>College</th>
                        <th>No. of Programs</th>
                        <th>Head</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->college->name }}</td>
                                    <td>{{ $department->programs()->count() }}</td>
                                    <td>{{ $department->head }}</td>
                                    <td>
                                        <a href="{{ route('qao.eoms.departments.show', $department->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('qao.eoms.departments.edit', $department->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('qao.eoms.departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this department?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
