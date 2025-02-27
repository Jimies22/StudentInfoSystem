<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $subjects = Subject::all();
        return view('admin.subjects', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_code' => ['required', 'string', 'unique:subjects,subject_code'],
            'subject_name' => ['required', 'string'],
            'units' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'semester' => ['required', 'in:1st,2nd,Summer'],
        ]);

        Subject::create($request->all());
        return redirect()->route('admin.subjects')->with('success', 'Subject created successfully');
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_code' => ['required', 'string', 'unique:subjects,subject_code,' . $subject->id],
            'subject_name' => ['required', 'string'],
            'units' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'semester' => ['required', 'in:1st,2nd,Summer'],
        ]);

        $subject->update($request->all());
        return redirect()->route('admin.subjects')->with('success', 'Subject updated successfully');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects')->with('success', 'Subject deleted successfully');
    }
}
