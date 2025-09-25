@extends('adminlte::page')

@section('title', 'Audit Logs')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Audit Logs</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left">
                        <th class="px-4 py-2 border-b">User</th>
                        <th class="px-4 py-2 border-b">Role</th>
                        <th class="px-4 py-2 border-b">Action</th>
                        <th class="px-4 py-2 border-b">Resource</th>
                        <th class="px-4 py-2 border-b">Details</th>
                        <th class="px-4 py-2 border-b">Date & Time</th>
                        <th class="px-4 py-2 border-b">IP Address</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-4 py-2 border-b">{{ ucfirst($log->user->role ?? 'N/A') }}</td>
                        <td class="px-4 py-2 border-b">{{ ucfirst($log->action) }}</td>
                        <td class="px-4 py-2 border-b">{{ $log->resource_type ?? '-' }} (ID: {{ $log->resource_id ?? '-' }})</td>
                        <td class="px-4 py-2 border-b">{{ $log->details ?? '-' }}</td>
                        <td class="px-4 py-2 border-b">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-4 py-2 border-b">{{ $log->ip_address ?? request()->ip() }}</td>
                    </tr>
                    @empty
                    {{-- Sample static rows for preview --}}
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">John Doe</td>
                        <td class="px-4 py-2 border-b">Super Admin</td>
                        <td class="px-4 py-2 border-b">Updated Settings</td>
                        <td class="px-4 py-2 border-b">System</td>
                        <td class="px-4 py-2 border-b">Changed site configuration</td>
                        <td class="px-4 py-2 border-b">2025-09-16 14:20:35</td>
                        <td class="px-4 py-2 border-b">192.168.1.10</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">Jane Smith</td>
                        <td class="px-4 py-2 border-b">Admin</td>
                        <td class="px-4 py-2 border-b">Approved Document</td>
                        <td class="px-4 py-2 border-b">Document (ID: 45)</td>
                        <td class="px-4 py-2 border-b">Approved upload request</td>
                        <td class="px-4 py-2 border-b">2025-09-16 14:22:10</td>
                        <td class="px-4 py-2 border-b">192.168.1.12</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">Alex Reyes</td>
                        <td class="px-4 py-2 border-b">Process Owner</td>
                        <td class="px-4 py-2 border-b">Uploaded Document</td>
                        <td class="px-4 py-2 border-b">Document (ID: 46)</td>
                        <td class="px-4 py-2 border-b">Uploaded initial version</td>
                        <td class="px-4 py-2 border-b">2025-09-16 14:25:55</td>
                        <td class="px-4 py-2 border-b">192.168.1.15</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
