@extends('adminlte::page')

@section('title', 'View Course')

@section('content_header')
    <h1>View Course</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Course Details</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Course Code:</dt>
                <dd class="col-sm-9">{{ $course->code }}</dd>

                <dt class="col-sm-3">Course Title:</dt>
                <dd class="col-sm-9">{{ $course->title }}</dd>

                <dt class="col-sm-3">Program:</dt>
                <dd class="col-sm-9">{{ $course->program->name }}</dd>

                <dt class="col-sm-3">Units:</dt>
                <dd class="col-sm-9">{{ $course->units }}</dd>

                <dt class="col-sm-3">Instructor:</dt>
                <dd class="col-sm-9">{{ $course->instructor }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('qao.eoms.courses') }}" class="btn btn-secondary">Back to Courses</a>
        </div>
    </div>
@stop