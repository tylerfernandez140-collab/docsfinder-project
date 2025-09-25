<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Requesting;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        Log::info('User in UploadController index: ' . json_encode($user));

        $latestUploads = Upload::select('control_number', DB::raw('MAX(created_at) as latest_created_at'))
            ->where('is_archived', false)
            ->groupBy('control_number');

        $uploadsQuery = Upload::with('owner')
            ->joinSub($latestUploads, 'latest_uploads', function ($join) {
                $join->on('uploads.control_number', '=', 'latest_uploads.control_number')
                     ->on('uploads.created_at', '=', 'latest_uploads.latest_created_at');
            })
            ->where('uploads.is_archived', false);

        if ($user->role->id == 1) { // Admin
            $uploadsQuery->where('uploads.user_id', $user->id);
        } else if ($user->role->id == 2) { // Campus DCC
            // Campus DCC sees all documents that are pending distribution (status_upload = 4 and status_distribution = 0)
            $uploadsQuery->where('uploads.status_upload', 4)
                         ->where('uploads.status_distribution', 0);
        } else if ($user->role->id == 3) { // Process Owner
            // Process Owner sees documents assigned to them
            $uploadsQuery->whereJsonContains('uploads.distributed_to_process_owner', (string)$user->id);
        }
        
        $uploads = $uploadsQuery->get();

        $uploads->each(function ($upload) {
            $upload->uploads_this_month_count = Upload::where('control_number', $upload->control_number)
                                                    ->whereYear('created_at', now()->year)
                                                    ->whereMonth('created_at', now()->month)
                                                    ->count();
        });
        Log::info('Uploads fetched in UploadController index: ' . $uploads->count() . ' records');

        // Admin specific counts
        if ($user->role->id == 1) {
            $totalUploads = Upload::where('user_id', $user->id)
                ->where('is_archived', false)
                ->count();
            $uploadsThisMonth = Upload::where('user_id', $user->id)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->where('is_archived', false)
                ->count();
            $pendingReviewByCampusDCC = Upload::where('user_id', $user->id)
                ->where('status_upload', 0) // Assuming 0 means pending review
                ->where('is_archived', false)
                ->count();
        }

        // Super Admin specific counts
        if ($user->role->id == 0) {
            $totalDocuments = Upload::where('is_archived', false)->count();
            $controlledDocuments = Upload::where('status_upload', 4) // Assuming 4 means controlled
                ->where('is_archived', false)
                ->count();
            $pendingApprovals = Upload::where('status_upload', 0) // Assuming 0 means pending approval
                ->where('is_archived', false)
                ->count();
        }

        // Campus DCC specific counts
        if ($user->role->id == 2) {
            $pendingDistributions = Upload::where('status_upload', 4) // Controlled documents
                ->where('status_distribution', 0) // Pending distribution
                ->where('is_archived', false)
                ->count();
            $totalDistributed = Upload::where('distributed_by_user_id', $user->id)
                ->where('status_distribution', 1) // Distributed
                ->where('is_archived', false)
                ->count();
            $distributedThisMonth = Upload::where('distributed_by_user_id', $user->id)
                ->where('status_distribution', 1) // Distributed
                ->whereYear('distributed_at', now()->year)
                ->whereMonth('distributed_at', now()->month)
                ->where('is_archived', false)
                ->count();
        }

        // Process Owner specific counts
        if ($user->role->id == 3) {
            $documentsAssignedToMe = Upload::whereJsonContains('distributed_to_process_owner', (string)$user->id)
                ->where('is_archived', false)
                ->count();
        }

        $totalDownloads = Upload::whereIn('upload_id', function ($query) use ($latestUploads) {
            $query->select('upload_id')
                ->fromSub($latestUploads, 'lu')
                ->join('uploads', function ($join) {
                    $join->on('uploads.control_number', '=', 'lu.control_number')
                        ->on('uploads.created_at', '=', 'lu.latest_created_at');
                });
        })->sum('numdl');
        Log::info('Total downloads calculated: ' . $totalDownloads);

         $totalDocuments = Upload::whereIn('upload_id', function ($query) use ($latestUploads) {
             $query->select('upload_id')
                 ->fromSub($latestUploads, 'lu')
                 ->join('uploads', function ($join) {
                     $join->on('uploads.control_number', '=', 'lu.control_number')
                         ->on('uploads.created_at', '=', 'lu.latest_created_at');
                 });
         })
             ->where('is_archived', false)
             ->count();
         $controlledDocuments = Upload::where('status_upload', 4)
             ->where('is_archived', false)
             ->whereIn('upload_id', function ($query) use ($latestUploads) {
                 $query->select('upload_id')
                     ->fromSub($latestUploads, 'lu')
                     ->join('uploads', function ($join) {
                         $join->on('uploads.control_number', '=', 'lu.control_number')
                             ->on('uploads.created_at', '=', 'lu.latest_created_at');
                     });
             })
             ->count();
         $pendingApprovals = Requesting::where('request_status', 0)
             ->whereHas('upload', function ($query) use ($latestUploads) {
                 $query->where('is_archived', false)
                     ->whereIn('upload_id', function ($subQuery) use ($latestUploads) {
                         $subQuery->select('upload_id')
                             ->fromSub($latestUploads, 'lu')
                             ->join('uploads', function ($join) {
                                 $join->on('uploads.control_number', '=', 'lu.control_number')
                                     ->on('uploads.created_at', '=', 'lu.latest_created_at');
                             });
                     });
             })
             ->count();

         $revisions = Requesting::with(['upload.owner'])
             ->whereIn('request_id', function ($query) {
                 $query->selectRaw('MAX(request_id)')
                     ->from('requesting')
                     ->groupBy('upload_id');
             })
             ->whereHas('upload', function ($query) use ($latestUploads) {
                 $query->where('is_archived', false)
                     ->whereIn('upload_id', function ($subQuery) use ($latestUploads) {
                         $subQuery->select('upload_id')
                             ->fromSub($latestUploads, 'lu')
                             ->join('uploads', function ($join) {
                                 $join->on('uploads.control_number', '=', 'lu.control_number')
                                     ->on('uploads.created_at', '=', 'lu.latest_created_at');
                             });
                     });
             })
             ->when($user->role->id == 2, fn($q) => $q->where('request_status', 0))
             ->when($user->role->id == 3, fn($q) => $q->where('user_id', $user->id))
             ->get();
         Log::info('Revisions fetched in UploadController index: ' . $revisions->count() . ' records');

         return view('uploads.index', [
             'uploads' => $uploads,
             'revisions' => $revisions,
             'totalDownloads' => $totalDownloads,
             'totalDocuments' => $totalDocuments,
             'controlledDocuments' => $controlledDocuments,
             'pendingApprovals' => $pendingApprovals,
             // Admin specific counts
             'totalUploads' => $totalUploads ?? 0,
             'uploadsThisMonth' => $uploadsThisMonth ?? 0,
             'pendingReviewByCampusDCC' => $pendingReviewByCampusDCC ?? 0,
             // Campus DCC specific counts
             'pendingDistributions' => $pendingDistributions ?? 0,
             'totalDistributed' => $totalDistributed ?? 0,
             'distributedThisMonth' => $distributedThisMonth ?? 0,
             // Process Owner specific counts
             'documentsAssignedToMe' => $documentsAssignedToMe ?? 0
         ]);
     }
     
     public function create()
    {
        return view('uploads.create', ['upload' => null]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('uploads.index')->with('error', 'You do not have permission to upload documents.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'control_number' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'version' => 'nullable|string|max:255'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');

            $upload = Upload::create([
                'filename' => $file->getClientOriginalName(),
                'title' => $request->input('title'),
                'control_number' => $request->input('control_number'),
                'type' => $request->input('type'),
                'version' => $request->input('version') ?? '1.0', // Default to 1.0 if not provided
                'file_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
                'user_id' => Auth::id(),
                'status_upload' => (Auth::user() instanceof User && Auth::user()->hasRole('campus-dcc')) ? 4 : 0, // Campus DCC (role 2) uploads are controlled (4), others are pending (0)
                'is_archived' => false // New uploads are not archived
            ]);
            Log::info('Auth::user() type: ' . get_class(Auth::user()));
            $this->saveUserActivity("Uploaded Document: " . $file->getClientOriginalName());

            NotificationController::createNotification(
                Auth::id(),
                'document_uploaded',
                'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been uploaded.',
                route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
                ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
                Auth::user()->name,
                'Uploaded'
            );

            return redirect()->route('uploads.index')->with('success', 'Document uploaded successfully.');
        } else {
            // Handle updates for non-Process Owners or other fields
            $upload->update($request->validate([
                'title' => 'required|string|max:255',
                'control_number' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'version' => 'nullable|string|max:255'
            ]));

            $this->saveUserActivity("Updated Document: " . $upload->filename);

            NotificationController::createNotification(
                Auth::id(),
                'document_updated',
                'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been updated.',
                route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
                ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
                Auth::user()->name,
                'Updated'
            );

            return redirect()->route('uploads.index')->with('success', 'Document updated successfully.');
        }
    }









    public function view($upload_id, Request $request)
    {
        $upload = Upload::findOrFail($upload_id);
        $latestRequest = Requesting::where('upload_id', $upload_id)->latest()->first();

        $status = 'pending'; // Default status

        if ($latestRequest) {
            if ($latestRequest->request_status == 2) {
                $status = 'rejected';
            } elseif ($latestRequest->request_status == 1) {
                $status = 'approved';
            }
        } else {
            // If no request, use the status from the Upload model
            // Assuming 0 for pending, 1 for controlled, etc. based on your system's logic
            // You might need to adjust this mapping based on your 'status_upload' values
            switch ($upload->status_upload) {
                case 0:
                    $status = 'pending';
                    break;
                case 1:
                    $status = 'controlled'; // Example: if 1 means controlled
                    break;
                case 2:
                    $status = 'rejected';
                    break;
                case 4:
                    $status = 'controlled'; // As per your store method comment
                    break;
                default:
                    $status = 'unknown';
                    break;
            }
        }

        $revisions = $this->revisions($upload_id)->getData()['revisions'] ?? collect();

        return view('uploads.view', [
            'upload' => $upload,
            'status' => $status, // Pass the determined status to the view
            'revisions' => $revisions,
            'previousUrl' => $request->input('previous_url', route('documents.total'))
        ]);
    }

    public function edit($upload_id)
    {
        $upload = Upload::findOrFail($upload_id);
        return view('uploads.create', ['upload' => $upload]);
    }

    public function downloadDocument($id)
    {
        $upload = Upload::findOrFail($id);

        if (Storage::disk('public')->exists($upload->path)) {
            $upload->increment('numdl');
            $this->saveUserActivity("Downloaded Document: " . $upload->filename, 'download');
            return response()->download(storage_path('app/public/' . $upload->path), $upload->filename);
        } else {
            abort(404, 'File not found.');
        }
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('uploads.index')->with('error', 'You do not have permission to update documents.');
        }
        $upload = Upload::findOrFail($id);
        $user = Auth::user();

        $validatedData = $request->validate([
            'control_number' => 'required|string|max:255',
            'version' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            // Store the new file
            $file = $request->file('file');
            $path = $file->store('uploads', 'public');

            // Archive the old version
            $upload->update(['is_archived' => true]);

            // Create a new Upload record for the revision
            $newUpload = Upload::create([
                'filename' => $file->getClientOriginalName(),
                'title' => $upload->title, // Keep the original title
                'control_number' => $validatedData['control_number'],
                'type' => $upload->type, // Keep the original type
                'version' => $validatedData['version'] ?? $this->incrementVersion($upload->version), // Use new version or increment old
                'file_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
                'user_id' => $user->id,
                'status_upload' => ($user instanceof User && $user->hasRole('campus-dcc')) ? 4 : 0, // Campus DCC uploads are controlled (4), others are pending (0)
                'is_archived' => false // New uploads are not archived
            ]);

            $this->saveUserActivity("Revised Document: " . $newUpload->filename . " (Control No: " . $newUpload->control_number . ")");

            NotificationController::createNotification(
                Auth::id(),
                'document_revised',
                'Document "' . $newUpload->title . '" (Control No: ' . $newUpload->control_number . ') has been revised to version ' . $newUpload->version . '.',
                route('uploads.view', ['upload_id' => $newUpload->upload_id, 'previous_url' => url()->previous()]),
                ['upload_id' => $newUpload->upload_id, 'upload_filename' => $newUpload->filename, 'control_number' => $newUpload->control_number, 'version' => $newUpload->version],
                Auth::user()->name,
                'Revised'
            );

            return redirect()->route('uploads.index')->with('success', 'Document revised successfully.');
        } else {
            // Handle updates for non-Process Owners or other fields
            $upload->update($request->validate([
                'title' => 'required|string|max:255',
                'control_number' => 'required|string|max:255',
                'type' => 'required|string|max:255',
                'version' => 'nullable|string|max:255'
            ]));

            $this->saveUserActivity("Updated Document Metadata: " . $upload->filename);

            NotificationController::createNotification(
                Auth::id(),
                'document_metadata_updated',
                'Metadata for document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been updated.',
                route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
                ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
                Auth::user()->name,
                'Metadata Updated'
            );

            return redirect()->route('uploads.index')->with('success', 'Document updated successfully.');
        }
    }

    protected function incrementVersion($currentVersion)
    {
        // Assuming version is in format X.Y or X
        $parts = explode('.', $currentVersion);
        if (count($parts) == 2) {
            $parts[1]++;
        } else {
            $parts[0]++;
            $parts[1] = 0;
        }
        return implode('.', $parts);
    }

    public function revisions($upload_id)
    {
        $mainUpload = Upload::findOrFail($upload_id);

        // Find the original document in the revision chain
        $originalUpload = $mainUpload;
        while ($originalUpload->previous_version_id) {
            $prev = Upload::find($originalUpload->previous_version_id);
            if ($prev) {
                $originalUpload = $prev;
            } else {
                break;
            }
        }

        // Get all revisions linked to the original document, including itself
        $allRevisions = collect();
        $current = $originalUpload;
        while ($current) {
            $allRevisions->push($current);
            $next = Upload::where('previous_version_id', $current->upload_id)->first();
            $current = $next;
        }

        // Sort revisions by version number or creation date to ensure correct order
        $allRevisions = $allRevisions->sortByDesc('created_at');

        return view('uploads.revisions', ['upload' => $mainUpload, 'revisions' => $allRevisions]);
    }

    public function destroy($upload_id)
    {
        Log::info('Destroy method called for upload ID: ' . $upload_id);
        Log::info('Request method: ' . request()->method());
        try {
            $upload = Upload::findOrFail($upload_id);

            if ($upload->path && Storage::disk('public')->exists($upload->path)) {
                Storage::disk('public')->delete($upload->path);
            }

            Requesting::where('upload_id', $upload_id)->delete();
            $upload->delete();

            $this->saveUserActivity("Deleted Document: " . $upload->filename . " (Control No: " . $upload->control_number . ")");

            NotificationController::createNotification(
                Auth::id(),
                'document_deleted',
                'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been deleted.',
                '#',
                ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
                Auth::user()->name,
                'Deleted'
            );

            Log::info('Document ' . $upload_id . ' deleted successfully.');
            return redirect()->route('uploads.index')->with('success', 'Document deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting document ' . $upload_id . ': ' . $e->getMessage(), ['upload_id' => $upload_id, 'exception' => $e]);
            return redirect()->route('uploads.index')->with('error', 'Failed to delete document. Please try again.');
        }
    }

    public function approve(Upload $upload)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to approve documents.');
        }
        // If this is a revision, ensure the previous version is marked as archived
        if ($upload->previous_version_id) {
            $previousUpload = Upload::find($upload->previous_version_id);
            if ($previousUpload) {
                $previousUpload->update(['is_archived' => true]);
            }
        }

        $upload->status_upload = 4; // Controlled
        $upload->save();

        $this->saveUserActivity("Approved Document: " . $upload->filename . " (Control No: " . $upload->control_number . ")");

        NotificationController::createNotification(
            Auth::id(),
            'document_approved',
            'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been approved.',
            route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
            ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
            Auth::user()->name,
            'Approved'
        );

        return redirect()->back()->with('success', 'Document approved successfully.');
    }

    public function reject(Request $request, $upload_id)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to reject documents.');
        }
        $upload->status_upload = 2; // Rejected
        $upload->save();

        $this->saveUserActivity("Rejected Document: " . $upload->filename . " (Control No: " . $upload->control_number . ")");

        NotificationController::createNotification(
            Auth::id(),
            'document_rejected',
            'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been rejected.',
            route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
            ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
            Auth::user()->name,
            'Rejected'
        );

        return redirect()->back()->with('success', 'Document rejected successfully.');
    }

    public function archive($upload_id)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to archive documents.');
        }
        $upload = Upload::findOrFail($upload_id);
        $upload->update(['status_upload' => 1]);

        $this->saveUserActivity("Archived Document: " . $upload->filename . " (Control No: " . $upload->control_number . ")");

        NotificationController::createNotification(
            Auth::id(),
            'document_archived',
            'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been archived.',
            route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
            ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
            Auth::user()->name,
            'Archived'
        );

        return redirect()->back()->with('success', 'Document archived successfully.');
    }

    public function unarchive($upload_id)
    {
        $upload = Upload::findOrFail($upload_id);
        $upload->update(['status_upload' => 0]); // Set status to pending (0) or appropriate status after unarchiving

        $this->saveUserActivity("Unarchived Document: " . $upload->filename . " (Control No: " . $upload->control_number . ")");

        NotificationController::createNotification(
            Auth::id(),
            'document_unarchived',
            'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been unarchived.',
            route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
            ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
            Auth::user()->name,
            'Unarchived'
        );

        return redirect()->back()->with('success', 'Document unarchived successfully.');
    }

    public function delete($upload_id)
    {
        $upload = Upload::findOrFail($upload_id);
        $upload->delete();

        $this->saveUserActivity("Deleted Document: " . $upload->filename . " (Control No: " . $upload->control_number . ")");

        NotificationController::createNotification(
            Auth::id(),
            'document_deleted',
            'Document "' . $upload->title . '" (Control No: ' . $upload->control_number . ') has been deleted.',
            '#',
            ['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename, 'control_number' => $upload->control_number],
            Auth::user()->name,
            'Deleted'
        );

        Log::info('Document ' . $upload_id . ' deleted successfully.');
        return redirect()->route('uploads.index')->with('success', 'Document deleted successfully.');
    }

    public function manageAccess($id)
    {
        $upload = Upload::findOrFail($id);
        $this->saveUserActivity("Accessed Manage Access for Document ID: " . $upload->upload_id);
        return redirect()->back()->with('info', 'Manage Access functionality is not yet implemented.');
    }

    public function moveToControlled($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to move documents to controlled.');
        }
        $upload = Upload::findOrFail($id);
        $upload->update(['status_upload' => 4]);

        $this->saveUserActivity("Moved Document ID: " . $upload->upload_id . " to Controlled");

        NotificationController::createNotification(
            Auth::id(),
            'document_controlled',
            'document_controlled',
            route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
            json_encode(['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename]),
            Auth::user()->name,
            'Moved to Controlled'
        );

        return redirect()->back()->with('success', 'Document moved to controlled successfully.');
    }

    public function moveToUncontrolled($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'You do not have permission to move documents to uncontrolled.');
        }
        $upload = Upload::findOrFail($id);
        $upload->update(['status_upload' => 0]);

        $this->saveUserActivity("Moved Document ID: " . $upload->upload_id . " to Uncontrolled");

        NotificationController::createNotification(
            Auth::id(),
            'document_uncontrolled',
            'document_uncontrolled',
            route('uploads.view', ['upload_id' => $upload->upload_id, 'previous_url' => url()->previous()]),
            json_encode(['upload_id' => $upload->upload_id, 'upload_filename' => $upload->filename]),
            Auth::user()->name,
            'Moved to Uncontrolled'
        );

        return redirect()->back()->with('success', 'Document moved to uncontrolled successfully.');
    }

    public function saveUserActivity($activity, $type = null)
    {
        History::create([
            'user_activity' => $activity,
            'user_id' => Auth::id(),
            'type' => $type,
        ]);
    }









    public function request_stats(string $id_request, string $type, string $remarks)
    {
        if (!Auth::user()->hasRole('campus-dcc')) {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

        $request = Requesting::findOrFail($id_request);

        $request->update([
            'status_remarks' => $remarks,
            'request_status' => $type == 0 ? 1 : 2
        ]);

        $this->saveUserActivity("Request status updated");

        return redirect()->back()->with('success', 'Request status updated successfully.');
    }

    public function notif_data()
    {
        $userId = Auth::id();

        $uploadCount = Upload::where('user_id', $userId)->count();
        $historyCount = History::where('user_id', $userId)->count();

        return response()->json([
            'upload' => $uploadCount,
            'history' => $historyCount,
        ]);
    }
}

