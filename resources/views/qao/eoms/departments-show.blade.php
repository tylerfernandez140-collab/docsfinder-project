@extends('adminlte::page')

@section('title', 'View Department')

@section('content_header')
    <h1>View Department</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Department Details</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Department Name:</dt>
                <dd class="col-sm-9">{{ $department->name }}</dd>

                <dt class="col-sm-3">College:</dt>
                <dd class="col-sm-9">{{ $department->college->name }}</dd>

                <dt class="col-sm-3">Programs:</dt>
                <dd class="col-sm-9">{{ $department->programs()->count() }}</dd>

                <dt class="col-sm-3">Head:</dt>
                <dd class="col-sm-9">{{ $department->head }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('qao.eoms.departments') }}" class="btn btn-secondary">Back to Departments</a>
        </div>
    </div>
@stop