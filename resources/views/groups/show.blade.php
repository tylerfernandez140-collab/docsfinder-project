@extends('adminlte::page')

@section('title', 'View Group')

@section('content_header')
    <h1>View Group: {{ $group->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Group Details</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $group->id }}</p>
            <p><strong>Name:</strong> {{ $group->name }}</p>
            <p><strong>Type:</strong> {{ ucfirst($group->type) }}</p>
            <p><strong>Created By Role:</strong> {{ $group->created_by_role ?? 'N/A' }}</p>
            <p><strong>Created At:</strong> {{ $group->created_at }}</p>
            <p><strong>Updated At:</strong> {{ $group->updated_at }}</p>

            <h4>Group Members</h4>
            @if($group->users->isEmpty())
                <p>No members in this group.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group->users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <form action="{{ route('groups.remove-user', ['group' => $group->id, 'user' => $user->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this user from the group?');">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($group->type === 'manual')
                <h4 class="mt-4">Add User to Group</h4>
                <form action="{{ route('groups.add-user', $group) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Select User</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">-- Select User --</option>
                            @foreach(\App\Models\User::all() as $user)
                                @if(!$group->users->contains($user->id))
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            @endif

            <a href="{{ route('groups.index') }}" class="btn btn-secondary mt-3">Back to Groups</a>
        </div>
    </div>
@stop