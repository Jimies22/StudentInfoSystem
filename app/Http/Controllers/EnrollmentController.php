<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $enrollments = Enrollment::with(['student.user', 'subject'])->get();
        $students = Student::with('user')->get();
        $subjects = Subject::all();
        
        return view('admin.enrollments', compact('enrollments', 'students', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'school_year' => ['required', 'string'],
            'semester' => ['required', 'in:1st,2nd,Summer'],
            'status' => ['required', 'in:Enrolled,Dropped,Incomplete'],
        ]);

        Enrollment::create($request->all());
        return redirect()->route('admin.enrollments')->with('success', 'Enrollment created successfully');
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'school_year' => ['required', 'string'],
            'semester' => ['required', 'in:1st,2nd,Summer'],
            'status' => ['required', 'in:Enrolled,Dropped,Incomplete'],
        ]);

        $enrollment->update($request->all());
        return redirect()->route('admin.enrollments')->with('success', 'Enrollment updated successfully');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('admin.enrollments')->with('success', 'Enrollment deleted successfully');
    }
}