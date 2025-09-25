@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1><i class="fas fa-chart-line"></i> QA Reports</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h3 class="card-title mb-0">Generated Reports</h3>
    </div>
    <div class="card-body">
        <ul>
            <li><a href="#">Accreditation Status Report</a></li>
            <li><a href="#">Faculty Performance Report</a></li>
            <li><a href="#">Student Assessment QA Report</a></li>
            <li><a href="#">College Compliance Report</a></li>
        </ul>
    </div>
</div>
@stop
