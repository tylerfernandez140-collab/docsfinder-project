@extends('adminlte::page')

@section('title', 'System Preferences')

@section('content_header')
    <h1>Configure System Preferences</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf


            <div class="form-group">
                <label for="system_name">System Name</label>
                <input type="text" class="form-control" name="system_name" value="{{ $settings['system_name'] ?? '' }}">
            </div>

            <div class="form-group">
                <label for="default_role">Default User Role</label>
                <select name="default_role" class="form-control">
                    <option value="1" {{ ($settings['default_role'] ?? '') == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="2" {{ ($settings['default_role'] ?? '') == 2 ? 'selected' : '' }}>Campus DCC</option>
                    <option value="3" {{ ($settings['default_role'] ?? '') == 3 ? 'selected' : '' }}>Process Owner</option>
                </select>
            </div>

            <div class="form-group">
                <label for="timezone">Timezone</label>
                <input type="text" class="form-control" name="timezone" value="{{ $settings['timezone'] ?? 'Asia/Manila' }}">
            </div>

            <div class="form-group">
                <label for="theme">Theme</label>
                <select name="theme" class="form-control">
                    <option value="light" {{ ($settings['theme'] ?? '') == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ ($settings['theme'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Preferences</button>
        </form>
    </div>
</div>
@stop
