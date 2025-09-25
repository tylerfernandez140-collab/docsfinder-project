<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display the approval page.
     */
    public function approval()
    {
        $user = Auth::user();

        // Only Campus DCC (role 2) can see pending approvals
        if ($user->role == 2) {
            $pendingUploads = Upload::where('status_upload', 0)->get();
            return view('requests.approval', compact('pendingUploads'));
        } else {
            // Redirect or show an error if the user is not authorized
            return redirect('/home')->with('error', 'You are not authorized to view this page.');
        }
    }

    /**
     * Display the revision page.
     */
    public function revision()
    {
        return view('requests.revision');
    }

    /**
     * Display the permission revision page.
     */
    public function permissionRevision()
    {
        return view('permissions.revision');
    }
}
