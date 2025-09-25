<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = College::all();
        return view('qao.eoms.colleges', compact('colleges'));
    }

    public function create()
    {
        return view('qao.eoms.colleges-create');
    }

    public function show(College $college)
    {
        return view('qao.eoms.colleges-show', compact('college'));
    }

    public function edit(College $college)
    {
        return view('qao.eoms.colleges-edit', compact('college'));
    }

    public function update(Request $request, College $college)
    {
        $request->validate([
            'name'          => 'required',
            'programs'      => 'required|integer',
            'accreditation' => 'required',
            'qa'            => 'required',
            'coordinator'   => 'required',
        ]);

        $college->update($request->all());

        return redirect()->route('qao.eoms.colleges')->with('success', 'College updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'programs'      => 'required|integer',
            'accreditation' => 'required',
            'qa'            => 'required',
            'coordinator'   => 'required',
        ]);

        College::create($request->all());

        return redirect()->route('qao.eoms.colleges')->with('success', 'College added successfully.');
    }

    public function destroy(College $college)
    {
        $college->delete();

        return redirect()->route('qao.eoms.colleges')->with('success', 'College deleted successfully.');
    }
}
