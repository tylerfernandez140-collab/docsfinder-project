@extends('adminlte::page')

@section('title', 'Faculty')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Faculty</h1>
        <a href="{{ route('eoms.faculty.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Faculty
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Specialization</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($faculty as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->department->name }}</td>
                                    <td>{{ $member->designation }}</td>
                                    <td>{{ $member->specialization }}</td>
                                    <td>
                                        <a href="{{ route('qao.eoms.faculty.show', $member->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('qao.eoms.faculty.edit', $member->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('qao.eoms.faculty.destroy', $member->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this faculty member?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
