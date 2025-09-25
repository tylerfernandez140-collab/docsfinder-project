@extends('adminlte::page')

@section('title', 'Groups')

@section('content_header')
    <h1>Groups</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Group List</h3>
            <div class="card-tools">
                <a href="{{ route('groups.create') }}" class="btn btn-primary btn-sm">Add New Group</a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Created By Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                        <tr>
                            <td>{{ $group->id }}</td>
                            <td>{{ $group->name }}</td>
                            <td>{{ ucfirst($group->type) }}</td>
                            <td>{{ $group->created_by_role ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('groups.show', $group) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('groups.edit', $group) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('groups.destroy', $group) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this group?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop