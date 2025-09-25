<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Program;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('program')->get();
        return view('qao.eoms.courses', compact('courses'));
    }

    public function create()
    {
        $programs = Program::all();
        return view('qao.eoms.courses-create', compact('programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'       => 'required',
            'title'      => 'required',
            'program_id' => 'required|exists:programs,id',
            'units'      => 'required|integer',
            'instructor' => 'required',
        ]);

        Course::create($request->all());

        return redirect()->route('qao.eoms.courses')->with('success', 'Course added successfully.');
    }

    public function show(Course $course)
    {
        return view('qao.eoms.courses-show', compact('course'));
    }

    public function edit(Course $course)
    {
        $programs = Program::all();
        return view('qao.eoms.courses-edit', compact('course', 'programs'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'code'       => 'required',
            'title'      => 'required',
            'program_id' => 'required|exists:programs,id',
            'units'      => 'required|integer',
            'instructor' => 'required',
        ]);

        $course->update($request->all());

        return redirect()->route('qao.eoms.courses')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('qao.eoms.courses')->with('success', 'Course deleted successfully.');
    }
}
