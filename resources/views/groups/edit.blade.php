@extends('adminlte::page')

@section('title', 'Edit Group')

@section('content_header')
    <h1>Edit Group: {{ $group->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('groups.update', $group) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Group Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $group->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="type">Group Type</label>
                    <select name="type" id="type" class="form-control" onchange="toggleGroupType()" required>
                        <option value="manual" {{ old('type', $group->type) == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="role_based" {{ old('type', $group->type) == 'role_based' ? 'selected' : '' }}>Role-Based</option>
                    </select>
                </div>

                <div class="form-group" id="role_selection" style="display: {{ old('type', $group->type) == 'role_based' ? 'block' : 'none' }};">
                    <label for="created_by_role">Select Role</label>
                    <select name="created_by_role" id="created_by_role" class="form-control">
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('created_by_role', $group->created_by_role) == $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" id="user_selection" style="display: {{ old('type', $group->type) == 'manual' ? 'block' : 'none' }};">
                    <label for="users">Select Users (for Manual Group)</label>
                    <select name="users[]" id="users" class="form-control" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('users', $group->users->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $user->name }} ({{ roleName($user->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Group</button>
                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        function toggleGroupType() {
            var type = document.getElementById('type').value;
            if (type === 'role_based') {
                document.getElementById('role_selection').style.display = 'block';
                document.getElementById('user_selection').style.display = 'none';
            } else {
                document.getElementById('role_selection').style.display = 'none';
                document.getElementById('user_selection').style.display = 'block';
            }
        }
        function roleName(roleId) {
            switch (roleId) {
                case 0: return 'User';
                case 1: return 'Admin';
                case 2: return 'Campus DCC';
                case 3: return 'Process Owner';
                default: return 'Unknown';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleGroupType(); // Call on page load to set initial state
        });
    </script>
@stop