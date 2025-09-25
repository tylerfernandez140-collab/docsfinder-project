@extends('adminlte::page')

@section('title', 'Add Course')

@section('content_header')
    <h1>Add Course</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="courseForm" action="{{ route('qao.eoms.courses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Course Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Course Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Program <span class="text-danger">*</span></label>
                <select name="program_id" class="form-control" required>
                    <option value="">-- Select Program --</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Units <span class="text-danger">*</span></label>
                <input type="number" name="units" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Instructor <span class="text-danger">*</span></label>
                <input type="text" name="instructor" class="form-control" required>
            </div>
            <button type="submit" id="saveBtn" class="btn btn-primary" disabled>Save</button>
            <a href="{{ route('qao.eoms.courses') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    const courseForm = document.getElementById('courseForm');
    const saveCourseBtn = document.getElementById('saveBtn');
    courseForm.addEventListener('input', () => {
        saveCourseBtn.disabled = !courseForm.checkValidity();
    });
</script>
@stop
