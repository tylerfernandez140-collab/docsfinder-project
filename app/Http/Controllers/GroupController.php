<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    // Show all groups
    public function index()
    {
        $groups = Group::all();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $roles = User::select('role')->distinct()->get()->pluck('role');
        return view('groups.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:groups',
            'type' => 'required|in:manual,role_based',
            'created_by_role' => 'nullable|string',
            'users' => 'array',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'type' => $request->type,
            'created_by_role' => $request->created_by_role,
        ]);

        if ($request->type === 'role_based' && $request->created_by_role) {
            $group->addUserByRole($request->created_by_role);
        } elseif ($request->type === 'manual' && $request->users) {
            $group->users()->sync($request->users);
        }

        return redirect()->route('groups.index')->with('success', 'Group created successfully.');
    }

    // Show the add member form
    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        $roles = User::select('role')->distinct()->get()->pluck('role');
        $users = User::all();
        return view('groups.edit', compact('group', 'roles', 'users'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|unique:groups,name,' . $group->id,
            'type' => 'required|in:manual,role_based',
            'created_by_role' => 'nullable|string',
            'users' => 'array',
        ]);

        $group->update([
            'name' => $request->name,
            'type' => $request->type,
            'created_by_role' => $request->created_by_role,
        ]);

        if ($request->type === 'role_based' && $request->created_by_role) {
            $group->users()->detach(); // Clear existing users
            $group->addUserByRole($request->created_by_role);
        } elseif ($request->type === 'manual' && $request->users) {
            $group->users()->sync($request->users);
        } else {
            $group->users()->detach(); // If type changes or no users selected
        }

        return redirect()->route('groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }

    public function addUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $group->users()->attach($request->user_id);

        return back()->with('success', 'User added to group successfully.');
    }

    public function removeUser(Request $request, Group $group, User $user)
    {
        $group->users()->detach($user->id);

        return back()->with('success', 'User removed from group successfully.');
    }
}
