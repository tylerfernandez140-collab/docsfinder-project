@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('modals')
<div class="modal fade" id="documentActionsModal" tabindex="-1" role="dialog" aria-labelledby="documentActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentActionsModalLabel">Document Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Always visible for all users --}}
                <a id="viewDocLink" href="#" class="btn btn-primary btn-block mb-2">View Document</a>
                <a id="downloadDocLink" href="#" class="btn btn-success btn-block mb-2">Download Document</a>

                {{-- Process Owners (role 3) --}}
                @if(Auth::user()->hasRole('process-owner'))
                    <a id="requestRevisionLink" href="#" class="btn btn-warning btn-block mb-2">Request Revision</a>
                    <a id="replaceDocLink" href="#" class="btn btn-primary btn-block mb-2">Replace / Re-upload</a>
                    <a id="viewHistoryLogsLink" href="#" class="btn btn-secondary btn-block mb-2">View History Logs</a>
                @endif

                {{-- Campus DCC (role 2) --}}
                @if(Auth::user()->hasRole('campus-dcc'))
                    <a id="approveRevisionLink" href="#" class="btn btn-success btn-block mb-2">Approve Revision Request</a>
                    <a id="rejectRevisionLink" href="#" class="btn btn-danger btn-block mb-2">Reject Revision Request</a>
                    <a id="archiveDocumentLink" href="#" class="btn btn-secondary btn-block mb-2">Archive Document</a>
                    <a id="viewRevisionHistoryLink" href="#" class="btn btn-secondary btn-block mb-2">View Revision History</a>
                @endif

                {{-- Super Admin / Admin (role 0 or 1) --}}
                @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                    <a id="editDocumentLink" href="#" class="btn btn-info btn-block mb-2">Edit Document</a>
                    <a id="manageAccessLink" href="#" class="dropdown-item"><i class="fas fa-users mr-2"></i> Manage Access</a>
                    <a id="moveToControlledLink" href="#" class="dropdown-item"><i class="fas fa-file-contract mr-2"></i> Move to Controlled</a>
                    <a id="moveToUncontrolledLink" href="#" class="dropdown-item"><i class="fas fa-file-alt mr-2"></i> Move to Uncontrolled</a>
                    <form id="deleteDocForm" action="#" method="POST" style="display: block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt mr-2"></i> Delete</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $('#documentActionsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var docId = button.data('docid');
        var docTitle = button.data('doctitle');
        var docViewUrl = button.data('docviewurl');
        var docEditUrl = button.data('docediturl');
        var docDownloadUrl = button.data('docdownloadurl');
        var docRequestRevisionUrl = button.data('docrequestediturl');
        var docReplaceUrl = button.data('docreplaceurl');
        var docViewHistoryLogsUrl = button.data('dochistorylogsurl');
        var docApproveRevisionUrl = button.data('docapproverequesturl');
        var docRejectRevisionUrl = button.data('docrejectrequesturl');
        var docArchiveDocumentUrl = button.data('docarchivedocurl');
        var docViewRevisionHistoryUrl = button.data('docviewrevisionhistoryurl');
        var docEditDocumentUrl = button.data('doceditdocumenturl');
        var docManageAccessUrl = button.data('docmanageaccessurl');
        var docMoveToControlledUrl = button.data('docmovetocontrolledurl');
        var docMoveToUncontrolledUrl = button.data('docmovetouncontrolledurl');
        var docDeleteUrl = button.data('docdeleteurl');
         var docStatusUpload = button.data('docstatusupload');

         updateModalLinks(
             docId,
             docTitle,
             docViewUrl,
             docEditUrl,
             docDownloadUrl,
             docRequestRevisionUrl,
             docReplaceUrl,
             docViewHistoryLogsUrl,
             docApproveRevisionUrl,
             docRejectRevisionUrl,
             docArchiveDocumentUrl,
             docViewRevisionHistoryUrl,
             docEditDocumentUrl,
             docManageAccessUrl,
             docMoveToControlledUrl,
             docMoveToUncontrolledUrl,
             docDeleteUrl,
             docStatusUpload
         );
     });

     // Function to update modal links
     function updateModalLinks(docId, docTitle, docViewUrl, docEditUrl, docDownloadUrl, docRequestRevisionUrl, docReplaceUrl, docViewHistoryLogsUrl, docApproveRevisionUrl, docRejectRevisionUrl, docArchiveDocumentUrl, docViewRevisionHistoryUrl, docEditDocumentUrl, docManageAccessUrl, docMoveToControlledUrl, docMoveToUncontrolledUrl, docDeleteUrl, docStatusUpload) {
         $('#documentTitle').text(docTitle);
         $('#viewDocLink').attr('href', docViewUrl);
         $('#editDocLink').attr('href', docEditUrl);
         $('#downloadDocLink').attr('href', docDownloadUrl);
         $('#requestRevisionLink').attr('href', docRequestRevisionUrl);
         $('#replaceDocLink').attr('href', docReplaceUrl);
         $('#viewHistoryLogsLink').attr('href', docViewHistoryLogsUrl);
         $('#approveRevisionLink').attr('href', docApproveRevisionUrl);
         $('#rejectRevisionLink').attr('href', docRejectRevisionUrl);
         $('#archiveDocumentLink').attr('href', docArchiveDocumentUrl);
         $('#viewRevisionHistoryLink').attr('href', docViewRevisionHistoryUrl);
         $('#editDocumentLink').attr('href', docEditDocumentUrl);
         $('#manageAccessLink').attr('href', docManageAccessUrl);

         // Conditional display for Move to Controlled/Uncontrolled
         if (docStatusUpload == 0) {
             $('#moveToControlledLink').show().attr('href', docMoveToControlledUrl);
             $('#moveToUncontrolledLink').hide();
         } else if (docStatusUpload == 4) {
             $('#moveToControlledLink').hide();
             $('#moveToUncontrolledLink').show().attr('href', docMoveToUncontrolledUrl);
         } else {
             $('#moveToControlledLink').hide();
             $('#moveToUncontrolledLink').hide();
         }

         $('#deleteDocForm').attr('action', docDeleteUrl);
     }


     // Handle delete confirmation for the form submission
     $('#deleteDocForm').on('submit', function(e) {
         
             e.preventDefault(); // Prevent form submission if user cancels
         }
     });
 </script>
@stop



@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Document Title</th>
                        <th>Control Number</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th>Last Modified</th>
                        <th>Downloads</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $doc)
                        <tr>
                            <td>{{ $doc->title }}</td>
                            <td>{{ $doc->control_number }}</td>
                            <td>{{ $doc->file_type }}</td>
                            <td>
                                @if($doc->status_upload == 1)
                                    <span class="badge badge-success">Controlled</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>{{ $doc->owner->name }}</td>
                            <td>{{ $doc->updated_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $doc->numdl }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#documentActionsModal" data-docid="{{ $doc->upload_id }}" data-docviewurl="{{ $doc->upload_id ? route('uploads.view', ['upload_id' => $doc->upload_id]) : '#' }}" data-docediturl="{{ route('uploads.edit', $doc->upload_id) }}" data-docdownloadurl="{{ route('documents.download', ['id' => $doc->upload_id]) }}"
                                 data-docrequestediturl="{{ route('documents.request_edit', ['id' => $doc->upload_id]) }}"
                                  data-docreplaceurl="{{ route('uploads.replace', ['id' => $doc->upload_id]) }}"
                                   data-dochistorylogsurl="{{ route('history.index', ['id' => $doc->upload_id]) }}"
                                    data-docapproverequesturl="{{ route('uploads.request.approve', ['id' => $doc->upload_id]) }}"
                                 data-docrejectrequesturl="{{ route('uploads.request.reject', ['id' => $doc->upload_id]) }}"
                                data-docarchivedocurl="{{ route('uploads.archive', ['id' => $doc->upload_id]) }}"
                                data-doceditmetadataurl="{{ route('uploads.updateMetadata', ['id' => $doc->upload_id]) }}"
                                data-doctitle="{{ $doc->title }}"
                                data-doccontrolnumber="{{ $doc->control_number }}"
                                data-docdeleteurl="{{ route('uploads.destroy', ['id' => $doc->upload_id]) }}"
                                 data-docmanageaccessurl="{{ route('uploads.manageAccess', ['id' => $doc->upload_id]) }}"
                                 data-docviewrevisionhistoryurl="{{ route('history.index', ['id' => $doc->upload_id]) }}"
                                 data-doceditdocumenturl="{{ route('uploads.editDocument', ['id' => $doc->upload_id]) }}"
                                 data-docmovetocontrolledurl="{{ route('uploads.moveToControlled', ['id' => $doc->upload_id]) }}"
                                 data-docmovetouncontrolledurl="{{ route('uploads.moveToUncontrolled', ['id' => $doc->upload_id]) }}"
                                 data-docstatusupload="{{ $doc->status_upload }}"
                                 data-bs-toggle="modal"
                                 data-bs-target="#documentActionsModal">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop