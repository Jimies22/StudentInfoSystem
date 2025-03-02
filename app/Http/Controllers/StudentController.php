<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user()->student()->with([
            'enrollments.subject',
            'enrollments.grade'
        ])->first();

        if (!$student) {
            abort(404, 'Student record not found');
        }

        // Sort the enrollments after loading
        $student->enrollments = $student->enrollments
            ->sortByDesc('school_year')
            ->sortByDesc('semester');

        return view('student.dashboard', compact('student'));
    }
}