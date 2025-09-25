@extends('adminlte::page')

@section('title', 'Add Program')

@section('content_header')
    <h1>Add Program</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="programForm" action="{{ route('qao.eoms.programs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Program <span class="text-danger">*</span></label>
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
                <label class="form-label">Level <span class="text-danger">*</span></label>
                <input type="text" name="level" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Accreditation <span class="text-danger">*</span></label>
                <input type="text" name="accreditation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Coordinator <span class="text-danger">*</span></label>
                <input type="text" name="coordinator" class="form-control" required>
            </div>
            <button type="submit" id="saveBtn" class="btn btn-primary" disabled>Save</button>
            <a href="{{ route('qao.eoms.programs') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    const programForm = document.getElementById('programForm');
    const saveProgramBtn = document.getElementById('saveBtn');
    programForm.addEventListener('input', () => {
        saveProgramBtn.disabled = !programForm.checkValidity();
    });
</script>
@stop
