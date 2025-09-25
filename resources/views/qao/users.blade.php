@extends('adminlte::page')

@section('title', 'User Management')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>User Management</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> New User
    </a>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table id="users-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Birthdate</th>
                    <th>Address</th>
                    <th>Administrative Position</th>
                    <th>Designation</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @if(!$user->hasRole('super-admin')) {{-- Exclude super admin --}}
                    <tr>
                        <td>{{ $user->employee_id }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->middle_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->dob)->format('m-d-Y') }}</td>
                        <td>{{ $user->address }}</td>
                        <td>{{ $user->administrative_position }}</td>
                        <td>{{ $user->designation }}</td>
                        <td>
                            @php
                                $displayName = '';
                                if ($user->role->name === 'admin') {
                                    $displayName = 'Admin';
                                } elseif ($user->role->name === 'campus-dcc') {
                                    $displayName = 'Campus DCC';
                                } elseif ($user->role->name === 'process-owner') {
                                    $displayName = 'Process Owner';
                                } else {
                                    $displayName = ucfirst($user->role->name); // Fallback for any other roles
                                }
                            @endphp
                            {{ $displayName }}
                        </td>
                        <td>{{ $user->created_at->format('m-d-Y') }}</td>
                        <td class="d-flex justify-content-center">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning mr-2">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
        $('#users-table').DataTable({
            destroy: true,
            responsive: false,
            autoWidth: false,
            columnDefs: [
                { targets: [3, 4], visible: true } // DOB is column 3, Address is column 4 (0-indexed)
            ],
            language: {
                search: "Search users:",
                lengthMenu: "Show _MENU_ entries",
                zeroRecords: "No matching users found",
            }
        });
    });
</script>
@stop
