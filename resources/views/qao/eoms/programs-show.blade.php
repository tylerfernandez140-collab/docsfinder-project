@extends('adminlte::page')

@section('title', 'View Program')

@section('content_header')
    <h1>View Program</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Program Details</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Program Name:</dt>
                <dd class="col-sm-9">{{ $program->name }}</dd>

                <dt class="col-sm-3">Department:</dt>
                <dd class="col-sm-9">{{ $program->department->name }}</dd>

                <dt class="col-sm-3">Level:</dt>
                <dd class="col-sm-9">{{ $program->level }}</dd>

                <dt class="col-sm-3">Accreditation:</dt>
                <dd class="col-sm-9">{{ $program->accreditation }}</dd>

                <dt class="col-sm-3">Coordinator:</dt>
                <dd class="col-sm-9">{{ $program->coordinator }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('qao.eoms.programs') }}" class="btn btn-secondary">Back to Programs</a>
        </div>
    </div>
@stop