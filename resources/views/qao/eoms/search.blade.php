@extends('adminlte::page')

@section('title', 'Search Documents')

@section('content_header')
    <h1><i class="fas fa-search"></i> Search / Quick Access</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('eoms.search.results') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search documents, faculty, programs...">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>
@stop
