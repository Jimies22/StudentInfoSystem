<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.dashboard');
    }

    public function profile()
    {
        $admin = auth()->user()->admin;
        return view('admin.profile', compact('admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'student_id' => ['required', 'string', 'unique:students'],
            'course' => ['required', 'string'],
            'year' => ['required', 'in:1st Year,2nd Year,3rd Year,4th Year'],
            'phone' => ['required', 'string'],
        ]);

        // Create user first
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student'
        ]);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $request->student_id,
            'course' => $request->course,
            'year' => $request->year,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.students')->with('success', 'Student created successfully');
    }

    public function students()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $students = Student::with('user')->get();
        return view('admin.students', compact('students'));
    }

    public function editStudent(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $student->user->id],
            'student_id' => ['required', 'string', 'unique:students,student_id,' . $student->id],
            'phone' => ['required', 'string'],
            'course' => ['required', 'string'],
            'year' => ['required', 'string'],
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $student->update([
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'course' => $request->course,
            'year' => $request->year,
        ]);

        return redirect()->route('admin.students')
            ->with('success', 'Student updated successfully');
    }

    public function destroyStudent(Student $student)
    {
        $student->user->delete(); // This will cascade delete the student record
        return redirect()->route('admin.students')
            ->with('success', 'Student deleted successfully');
    }

    public function subjects()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $subjects = Subject::all();
        return view('admin.subjects', compact('subjects'));
    }
}