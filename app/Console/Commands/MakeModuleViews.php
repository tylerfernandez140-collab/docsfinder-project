<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleViews extends Command
{
    protected $signature = 'make:module-views';
    protected $description = 'Generate index.blade.php for EOMS, ProcessManuals, ReferenceManuals, and AuditLogs';

    public function handle()
    {
        $modules = [
            'eoms' => '$eoms',
            'process_manuals' => '$manuals',
            'reference_manuals' => '$manuals',
            'audit_logs' => '$logs'
        ];

        foreach ($modules as $folder => $variable) {
            $path = resource_path("views/{$folder}");
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->info("Created folder: {$path}");
            }

            $filePath = "{$path}/index.blade.php";

            $content = <<<EOD
@extends('layouts.app')

@section('title', ucwords(str_replace('_', ' ', '{$folder}')))

@section('content')
<h1 class="text-2xl font-bold mb-4">{$folder}</h1>

<table class="table-auto w-full border">
    <thead>
        <tr class="bg-gray-200">
            <th class="px-4 py-2">Title</th>
            <th class="px-4 py-2">Control Number</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Version</th>
            <th class="px-4 py-2">Revisions</th>
            <th class="px-4 py-2">Owner</th>
            <th class="px-4 py-2">Downloads</th>
        </tr>
    </thead>
    <tbody>
        @foreach({$variable} as \$item)
        <tr class="border-t">
            <td class="px-4 py-2">{{ \$item->title }}</td>
            <td class="px-4 py-2">{{ \$item->control_number }}</td>
            <td class="px-4 py-2">{{ ucfirst(\$item->status) }}</td>
            <td class="px-4 py-2">{{ \$item->version ?? '-' }}</td>
            <td class="px-4 py-2">{{ \$item->revisions ?? 0 }}</td>
            <td class="px-4 py-2">{{ \$item->owner->name ?? 'N/A' }}</td>
            <td class="px-4 py-2">{{ \$item->numdl ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
EOD;

            File::put($filePath, $content);
            $this->info("Created Blade view: {$filePath}");
        }

        $this->info('All module index views have been created successfully!');
    }
}
