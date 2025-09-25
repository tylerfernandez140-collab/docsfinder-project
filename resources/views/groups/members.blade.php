@extends('adminlte::page')

@section('title', 'Create Group Member')

@section('content_header')
    <h1>Create Group Member</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('groups.store_member') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <!-- Choose Member -->
                <div class="col-md-6">
                    <label for="member" class="form-label">Choose Member</label>
                    <select class="form-control @error('member') is-invalid @enderror" name="member" id="member" required>
                        <option value="">-- Choose Member --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('member') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('member')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Choose Group -->
                <div class="col-md-6">
                    <label for="groups" class="form-label">Choose Group</label>
                    <select class="form-control @error('groups') is-invalid @enderror" name="groups" id="groups" required>
                        <option value="">-- Choose Group --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('groups') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('groups')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success"><i class="fas fa-plus-circle me-1"></i> Add Member</button>
            </div>
        </form>
    </div>
</div>
@stop
