@extends('adminlte::page')

@section('title', isset($user) ? 'Edit User' : 'Create New User')

@section('content_header')
    <h1>{{ isset($user) ? 'Edit User' : 'Create New User' }}</h1>
@stop

@section('content')
<div class="card card-primary">
    <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
        @csrf
        @if(isset($user))
            @method('PUT')
        @endif

        <div class="card-body">
            <!-- Employee ID -->
            <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                    id="employee_id" name="employee_id"
                    placeholder="Enter Employee ID"
                    value="{{ old('employee_id', $user->employee_id ?? '') }}" required>
                @error('employee_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Full Name -->
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                    id="first_name" name="first_name"
                    placeholder="Enter first name"
                    value="{{ old('first_name', $user->first_name ?? '') }}" required>
                @error('first_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="middle_name">Middle Name (Optional)</label>
                <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                    id="middle_name" name="middle_name"
                    placeholder="Enter middle name"
                    value="{{ old('middle_name', $user->middle_name ?? '') }}">
                @error('middle_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                    id="last_name" name="last_name"
                    placeholder="Enter last name"
                    value="{{ old('last_name', $user->last_name ?? '') }}" required>
                @error('last_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Administrative Position -->
            <div class="form-group">
                <label for="administrative_position">Administrative Position</label>
                <input type="text" class="form-control @error('administrative_position') is-invalid @enderror"
                    id="administrative_position" name="administrative_position"
                    placeholder="Enter administrative position"
                    value="{{ old('administrative_position', $user->administrative_position ?? '') }}">
                @error('administrative_position')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Designation -->
            <div class="form-group">
                <label for="designation">Designation</label>
                <select class="form-control @error('designation') is-invalid @enderror"
                    id="designation" name="designation">
                    <option value="">Select Designation</option>
                    <option value="Lingayen Campus (Main Campus)" {{ (old('designation', $user->designation ?? '') == 'Lingayen Campus (Main Campus)') ? 'selected' : '' }}>Lingayen Campus (Main Campus)</option>
                    <option value="Alaminos City Campus" {{ (old('designation', $user->designation ?? '') == 'Alaminos City Campus') ? 'selected' : '' }}>Alaminos City Campus</option>
                    <option value="Asingan Campus" {{ (old('designation', $user->designation ?? '') == 'Asingan Campus') ? 'selected' : '' }}>Asingan Campus</option>
                    <option value="Bayambang Campus" {{ (old('designation', $user->designation ?? '') == 'Bayambang Campus') ? 'selected' : '' }}>Bayambang Campus</option>
                    <option value="Binmaley Campus" {{ (old('designation', $user->designation ?? '') == 'Binmaley Campus') ? 'selected' : '' }}>Binmaley Campus</option>
                    <option value="Infanta Campus" {{ (old('designation', $user->designation ?? '') == 'Infanta Campus') ? 'selected' : '' }}>Infanta Campus</option>
                    <option value="San Carlos City Campus" {{ (old('designation', $user->designation ?? '') == 'San Carlos City Campus') ? 'selected' : '' }}>San Carlos City Campus</option>
                    <option value="Santa Maria Campus" {{ (old('designation', $user->designation ?? '') == 'Santa Maria Campus') ? 'selected' : '' }}>Santa Maria Campus</option>
                    <option value="Urdaneta City Campus" {{ (old('designation', $user->designation ?? '') == 'Urdaneta City Campus') ? 'selected' : '' }}>Urdaneta City Campus</option>
                </select>
                @error('designation')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email"
                    placeholder="Enter email"
                    value="{{ old('email', $user->email ?? '') }}" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- DOB -->
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control @error('dob') is-invalid @enderror"
                    id="dob" name="dob"
                    value="{{ old('dob', $user->dob ?? '') }}">
                @error('dob')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                    id="address" name="address"
                    placeholder="Enter address">{{ old('address', $user->address ?? '') }}</textarea>
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role_id">User Type</label>
                <select class="form-control @error('role_id') is-invalid @enderror"
                    id="role_id" name="role_id" required>
                    <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Select User Type</option>
                    @foreach($roles as $role)
                        @php
                            $displayName = '';
                            if ($role->name === 'admin') {
                                $displayName = 'University DCC – Admin';
                            } elseif ($role->name === 'campus-dcc') {
                                $displayName = 'Campus DCC';
                            } elseif ($role->name === 'process-owner') {
                                $displayName = 'Process Owner';
                            } else {
                                $displayName = ucfirst($role->name); // Fallback for any other roles
                            }
                        @endphp
                        <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id ?? '') == $role->id) ? 'selected' : '' }}>{{ $displayName }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">
                    Password {{ isset($user) ? '(Leave blank to keep current)' : '' }}
                </label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                    id="password" name="password"
                    placeholder="Enter password" {{ isset($user) ? '' : 'required' }}>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control"
                    id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm password" {{ isset($user) ? '' : 'required' }}>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update User' : 'Create User' }}</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('User form loaded'); </script>
@stop
