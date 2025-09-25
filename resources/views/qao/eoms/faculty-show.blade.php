@extends('adminlte::page')

@section('title', 'View Faculty')

@section('content_header')
    <h1>View Faculty</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Faculty Details</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Name:</dt>
                <dd class="col-sm-9">{{ $faculty->name }}</dd>

                <dt class="col-sm-3">Department:</dt>
                <dd class="col-sm-9">{{ $faculty->department->name }}</dd>

                <dt class="col-sm-3">Designation:</dt>
                <dd class="col-sm-9">{{ $faculty->designation }}</dd>

                <dt class="col-sm-3">Specialization:</dt>
                <dd class="col-sm-9">{{ $faculty->specialization }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('qao.eoms.faculty') }}" class="btn btn-secondary">Back to Faculty List</a>
        </div>
    </div>
@stop