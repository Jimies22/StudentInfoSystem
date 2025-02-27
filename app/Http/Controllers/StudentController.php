<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->role !== 'student') {
            abort(403, 'Unauthorized action.');
        }
        return view('student.dashboard');
    }
}