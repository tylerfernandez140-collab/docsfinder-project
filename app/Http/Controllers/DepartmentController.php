<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\College;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('college')->get();
        return view('qao.eoms.departments', compact('departments'));
    }

    public function create()
    {
        $colleges = College::all();
        return view('qao.eoms.departments-create', compact('colleges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'college_id'=> 'required|exists:colleges,id',
            'programs'  => 'required|integer',
            'head'      => 'required',
        ]);

        Department::create($request->all());

        return redirect()->route('qao.eoms.departments')->with('success', 'Department added successfully.');
    }

    public function edit(Department $department)
    {
        $colleges = College::all();
        return view('qao.eoms.departments-edit', compact('department', 'colleges'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'      => 'required',
            'college_id'=> 'required|exists:colleges,id',
            'programs'  => 'required|integer',
            'head'      => 'required',
        ]);

        $department->update($request->all());

        return redirect()->route('qao.eoms.departments')->with('success', 'Department updated successfully.');
    }

    public function show(Department $department)
    {
        return view('qao.eoms.departments-show', compact('department'));
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('qao.eoms.departments')->with('success', 'Department deleted successfully.');
    }
}
