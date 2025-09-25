@extends('adminlte::page')

@section('title', 'Distributed Documents')

@section('content_header')
    <h1>Distributed Documents</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recently Distributed Documents</h3>
                </div>
                <div class="card-body">
                    <table id="distributed-documents-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Document Title</th>
                                <th>Control Number</th>
                                <th>Status (Pending / Distributed)</th>
                                <th>Distributed To (Campus/Department)</th>
                                <th>Distributed Date</th>
                                <th>Last Modified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($distributedDocuments as $document)
                                <tr>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->control_number }}</td>
                                    <td>
                                        @php
                                            $statusBadge = ['class' => '', 'label' => ''];
                                            if ($document->status_distribution == 0) {
                                                $statusBadge = ['class' => 'badge-warning', 'label' => 'Pending'];
                                            } elseif ($document->status_distribution == 1) {
                                                $statusBadge = ['class' => 'badge-success', 'label' => 'Distributed'];
                                            }
                                        @endphp
                                        <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                                    </td>
                                    <td>{{ $document->distributed_to }}</td>
                                    <td>{{ \Carbon\Carbon::parse($document->distributed_date)->format('F j, Y g:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($document->last_modified)->format('F j, Y g:i A') }}</td>
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
            $('#distributed-documents-table').DataTable({
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