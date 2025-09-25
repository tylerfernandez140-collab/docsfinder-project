@extends('adminlte::page')

@section('title', 'Add College')

@section('content_header')
    <h1>Add College</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="collegeForm" action="{{ route('qao.eoms.colleges.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">No. of Programs <span class="text-danger">*</span></label>
                <input type="number" name="programs" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Accreditation <span class="text-danger">*</span></label>
                <input type="text" name="accreditation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">QA <span class="text-danger">*</span></label>
                <input type="text" name="qa" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Coordinator <span class="text-danger">*</span></label>
                <input type="text" name="coordinator" class="form-control" required>
            </div>
            <button type="submit" id="saveBtn" class="btn btn-primary" disabled>Save</button>
            <a href="{{ route('qao.eoms.colleges') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    const collegeForm = document.getElementById('collegeForm');
    const saveCollegeBtn = document.getElementById('saveBtn');
    collegeForm.addEventListener('input', () => {
        saveCollegeBtn.disabled = !collegeForm.checkValidity();
    });
</script>
@stop
