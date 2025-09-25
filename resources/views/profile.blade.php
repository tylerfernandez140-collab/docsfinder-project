@extends('adminlte::page')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', 'My Profile')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">Account Information</h3>
    </div>
    <div class="card-body">
        @php
            $user = Auth::user();
            $role = $user->role;
        @endphp

        <div class="row">
            <div class="col-md-3 text-center">
                <img src="{{ asset('images/profile-placeholder.png') }}" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ 
                    $role == 0 ? 'Super Admin' :
                    ($role == 1 ? 'Admin' :
                    ($role == 2 ? 'Campus DCC' :
                    ($role == 3 ? 'Process Owner' : 'User')))
                }}</p>
            </div>
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Account Information</h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Full Name</dt>
                            <dd class="col-sm-8">{{ $user->name }}</dd>

                            <dt class="col-sm-4">Username / Login ID</dt>
                            <dd class="col-sm-8">{{ $user->username ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">Email Address</dt>
                            <dd class="col-sm-8">{{ $user->email }}</dd>

                            <dt class="col-sm-4">Contact Number</dt>
                            <dd class="col-sm-8">{{ $user->contact_number ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">Role</dt>
                            <dd class="col-sm-8">{{ 
                                $role == 0 ? 'Super Admin' :
                                ($role == 1 ? 'Admin' :
                                ($role == 2 ? 'Campus DCC' :
                                ($role == 3 ? 'Process Owner' : 'User')))
                            }}</dd>

                            @if($role == 0 || $role == 1) {{-- Super Admin or Admin --}}
                                <dt class="col-sm-4">Assigned Campuses / Departments</dt>
                                <dd class="col-sm-8">{{ $user->assigned_campuses ?? 'All' }}</dd>
                            @elseif($role == 2) {{-- Campus DCC --}}
                                <dt class="col-sm-4">Campus Assigned</dt>
                                <dd class="col-sm-8">{{ $user->campus_assigned ?? 'N/A' }}</dd>
                            @elseif($role == 3) {{-- Process Owner --}}
                                <dt class="col-sm-4">Department / Office Assigned</dt>
                                <dd class="col-sm-8">{{ $user->department_assigned ?? 'N/A' }}</dd>
                            @endif

                            <dt class="col-sm-4">Last Login Time & Date</dt>
                            <dd class="col-sm-8">{{ $user->last_login_at ? $user->last_login_at->format('F d, Y h:i A') : 'N/A' }}</dd>

                            <dt class="col-sm-4">Account Status</dt>
                            <dd class="col-sm-8">{{ $user->account_status ?? 'Active' }}</dd>
                        </dl>
                    </div>
                </div>

                @if($role == 0) {{-- Super Admin --}}
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">System Activity Summary</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Total Admins Created</dt>
                                <dd class="col-sm-8">{{ $user->total_admins_created ?? 0 }}</dd>
                                <dt class="col-sm-4">Total Documents Managed</dt>
                                <dd class="col-sm-8">{{ $user->total_documents_managed ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                @elseif($role == 1) {{-- Admin --}}
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Activity Summary</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Users Managed</dt>
                                <dd class="col-sm-8">{{ $user->users_managed ?? 0 }}</dd>
                                <dt class="col-sm-4">Documents Approved</dt>
                                <dd class="col-sm-8">{{ $user->documents_approved ?? 0 }}</dd>
                                <dt class="col-sm-4">Documents Pending</dt>
                                <dd class="col-sm-8">{{ $user->documents_pending ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                @elseif($role == 2) {{-- Campus DCC --}}
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Document Overview</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Pending Documents for Review</dt>
                                <dd class="col-sm-8">{{ $user->pending_documents_review ?? 0 }}</dd>
                                <dt class="col-sm-4">Total Documents Reviewed</dt>
                                <dd class="col-sm-8">{{ $user->total_documents_reviewed ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                @elseif($role == 3) {{-- Process Owner --}}
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Document Stats</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Documents Uploaded</dt>
                                <dd class="col-sm-8">{{ $user->documents_uploaded ?? 0 }}</dd>
                                <dt class="col-sm-4">Pending Approvals</dt>
                                <dd class="col-sm-8">{{ $user->pending_approvals ?? 0 }}</dd>
                                <dt class="col-sm-4">Approved Documents</dt>
                                <dd class="col-sm-8">{{ $user->approved_documents ?? 0 }}</dd>
                                <dt class="col-sm-4">Rejected Documents</dt>
                                <dd class="col-sm-8">{{ $user->rejected_documents ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                @endif

                <div class="card-footer">
                    <a href="#" class="btn btn-primary">Edit Profile</a>
                    <a href="#" class="btn btn-secondary">Change Password</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
