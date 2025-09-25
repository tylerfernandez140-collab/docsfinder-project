@extends('adminlte::page')

@section('title', 'Colleges')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Colleges</h1>
        <a href="{{ route('eoms.colleges.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add College
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
                        <th>No. of Programs</th>
                        <th>Accreditation</th>
                        <th>QA</th>
                        <th>Coordinator</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($colleges as $college)
                        <tr>
                            <td>{{ $college->name }}</td>
                            <td>{{ $college->programs }}</td>
                            <td>{{ $college->accreditation }}</td>
                            <td>{{ $college->qa }}</td>
                            <td>{{ $college->coordinator }}</td>
                            <td>
                                <a href="{{ route('qao.eoms.colleges.show', $college->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('qao.eoms.colleges.edit', $college->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('qao.eoms.colleges.destroy', $college->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this college?');">Delete</button>
                                        </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
