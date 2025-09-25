<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculty = Faculty::with('department')->get();
        return view('qao.eoms.faculty', compact('faculty'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('qao.eoms.faculty-create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'department_id' => 'required|exists:departments,id',
            'designation'   => 'required',
            'specialization'=> 'required',
        ]);

        Faculty::create($request->all());

        return redirect()->route('qao.eoms.faculty')->with('success', 'Faculty added successfully.');
    }

    public function show(Faculty $faculty)
    {
        return view('qao.eoms.faculty-show', compact('faculty'));
    }

    public function edit(Faculty $faculty)
    {
        $departments = Department::all();
        return view('qao.eoms.faculty-edit', compact('faculty', 'departments'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'name'          => 'required',
            'department_id' => 'required|exists:departments,id',
            'designation'   => 'required',
            'specialization'=> 'required',
        ]);

        $faculty->update($request->all());

        return redirect()->route('qao.eoms.faculty')->with('success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return redirect()->route('qao.eoms.faculty')->with('success', 'Faculty deleted successfully.');
    }
}
