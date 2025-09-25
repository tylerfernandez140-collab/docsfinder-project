@extends('adminlte::page')

@section('title', 'My Documents')

@section('content_header')
    <h1>My Documents</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recently Viewed Documents</h3>
                </div>
                <div class="card-body">
                    <table id="my-documents-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Document Title</th>
                                <th>Control Number</th>
                                <th>Assigned By (Campus DCC)</th>
                                <th>Status (Pending Feedback / Controlled)</th>
                                <th>Last Viewed</th>
                                <th>Feedback History</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myDocuments as $document)
                                <tr>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->control_number }}</td>
                                    <td>{{ $document->assigned_by_dcc ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusBadge = ['class' => '', 'label' => ''];
                                            if ($document->status_feedback == 0) {
                                                $statusBadge = ['class' => 'badge-warning', 'label' => 'Pending Feedback'];
                                            } elseif ($document->status_feedback == 1) {
                                                $statusBadge = ['class' => 'badge-success', 'label' => 'Controlled'];
                                            }
                                        @endphp
                                        <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($document->last_viewed)->format('F j, Y g:i A') }}</td>
                                    <td>
                                        @if($document->feedback_history)
                                            <a href="#" class="btn btn-info btn-sm">View History</a>
                                        @else
                                            N/A
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
@stop

@section('css')
    <link rel="stylesheet" href="/vendor/datatables/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $('#my-documents-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@stop