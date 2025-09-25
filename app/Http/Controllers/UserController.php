<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('qao.users', compact('users'));
    }



    public function create()
    {
        $roles = Role::where('name', '!=', 'super-admin')->get();
        return view('qao.users_create', compact('roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'super-admin')->get();
        return view('qao.users_edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'employee_id' => 'required|unique:users,employee_id,' . $user->id,
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'administrative_position' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
        ]);

        $user->update([
            'employee_id' => $request->employee_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'dob' => $request->dob,
            'address' => $request->address,
            'role_id' => $request->role_id,
            'administrative_position' => $request->administrative_position,
            'designation' => $request->designation,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:users',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'administrative_position' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'employee_id' => $request->employee_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dob' => $request->dob,
            'address' => $request->address,
            'role_id' => $request->role_id,
            'administrative_position' => $request->administrative_position,
            'designation' => $request->designation,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


}
