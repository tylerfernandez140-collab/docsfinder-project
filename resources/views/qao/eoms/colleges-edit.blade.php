@extends('adminlte::page')

@section('title', 'Edit College')

@section('content_header')
    <h1>Edit College</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit College Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('qao.eoms.colleges.update', $college->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">College Name:</label>
                        <input type="text" name="name" class="form-control" value="{{ $college->name }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="programs">Programs:</label>
                        <input type="number" name="programs" class="form-control" value="{{ $college->programs }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="accreditation">Accreditation:</label>
                        <input type="text" name="accreditation" class="form-control" value="{{ $college->accreditation }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="qa">QA:</label>
                        <input type="text" name="qa" class="form-control" value="{{ $college->qa }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="coordinator">Coordinator:</label>
                        <input type="text" name="coordinator" class="form-control" value="{{ $college->coordinator }}" required>
                    </div>
                </div>
                <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update College</button>
            <a href="{{ route('qao.eoms.colleges') }}" class="btn btn-secondary">Cancel</a>
        </form>
        </div>
    </div>
@stop