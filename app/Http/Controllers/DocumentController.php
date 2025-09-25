<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function distribute($upload_id)
    {
        $upload = \App\Models\Upload::findOrFail($upload_id);
        $designations = \App\Models\Designation::all(); // Assuming a Designation model exists
        $processOwners = \App\Models\User::whereHas('role', function($query) {
            $query->where('name', 'process-owner');
        })->get(); // Assuming 'process-owner' is the role name for process owners

        return view('documents.distribute', compact('upload', 'designations', 'processOwners'));
    }

    public function performDistribution(Request $request, $upload_id)
    {
        $request->validate([
            'designation' => 'required|exists:designations,id',
            'process_owners' => 'required|array',
            'process_owners.*' => 'exists:users,id',
        ]);

        $upload = \App\Models\Upload::findOrFail($upload_id);
        $upload->status_distribution = 1; // Mark as distributed
        $upload->distributed_to_designation = $request->input('designation');
        $upload->distributed_to_process_owner = json_encode($request->input('process_owners')); // Store as JSON
        $upload->distributed_by_user_id = auth()->user()->id;
        $upload->distributed_at = now();
        $upload->save();

        return redirect()->route('home')->with('success', 'Document distributed successfully!');
    }

    public function distributed()
    {
        $distributedDocuments = \App\Models\Upload::where('status_distribution', '!=', null)->get();
        return view('documents.distributed', compact('distributedDocuments'));
    }

    public function myDocuments()
    {
        $myDocuments = \App\Models\Upload::where('user_id', auth()->user()->id)->get();
        return view('documents.my_documents', compact('myDocuments'));
    }

    public function feedback()
    {
        return view('documents.feedback');
    }
}
