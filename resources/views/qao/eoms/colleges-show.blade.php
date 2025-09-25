@extends('adminlte::page')

@section('title', 'View College')

@section('content_header')
    <h1>View College</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">College Details</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">College Name:</dt>
                <dd class="col-sm-9">{{ $college->name }}</dd>

                <dt class="col-sm-3">Programs:</dt>
                <dd class="col-sm-9">{{ $college->programs }}</dd>

                <dt class="col-sm-3">Accreditation:</dt>
                <dd class="col-sm-9">{{ $college->accreditation }}</dd>

                <dt class="col-sm-3">QA:</dt>
                <dd class="col-sm-9">{{ $college->qa }}</dd>

                <dt class="col-sm-3">Coordinator:</dt>
                <dd class="col-sm-9">{{ $college->coordinator }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('qao.eoms.colleges') }}" class="btn btn-secondary">Back to Colleges</a>
        </div>
    </div>
@stop