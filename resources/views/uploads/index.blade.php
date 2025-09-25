@extends('adminlte::page')

@section('title', 'Manage Documents')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Manage Documents</h1>
    </div>
@stop

@section('content')
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




    <div class="card">
        <div class="card-header">
            <h3>Uploaded List</h3>
            @if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
                <h4 class="text-success">Total Downloads: <strong>{{ $totalDownloads }}</strong></h4>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped documents-table">
                <thead>
                    <tr>
                        <th>Document Title</th>
                        <th>Control Number</th>
                        <th>Status</th>
                        <th>Version</th>
                        <th>Last Modified</th>
                        <th>Uploads This Month</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uploads as $upload)
                        @php $badge = statusBadge($upload->status_upload); @endphp
                        <tr>
                            <td>{{ $upload->title }}</td>
                            <td>{{ $upload->control_number }}</td>
                            <td><span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                            <td>{{ $upload->version }}</td>
                            <td>{{ \Carbon\Carbon::parse($upload->last_modified)->format('F j, Y g:i A') }}</td>
                            <td>{{ $upload->uploads_this_month_count ?? 0 }}</td>
                            <td>{{ $upload->requesting->count() }}</td>
                            <td>{{ \Carbon\Carbon::parse($upload->last_modified)->format('F j, Y g:i A') }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $upload->upload_id }}" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $upload->upload_id }}">
                                        {{-- View --}}
                                        <a class="dropdown-item" href="{{ $upload->upload_id ? route('uploads.view', ['upload_id' => $upload->upload_id]) : '#' }}"><i class="fas fa-eye mr-2"></i> View</a>

                                        {{-- Download --}}
                                        @if(!empty($upload->filename))
                                            <a class="dropdown-item" href="{{ route('documents.download', ['id' => $upload->upload_id]) }}"><i class="fas fa-download mr-2"></i> Download</a>
                                        @endif

                                        {{-- Delete (Super Admin, Admin, Campus DCC) --}}
                                        @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('campus-dcc'))
                                            <form action="{{ route('uploads.destroy', $upload->upload_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash mr-2"></i> Delete</button>
                                            </form>
                                        @endif

                                        {{-- Edit (Super Admin, Admin, Campus DCC, Process Owner) --}}
                                        @if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('campus-dcc') || Auth::user()->hasRole('process-owner'))
                                            <a class="dropdown-item" href="{{ route('uploads.edit', $upload->upload_id) }}"><i class="fas fa-edit mr-2"></i> Edit</a>
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
@stop

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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
    textarea#remarksInput { margin: 0; }
    div#swal2-html-container { overflow: hidden; }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

<script>
$(function () {
    $('.documents-table').DataTable({
        responsive: true,
        autoWidth: false,
        destroy: true,
        language: { search: "Search:", lengthMenu: "Show _MENU_ entries", zeroRecords: "No matching records found" }
    });
});

function confirmStatus(id, type) {
    Swal.fire({
        title: 'Remarks',
        html: `<p>Please provide remarks</p>
               <textarea id="remarksInput" class="swal2-textarea form-control" placeholder="Enter remarks here..."></textarea>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Submit',
        preConfirm: () => {
            const remarks = document.getElementById('remarksInput').value.trim();
            if (!remarks) Swal.showValidationMessage('Remarks are required');
            return remarks;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const remarks = encodeURIComponent(result.value);
            window.location.href = `{{ url('uploads/request_stats') }}/${id}/${type}/${remarks}`;
        }
    });
}
</script>
@stop
