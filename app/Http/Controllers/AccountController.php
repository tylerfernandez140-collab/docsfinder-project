<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AccountController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function editPassword()
    {

        $user = User::where('id', Auth::id())
            ->get();

        return view('password', ['user' => $user[0]]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->name = $request->empid;
        $user->employee_id = $request->name;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('account.password.edit')->with('success', 'Password updated successfully!');
    }
}
