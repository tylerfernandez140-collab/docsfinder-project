@extends('adminlte::page')

@section('title', 'Pending Approvals')

@section('css')
<style>
    .dropdown-toggle::after {
        display: none;
    }
    .dropdown-menu {
        min-width: auto;
        left: -80px; /* Adjust this value as needed */
    }
    .dropdown-item {
        padding: .25rem 1rem;
    }
    .dropdown {
        display: inline-block;
    }
</style>
<style>
    /* Watermark */
    #logoWatermark {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 250px;
        height: 250px;
        margin-left: -125px;
        margin-top: -125px;
        opacity: 0.05;
        pointer-events: none;
        z-index: 9999;
        animation: spin 10s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Dashboard UI Styles */
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .stats-card { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; position: relative; }
    .stats-card .icon { position: absolute; right: 20px; top: 20px; font-size: 20px; }
    .stats-card .title { font-size: 14px; color: #666; margin-bottom: 5px; }
    .stats-card .number { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
    .stats-card .subtitle { font-size: 12px; color: #999; }
    .stats-card.controlled .icon { color: #28a745; }
    .stats-card.pending .icon { color: #ffc107; }
    .stats-card.downloads .icon { color: #6c757d; }
    .quick-actions { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
    .quick-actions h3 { font-size: 18px; margin-bottom: 5px; }
    .quick-actions p { color: #666; font-size: 14px; margin-bottom: 20px; }
    .quick-actions .btn { margin-right: 10px; margin-bottom: 10px; }
    .documents-table { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; }
    .documents-table h3 { font-size: 18px; margin-bottom: 5px; }
    .documents-table p { color: #666; font-size: 14px; margin-bottom: 20px; }
    .status-badge { padding: 6px 12px; border-radius: 20px; color: white; font-size: 12px; display: inline-flex; align-items: center; font-weight: 500; min-width: 100px; justify-content: center; }
    .status-badge.controlled { background-color: #28a745; }
    .status-badge.pending { background-color: #ffc107; color: #212529; }
    .status-badge.expired { background-color: #dc3545; }
    .status-badge i { margin-right: 5px; }
    .search-box { max-width: 7000px; }
</style>
@stop






@section('js')
<script>
    $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var documentId = button.data('document-id'); // Extract info from data-* attributes
        var form = $('#deleteDocumentForm');
        form.attr('action', '/uploads/' + documentId); // Set the form action dynamically
    });
</script>
@stop

@section('content_header')
<div class="dashboard-header">
    <div>
        <a href="{{ route('home') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Back</a>
        <h1 class="mt-3">Pending Approvals</h1>
        <p>Overview of documents awaiting approval</p>
    </div>
    <div class="search-box">
        <div class="input-group" style="width: 400px;">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Search documents...">
        </div>
        <!-- Back Button aligned right -->

    </div>
</div>
@stop

@section('content')
<!-- Centered Spinning Watermark -->
<img src="{{ asset('images/psu_logo.png') }}" id="logoWatermark" alt="PSU Logo">

<div class="row">
    <!-- Pending Approvals -->
    <div class="col-md-12">
        <div class="stats-card pending">
            <i class="fas fa-clock icon"></i>
            <div class="title">Pending Approvals</div>
            <div class="number">{{ count($pendingApprovalsList) }}</div>
            <div class="subtitle">Documents awaiting approval</div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <!-- Recent Documents Table -->
        <div class="documents-table">
            <h3>Pending Approvals</h3>
            <p>A comprehensive list of documents awaiting approval</p>
            <div class="table-responsive">
                <table class="table table-striped" id="documents-table">
                    <thead>
                        <tr>
                            <th>Document Title</th>
                            <th>Control Number</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Version</th>
                            <th>Revisions</th>
                            <th>Owner</th>
                            <th>Last Modified</th>
                            <th>Downloads</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populate with document rows from your database -->
                  @foreach($pendingApprovalsList as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->control_number }}</td>
                        <td>{{ $doc->type }}</td>
                        <td>
                            <span class="status-badge 
                                @if($doc->status_upload == 4) controlled 
                                @elseif($doc->status_upload == 0) pending 
                                @else other 
                                @endif">
                                @if($doc->status_upload == 4) Controlled 
                                @elseif($doc->status_upload == 0) Pending 
                                @else Unknown 
                                @endif
                            </span>
                        </td>
                        <td>{{ $doc->version }}</td>
                        <td><a href="{{ route('uploads.revisions', $doc->upload_id) }}?return_to={{ url()->full() }}" class="btn btn-sm btn-secondary"><i class="fas fa-history"></i> View</a></td>
                        <td>{{ $doc->owner->name ?? 'N/A' }}</td>
                        <td>{{ $doc->updated_at->format('M d, Y H:i') }}</td>
                        <td>{{ $doc->numdl }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $doc->id }}" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $doc->id }}">
                                                <a class="dropdown-item" href="{{ $doc->upload_id ? route('uploads.view', ['upload_id' => $doc->upload_id, 'previous_url' => url()->current()]) : '#' }}"><i class="fas fa-eye mr-2"></i> View</a>
                                                @if(!empty($doc->filename))
                                                    <a class="dropdown-item" href="{{ route('documents.download', ['id' => $doc->upload_id]) }}"><i class="fas fa-download mr-2"></i> Download</a>
                                                @endif
                                                @if(Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 2)
                                                    <a class="dropdown-item" href="{{ route('uploads.edit', $doc->upload_id) }}"><i class="fas fa-edit mr-2"></i> Edit</a>
                                                    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteConfirmationModal" data-document-id="{{ $doc->upload_id }}"><i class="fas fa-trash-alt mr-1"></i> Delete</a>
                                                @endif
                                            </div>
                                    </div>
                                </td>
                    </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this document? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteDocumentForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop