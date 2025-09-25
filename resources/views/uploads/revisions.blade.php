@extends('adminlte::page')

@section('title', 'Document Revisions')

@section('css')
<style>
    .documents-table { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; }
    .documents-table h3 { font-size: 18px; margin-bottom: 5px; }
    .documents-table p { color: #666; font-size: 14px; margin-bottom: 20px; }
    .status-badge { padding: 6px 12px; border-radius: 20px; color: white; font-size: 12px; display: inline-flex; align-items: center; font-weight: 500; min-width: 100px; justify-content: center; }
    .status-badge.controlled { background-color: #28a745; }
    .status-badge.pending { background-color: #ffc107; color: #212529; }
    .status-badge.rejected { background-color: #dc3545; }
    .status-badge.archived { background-color: #6c757d; }

    /* Custom badge colors */
    .badge-custom-controlled { background-color: #28a745; color: white; }
    .badge-custom-pending { background-color: #ffc107; }
    .badge-custom-rejected { background-color: #dc3545; }
</style>
@stop

@section('content_header')
    <a href="{{ request()->query('return_to') ?: url()->previous() }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Back</a>
    <h1 class="mt-3">{{ $upload->title }} Revisions</h1>
@stop

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="documents-table">
            <h3>Revision History for "{{ $upload->title }}"</h3>
            <p>All versions of this document</p>
            <div class="table-responsive">
    <table class="table table-striped" id="documents-table">
        <thead>
            <tr>
                <th>Version</th>
                <th>Uploaded By</th>
                <th>Date Uploaded</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revisions as $revision)
            <tr>
                <td>{{ $revision->version }}</td>
                <td>{{ $revision->owner->name }}</td>
                <td>{{ $revision->created_at->format('M d, Y H:i A') }}</td>
                <td>
                    <span class="status-badge 
                        @if($revision->status_upload == 0) badge-custom-pending
                        @elseif($revision->status_upload == 1) badge-custom-controlled
                        @elseif($revision->status_upload == 2) badge-custom-rejected
                        @elseif($revision->status_upload == 4) badge-custom-controlled
                        @endif
                        @if($revision->is_archived) archived @endif">
                        @if($revision->status_upload == 0) Pending
                        @elseif($revision->status_upload == 1) Controlled
                        @elseif($revision->status_upload == 2) Rejected
                        @elseif($revision->status_upload == 4) Controlled
                        @endif
                        @if($revision->is_archived) Archived @endif
                    </span>
                </td>
                <td>
                    @if ($revision->is_archived)
                        <a href="{{ $revision->upload_id ? route('documents.download', ['id' => $revision->upload_id]) : '#' }}" class="btn btn-sm btn-primary">Download Archived</a>
                    @else
                        <a href="{{ $revision->upload_id ? route('uploads.view', ['upload_id' => $revision->upload_id, 'return_to' => url()->full()]) : '#' }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ $revision->upload_id ? route('documents.download', ['id' => $revision->upload_id]) : '#' }}" class="btn btn-sm btn-success">Download</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

            </div>
        </div>
    </div>
</div>
@endsection