@extends('adminlte::page')

@section('title', 'Programs')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Programs</h1>
        <a href="{{ route('eoms.programs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Program
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Department</th>
                        <th>Level</th>
                        <th>Accreditation</th>
                        <th>Coordinator</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($programs as $program)
                                <tr>
                                    <td>{{ $program->name }}</td>
                                    <td>{{ $program->department->name }}</td>
                                    <td>{{ $program->level }}</td>
                                    <td>{{ $program->accreditation }}</td>
                                    <td>{{ $program->coordinator }}</td>
                                    <td>
                                        <a href="{{ route('qao.eoms.programs.show', $program->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('qao.eoms.programs.edit', $program->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('qao.eoms.programs.destroy', $program->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this program?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
