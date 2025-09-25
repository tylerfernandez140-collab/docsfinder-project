@extends('adminlte::page')

@extends('adminlte::page')

@section('title', 'Audit Logs')

@section('content_header')
    <h1>Audit Logs</h1>
@stop

@section('content')
<h1 class="text-xl font-bold mb-4">Audit Logs</h1>
<div class="bg-white p-6 rounded-lg shadow">
    <table class="table-auto w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">User</th>
                <th class="px-4 py-2 border">Action</th>
                <th class="px-4 py-2 border">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border">{{ $log->user->name ?? 'N/A' }}</td>
                <td class="px-4 py-2 border">{{ $log->action }}</td>
                <td class="px-4 py-2 border">{{ $log->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
