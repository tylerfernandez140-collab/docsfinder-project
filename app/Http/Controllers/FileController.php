<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function download($filename)
    {
        $file = Upload::where('filename', $filename)->firstOrFail();
        $user = Auth::user();

        // Check if user has already downloaded the file
        $alreadyDownloaded = DB::table('file_user_downloads')
            ->where('user_id', $user->id)
            ->where('file_id', $file->upload_id)
            ->exists();

        if ($alreadyDownloaded) {
            return back()->with('error', 'You have already downloaded this file.');
        }

        // Log the download
        DB::table('file_user_downloads')->insert([
            'user_id' => $user->id,
            'file_id' => $file->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Serve the file
        $filePath = storage_path('app/public/' . $file->file_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath);
    }
}
