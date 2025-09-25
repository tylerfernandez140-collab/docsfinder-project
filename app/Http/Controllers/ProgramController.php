<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Department;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::with('department')->get();
        return view('qao.eoms.programs', compact('programs'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('qao.eoms.programs-create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'department_id' => 'required|exists:departments,id',
            'level'         => 'required',
            'accreditation' => 'required',
            'coordinator'   => 'required',
        ]);

        Program::create($request->all());

        return redirect()->route('qao.eoms.programs')->with('success', 'Program added successfully.');
    }

    public function show(Program $program)
    {
        return view('qao.eoms.programs-show', compact('program'));
    }

    public function edit(Program $program)
    {
        $departments = Department::all();
        return view('qao.eoms.programs-edit', compact('program', 'departments'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'name'          => 'required',
            'department_id' => 'required|exists:departments,id',
            'level'         => 'required',
            'accreditation' => 'required',
            'coordinator'   => 'required',
        ]);

        $program->update($request->all());

        return redirect()->route('qao.eoms.programs')->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('qao.eoms.programs')->with('success', 'Program deleted successfully.');
    }
}
