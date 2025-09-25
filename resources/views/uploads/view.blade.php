@extends('adminlte::page')

@section('title', 'Manage Documents')

@section('content_header')
    <h1>Manage Documents
        <div class="float-right">
            <a href="{{ url()->previous() }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </h1>
@stop



@section('content')

@section('content')
@php
    $badgeClass = match($status) {
        'approved' => 'badge-success',
        'pending' => 'badge-custom-pending',
        'rejected' => 'badge-custom-rejected',
        'controlled' => 'badge-custom-controlled',
        default => 'badge-secondary',
    };
@endphp

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($upload)
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Document Details</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr><th>Document Title</th><td>{{ $upload->title }}</td></tr>
                        <tr><th>Control Number</th><td>{{ $upload->control_number }}</td></tr>
                        <tr><th>Type</th><td>{{ $upload->type }}</td></tr>
                        <tr><th>Status</th><td>
                            @php
                                $statusText = match($upload->status_upload) {
                                    0 => 'Pending',
                                    2 => 'Rejected',
                                    4 => 'Controlled',
                                    default => 'Unknown',
                                };
                                $badgeClass = match($upload->status_upload) {
                                    0 => 'badge-custom-pending',
                                    2 => 'badge-custom-rejected',
                                    4 => 'badge-custom-controlled',
                                    default => 'badge-secondary',
                                };
                            @endphp
                            <span class="status-badge {{ $badgeClass }} rounded-pill py-2 px-3">{{ $statusText }}</span>
                        </td></tr>
                        <tr><th>Version</th><td>{{ $upload->version }}</td></tr>
                        <tr><th>Revisions</th><td>
                            @if($revisions->isNotEmpty())
                                <table class="table table-sm table-bordered mt-2">
                                    <thead>
                                        <tr>
                                            <th>Version</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($revisions as $rev)
                                            <tr>
                                                <td>{{ $rev->version }}</td>
                                                <td>{{ $rev->created_at->format('M d, Y H:i:s') }}</td>
                                                <td>
                                                    @php
                                                        $statusText = match((int)$rev->status_upload) {
                                                            0 => 'Pending',
                                                            2 => 'Rejected',
                                                            4 => 'Controlled',
                                                            default => 'Unknown',
                                                        };
                                                    @endphp
                                                    {{ $statusText }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                No revisions available.
                            @endif
                        </td></tr>
                        <tr><th>Owner</th><td>{{ $upload->owner->name }}</td></tr>
                        <tr><th>Last Modified</th><td>{{ $upload->updated_at->format('M d, Y H:i:s') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Document Preview</h3>
            </div>
            <div class="card-body">
                @php
                    $fileExtension = pathinfo($upload->file_path, PATHINFO_EXTENSION);
                @endphp

                @php
                    $fileExtension = strtolower(pathinfo($upload->file_path, PATHINFO_EXTENSION));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                @endphp

                @if ($fileExtension == 'pdf')
                    <iframe src="{{ asset('storage/' . $upload->file_path) }}" width="100%" height="400px" style="border: none;"></iframe>
                @elseif (in_array($fileExtension, $imageExtensions))
                    <img src="{{ asset('storage/' . $upload->file_path) }}" class="img-fluid" alt="Document Image Preview">
                @else
                    {{-- Debugging: Output file path and asset URL --}}
                    <p>File Path: {{ $upload->file_path }}</p>
                    <p>Asset URL: {{ asset('storage/' . $upload->file_path) }}</p>
                    <p>No direct preview available for this file type. Please download the file to view it.</p>
                    @if ($upload->upload_id && (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('campus-dcc') || Auth::user()->hasRole('process-owner')))
                        <a href="{{ $upload->upload_id ? route('documents.download', ['id' => $upload->upload_id]) : '#' }}" class="btn btn-primary">Download Document</a>
                    @else
                        <p>Document ID not available for download.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>



@else
<p class="text-muted fst-italic">No document uploaded yet.</p>
<a href="{{ route('uploads.create') }}" class="btn btn-primary mt-3">
    <i class="fas fa-upload mr-2"></i> Upload Document
</a>
@endif
@stop

@section('css')
<style>
    .dropdown {
        display: inline-block;
    }

    /* Custom badge colors */
    .badge-custom-controlled { background-color: #28a745; color: white; }
    .badge-custom-pending { background-color: #ffc107; color: white;}
    .badge-custom-rejected { background-color: #dc3545; color: white; }
</style>
@stop

@section('js')
@stop
