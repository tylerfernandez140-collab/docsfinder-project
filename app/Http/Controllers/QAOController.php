<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eom;
use App\Models\ProcessManual;
use App\Models\ReferenceManual;
use App\Models\AuditLog;
use App\Models\User;

class QAOController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $totalEoms = Eom::count();
        $totalProcessManuals = ProcessManual::count();
        $totalReferenceManuals = ReferenceManual::count();
        $totalAuditLogs = AuditLog::count();

        return view('qao.dashboard', compact(
            'totalEoms',
            'totalProcessManuals',
            'totalReferenceManuals',
            'totalAuditLogs'
        ));
    }



    
    public function processManuals()
    {
        $manuals = ProcessManual::with('owner')->latest()->get();
        return view('qao.process_manuals', compact('manuals'));
    }

    
    public function referenceManuals()
    {
        $manuals = ReferenceManual::with('owner')->latest()->get();
        return view('qao.reference_manuals', compact('manuals'));
    }

    // Audit Logs list
    public function auditLogs()
    {
        $user = auth()->user();
        $logs = AuditLog::with('user')->latest();

        if ($user->role === 0 || $user->role === 1) { // Super Admin or Admin
            // Super Admin and Admin can see all logs
            $logs = $logs->get();
        } else {
            // For other roles, perhaps show only their own activities or nothing
            // For now, let's assume other roles don't see audit logs
            $logs = collect(); // Empty collection
        }
        
        return view('audit_logs.index', compact('logs'));
    }

    // System Settings
    public function settings()
    {
        return view('qao.settings');
    }

    // User Management
    public function users()
    {
        $users = User::all();
        return view('qao.users', compact('users'));
    }

    public function reports()
    {
        return view('qao.eoms.reports');
    }

    public function createUser()
    {
        return view('role.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'dob' => $request->dob,
            'address' => $request->address,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('qao.users')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('role.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|unique:users,employee_id,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'dob' => $request->dob,
            'address' => $request->address,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('qao.users')->with('success', 'User updated successfully.');
    }
}
