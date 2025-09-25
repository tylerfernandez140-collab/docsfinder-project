@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
    <h1>Change Password</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('account.password.update') }}">
            @csrf

            <div class="form-group">
                <label for="current_password">Employee ID</label>
                <input type="text" name="empid" class="form-control" value="{{ $user->employee_id }}" required>
            </div>

            <div class="form-group">
                <label for="current_password">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

           <div class="form-group">
                <label for="current_password">Birth Date</label>
                <input type="date" name="dob" class="form-control" value="{{ $user->dob }}" required>
            </div>


            <div class="form-group">
                <label for="current_password">Address</label>
                <input type="text" name="address" class="form-control" value="{{ $user->address }}" required>
            </div>

            <div class="form-group">
                <label for="current_password">Email</label>
                <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>


            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</div>
@stop
