@extends('adminlte::page')

@section('title', 'Approval Requests')

@section('content_header')
    <h1>Approval Requests</h1>
@stop

@section('content')
    <!-- Approve Confirmation Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Confirm Approval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to approve this document?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="confirmApprove" class="btn btn-success">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reject this document?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="confirmReject" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pending Uploads</h3>
        </div>
        <div class="card-body">
            @if($pendingUploads->isEmpty())
                <p>No pending approval requests.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Document Title</th>
                            <th>Control Number</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Version</th>
                            <th>Owner</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUploads as $upload)
                            <tr>
                                <td>{{ $upload->title }}</td>
                                <td>{{ $upload->control_number }}</td>
                                <td>{{ $upload->type }}</td>
                                <td>
                                @if($upload->status_upload === 0)
                                    Pending
                                @elseif($upload->status_upload === 1)
                                    Approved
                                @elseif($upload->status_upload === 2)
                                    Rejected
                                @else
                                    {{ $upload->status_upload }}
                                @endif
                            </td>
                                <td>{{ $upload->version }}</td>
                                <td>{{ $upload->owner->name }}</td>
                                <td>

                                    <form id="approve-form-{{ $upload->upload_id }}" action="{{ route('requests.uploads.approve', ['upload' => $upload->upload_id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="button" class="btn btn-success btn-sm approve-btn" form="approve-form-{{ $upload->upload_id }}">Approve</button>
                                    </form>
                                    <form id="reject-form-{{ $upload->upload_id }}" action="{{ route('requests.uploads.reject', ['upload' => $upload->upload_id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="button" class="btn btn-danger btn-sm reject-btn" form="reject-form-{{ $upload->upload_id }}">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.approve-btn').click(function(e) {
                e.preventDefault();
                $('#approveModal').modal('show');
                $('#confirmApprove').attr('form', $(this).closest('form').attr('id'));
            });

            $('.reject-btn').click(function(e) {
                e.preventDefault();
                $('#rejectModal').modal('show');
                $('#confirmReject').attr('form', $(this).closest('form').attr('id'));
            });

            $('#confirmApprove').click(function() {
                $('#' + $(this).attr('form')).submit();
            });

            $('#confirmReject').click(function() {
                $('#' + $(this).attr('form')).submit();
            });
        });
    </script>
@stop