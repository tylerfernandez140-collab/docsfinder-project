@extends('adminlte::page')

@section('title', 'User Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>My History Logs</h1>
    </div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table id="history-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    @if (Auth::user()->role <= 1)
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>User Type</th>
                    @endif
                    <th>Activity Log</th>
                    <th>Log Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $history)
                <tr>
                    @if (Auth::user()->role <= 1)
                        <td>{{ $history->employee_id }}</td>
                        <td>{{ $history->name }}</td>
                        <th>                
                           @php
    switch ($history->role) {
        case 3:
            $role = 'Process Owners (Faculty)';
            break;
        case 2:
            $role = 'Campus DCC';
            break;
        case 1:
            $role = 'Admin (OVPQA)';
            break;
        default:
            $role = 'Unknown Role';
    }
@endphp


                            {{ $role }}
                        </th>
                    @endif
                    <td>{{ $history->user_activity }}</td>
                    <td>{{ \Carbon\Carbon::parse($history->created_at)->format('F j, Y g:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function () {
            $('#history-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    search: "Search users:",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "No matching users found",
                }
            });
        });
    </script>
@stop
