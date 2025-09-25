@extends('adminlte::page')

@section('title', 'Distribute Document')

@section('content_header')
    <h1>Distribute Document: {{ $upload->title }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Distribution Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('documents.perform-distribution', ['upload_id' => $upload->upload_id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="designation">Distribute to Designation:</label>
                    <select name="designation" id="designation" class="form-control" required>
                        <option value="">Select Designation</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="process_owners">Distribute to Process Owner(s):</label>
                    <select name="process_owners[]" id="process_owners" class="form-control" multiple="multiple" required>
                        @foreach($processOwners as $processOwner)
                            <option value="{{ $processOwner->id }}">{{ $processOwner->first_name }} {{ $processOwner->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Distribute Document</button>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#process_owners').select2({
                placeholder: "Select Process Owner(s)",
                allowClear: true
            });
        });
    </script>
@stop