@extends('adminlte::page')

@section('title', 'Search Documents')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Search Documents</h1>
    </div>
@stop

@section('content')

{{-- Live Search Filter --}}
<div class="mb-4">
    <div class="input-group input-group-lg shadow-sm">
        <div class="input-group-prepend">
            <span class="input-group-text bg-white border-right-0">
                <i class="fas fa-search text-muted"></i>
            </span>
        </div>
        <input 
            type="text" 
            id="fileSearchInput" 
            class="form-control border-left-0" 
            placeholder="Search files..."
        >
    </div>
</div>

{{-- File Cards --}}
<div id="fileCards">
    @foreach ($files as $file)
        <div class="card shadow-sm mb-4 file-card">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt text-primary mr-2"></i> File Information
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th class="text-muted" style="width: 150px;">Filename</th>
                            <td>{{ $file->filename }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Size</th>
                            <td>{{ number_format($file->size / 1024, 2) }} KB</td>
                        </tr>
                        <tr>
                            <th class="text-muted">File Type</th>
                            <td>{{ $file->file_type }}</td>
                        </tr>
                        @if (Auth::user()->role != 3)
                            <tr>
                                <th class="text-muted">Uploaded by</th>
                                <td>{{ $file->name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class="text-muted">Uploaded Date</th>
                            <td>{{ \Carbon\Carbon::parse($file->created_at)->format('F j, Y g:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-right">
                <a href="uploads/uploads/download/{{ str_replace('uploads/', '', $file->path) }}/{{ $file->upload_id }}" class="btn btn-primary">
                    <i class="fas fa-download mr-1"></i> Download File
                </a>
            </div>
        </div>
    @endforeach
</div>

{{-- No results message --}}
<div id="noResultsMessage" class="alert alert-warning text-center d-none">
    <i class="fas fa-exclamation-circle mr-1"></i> No matching files found.
</div>

@stop

@section('css')
    <style>
        #noResultsMessage {
            font-size: 1.1rem;
        }
    </style>
@stop

@section('js')
    <script>
        document.getElementById('fileSearchInput').addEventListener('keyup', function () {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.file-card');
            let anyVisible = false;

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                const isVisible = text.includes(query);
                card.style.display = isVisible ? '' : 'none';
                if (isVisible) anyVisible = true;
            });

            const noResults = document.getElementById('noResultsMessage');
            if (!anyVisible) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        });
    </script>
@stop
