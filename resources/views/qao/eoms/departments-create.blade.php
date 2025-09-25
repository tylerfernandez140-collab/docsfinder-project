@extends('adminlte::page')

@section('title', 'Add Department')

@section('content_header')
    <h1>Add Department</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="departmentForm" action="{{ route('qao.eoms.departments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Department Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">College <span class="text-danger">*</span></label>
                <select name="college_id" class="form-control" required>
                    <option value="">-- Select College --</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">No. of Programs <span class="text-danger">*</span></label>
                <input type="number" name="programs" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Head <span class="text-danger">*</span></label>
                <input type="text" name="head" class="form-control" required>
            </div>
            <button type="submit" id="saveBtn" class="btn btn-primary" disabled>Save</button>
            <a href="{{ route('qao.eoms.departments') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    const deptForm = document.getElementById('departmentForm');
    const saveDeptBtn = document.getElementById('saveBtn');
    deptForm.addEventListener('input', () => {
        saveDeptBtn.disabled = !deptForm.checkValidity();
    });
</script>
@stop
