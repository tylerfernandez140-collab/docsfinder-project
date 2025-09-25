@extends('adminlte::page')

@section('title', isset($upload) ? 'Reupload Document' : 'Upload Document')

@section('content_header')
    <h1>{{ isset($upload) ? 'Reupload Document' : 'Upload Document' }}</h1>
@stop

@section('content')
<div class="card card-primary">
   

    <form action="{{ isset($upload) ? route('uploads.update', ['id' => $upload->upload_id]) : route('uploads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($upload))
            @method('PUT')
        @endif

        <div class="card-body">
            <div class="form-group">
                <label for="title">Document Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $upload->title ?? '') }}" {{ isset($upload) ? 'disabled' : '' }} required>
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="control_number">Control Number <span class="text-danger">*</span></label>
                <input type="text" name="control_number" id="control_number" class="form-control @error('control_number') is-invalid @enderror" value="{{ old('control_number', $upload->control_number ?? '') }}" required>
                @error('control_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Document Type <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" {{ isset($upload) ? 'disabled' : '' }} required>
                    <option value="">Select Document Type</option>
                    <option value="Process Manual" {{ old('type', $upload->type ?? '') == 'process manual' ? 'selected' : '' }}>Process Manual</option>
                    <option value="Reference Manual" {{ old('type', $upload->type ?? '') == 'reference manual' ? 'selected' : '' }}>Reference Manual</option>
                    <option value="Form" {{ old('type', $upload->type ?? '') == 'form' ? 'selected' : '' }}>Form</option>
                </select>
                @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="version">Version</label>
                <input type="text" name="version" id="version" class="form-control @error('version') is-invalid @enderror" value="{{ old('version', $upload->version ?? '') }}">
                @error('version')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            

            <div class="form-group">
                <label for="file" class="form-label text-secondary fw-medium">
                    Select a file to upload <span class="text-danger">*</span>
                </label>

                <input 
                    type="file" 
                    class="form-control form-control-lg @error('file') is-invalid @enderror" 
                    id="file" 
                    name="file" 
                    required
                    onchange="previewFile()"
                >

                @error('file')
                    <div class="invalid-feedback d-block mt-2">
                        <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                    </div>
                @enderror

                <div id="filePreview"></div>

                @if(!empty($upload->path))
                    <div class="mt-4 p-3 border rounded bg-light d-flex align-items-center justify-content-between">
                        <div class="text-muted">
                            <i class="bi bi-file-earmark-text-fill me-2"></i>
                            <span>Current file:</span>
                            <a href="{{ Storage::url($upload->path) }}" target="_blank" class="fw-semibold text-decoration-none text-primary">
                                View Uploaded Document
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-footer">
            @if(Auth::user()->hasRole('admin'))
                <button type="submit" class="btn btn-primary">
                    {{ isset($upload) ? 'Reupload Document' : 'Upload Document' }}
                </button>
            @endif
        </div>
    </form>
</div>
@stop


@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth.js/1.8.0/mammoth.browser.min.js"></script>
<script>
    function previewFile() {
        const preview = document.getElementById('filePreview');
        const file = document.getElementById('file').files[0];
        preview.innerHTML = ''; // Clear previous preview

        if (!file) return;

        console.log('File selected:', file);
        console.log('File type:', file.type);
        console.log('File name:', file.name);

        const fileName = document.createElement('p');
        fileName.textContent = `Selected file: ${file.name}`;
        fileName.classList.add('fw-semibold');
        preview.appendChild(fileName);

        const fileType = file.type;
        const lowerName = file.name.toLowerCase();
        
        if(fileType === 'application/pdf') {
                const pdfEmbed = document.createElement('embed');
                pdfEmbed.src = URL.createObjectURL(file);
                pdfEmbed.type = 'application/pdf';
                pdfEmbed.width = '100%';
                pdfEmbed.height = '400px';
                preview.appendChild(pdfEmbed);
            } else if(fileType.startsWith('image/')) {
                const imgPreview = document.createElement('img');
                imgPreview.src = URL.createObjectURL(file);
                imgPreview.style.maxWidth = '100%';
                imgPreview.style.maxHeight = '400px';
                imgPreview.classList.add('mt-2');
                preview.appendChild(imgPreview);
            } else {
                // For Word, Excel, PowerPoint, and other docs
                const icon = document.createElement('i');
                icon.style.fontSize = '3rem';
                icon.style.color = '#6c757d';
                icon.classList.add('me-2');

                if(lowerName.endsWith('.doc') || lowerName.endsWith('.docx')) {
                    icon.classList.add('bi', 'bi-file-earmark-word');
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        mammoth.convertToHtml({arrayBuffer: event.target.result})
                            .then(function(result) {
                                console.log('Mammoth conversion result:', result);
                                const docxPreview = document.createElement('div');
                                docxPreview.innerHTML = result.value;
                                docxPreview.style.maxWidth = '100%';
                                docxPreview.style.maxHeight = '400px';
                                docxPreview.style.overflowY = 'auto';
                                docxPreview.classList.add('mt-2', 'p-3', 'border', 'rounded', 'bg-light');
                                preview.appendChild(docxPreview);
                            })
                            .catch(function(error) {
                                console.error('Mammoth conversion error:', error);
                                const errorDiv = document.createElement('div');
                                errorDiv.textContent = 'Error converting document: ' + error.message;
                                errorDiv.style.color = 'red';
                                preview.appendChild(errorDiv);
                            })
                            .done();
                    };
                    reader.readAsArrayBuffer(file);
                } else if(lowerName.endsWith('.xls') || lowerName.endsWith('.xlsx')) {
                    icon.classList.add('bi', 'bi-file-earmark-spreadsheet');
                } else if(lowerName.endsWith('.ppt') || lowerName.endsWith('.pptx')) {
                    icon.classList.add('bi', 'bi-file-earmark-ppt');
                } else {
                    icon.classList.add('bi', 'bi-file-earmark');
                }

                const div = document.createElement('div');
                div.classList.add('d-flex', 'align-items-center', 'mt-2');
                div.appendChild(icon);
                const span = document.createElement('span');
                span.textContent = file.name;
                span.classList.add('fw-semibold', 'ms-2');
                div.appendChild(span);

                preview.appendChild(div);
            }
    }
</script>
@stop
