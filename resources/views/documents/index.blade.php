@extends('adminlte::page')

@section('title', 'All Documents')

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
        <h1 class="mt-3">Total Documents</h1>
        <p>Overview of all documents</p>
    </div>
    <div class="search-box">
        <div class="input-group" style="width: 400px;">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="Search all documents...">
        </div>
    </div>
</div>
@stop

@section('content')
<!-- Centered Spinning Watermark -->
<img src="{{ asset('images/psu_logo.png') }}" id="logoWatermark" alt="PSU Logo">

<div class="row">
    <!-- All Documents -->
    <div class="col-md-12">
        <div class="stats-card controlled">
            <i class="{{ $totalDocumentsIcon ?? 'fas fa-file' }} icon"></i>
            <div class="title">{{ $totalDocumentsTitle ?? 'Total Documents' }}</div>
            <div class="number">{{ $totalDocuments ?? 0 }}</div>
            <div class="subtitle">{{ $totalDocumentsSubtitle ?? 'Overview of all documents' }}</div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <!-- All Documents Table -->
        <div class="documents-table">
            <h3>All Documents</h3>
            <p>A comprehensive list of all documents</p>
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
                        {{-- Document data will be loaded here via DataTables --}}
                    </tbody>
                </table>
            </div>
        </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this document? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteDocumentForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@stop

@section('js')
    <script>
        $(function () {
            $('#documents-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('api.documents.index') }}',
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'control_number', name: 'control_number' },
                    { data: 'type', name: 'type' },
                    { data: 'status', name: 'status' },
                    { data: 'version', name: 'version' },
                    { data: 'revisions', name: 'revisions', orderable: false, searchable: false },
                    { data: 'owner', name: 'owner' },
                    { data: 'last_modified', name: 'last_modified' },
                    { data: 'downloads', name: 'downloads' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@stop