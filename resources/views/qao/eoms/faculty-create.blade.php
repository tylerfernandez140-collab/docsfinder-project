@extends('adminlte::page')

@section('title', 'Add Faculty')

@section('content_header')
    <h1>Add Faculty</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="facultyForm" action="{{ route('qao.eoms.faculty.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" class="form-control" required>
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Designation <span class="text-danger">*</span></label>
                <input type="text" name="designation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Specialization <span class="text-danger">*</span></label>
                <input type="text" name="specialization" class="form-control" required>
            </div>
            <button type="submit" id="saveBtn" class="btn btn-primary" disabled>Save</button>
            <a href="{{ route('qao.eoms.faculty') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    const facultyForm = document.getElementById('facultyForm');
    const saveFacultyBtn = document.getElementById('saveBtn');
    facultyForm.addEventListener('input', () => {
        saveFacultyBtn.disabled = !facultyForm.checkValidity();
    });
</script>

@stop
