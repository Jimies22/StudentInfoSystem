<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Cache;

class GradeController extends Controller
{
    protected $customMessages = [
        'midterm.required' => 'Midterm grade is required.',
        'midterm.numeric' => 'Midterm grade must be a number.',
        'midterm.min' => 'Midterm grade cannot be less than :min.',
        'midterm.max' => 'Midterm grade cannot be greater than :max.',
        'final.required' => 'Final grade is required.',
        'final.numeric' => 'Final grade must be a number.',
        'final.min' => 'Final grade cannot be less than :min.',
        'final.max' => 'Final grade cannot be greater than :max.',
        'status.required' => 'Status is required.',
        'status.in' => 'Selected status is invalid.',
    ];

    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        $enrollments = Cache::remember('enrollments.all', 3600, function () {
            return Enrollment::with(['student.user', 'subject', 'grade'])->get();
        });
        
        return view('admin.grades', compact('enrollments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'midterm' => ['required', 'numeric', 'min:0', 'max:100'],
            'final' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:Regular,INC,FDA'],
        ], $this->customMessages);

        // Check if grade already exists
        if (Grade::where('enrollment_id', $request->enrollment_id)->exists()) {
            return redirect()->route('admin.grades')
                ->with('error', 'Grade already exists for this enrollment');
        }

        $grade = $this->calculateGrade($request->midterm, $request->final);
        $remarks = $this->calculateRemarks($grade, $request->status);

        $grade = Grade::create([
            'enrollment_id' => $request->enrollment_id,
            'midterm' => $request->midterm,
            'final' => $request->final,
            'grade' => $grade,
            'remarks' => $remarks,
            'status' => $request->status
        ]);

        LoggingService::logGradeAction(
            'grade_created',
            $grade->toArray(),
            auth()->user()
        );

        return redirect()->route('admin.grades')
            ->with('success', 'Grade added successfully');
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'midterm' => ['required', 'numeric', 'min:0', 'max:100'],
            'final' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:Regular,INC,FDA'],
        ]);

        $finalGrade = $this->calculateGrade($request->midterm, $request->final);
        $remarks = $this->calculateRemarks($finalGrade, $request->status);

        $grade->update([
            'midterm' => $request->midterm,
            'final' => $request->final,
            'grade' => $finalGrade,
            'remarks' => $remarks,
            'status' => $request->status
        ]);

        return redirect()->route('admin.grades')->with('success', 'Grade updated successfully');
    }

    private function calculateGrade($midterm, $final)
    {
        if (is_null($midterm) || is_null($final)) {
            return null;
        }
        
        $average = ($midterm + $final) / 2;
        
        // If the input grades are already in the 1.00-5.00 scale
        if ($midterm <= 5.00 && $final <= 5.00) {
            // Check if the average is within valid range
            if ($average >= 1.00 && $average <= 3.00) {
                // Round to nearest valid grade point
                foreach (Grade::$gradeScale as $validGrade) {
                    if ($average <= $validGrade) {
                        return $validGrade;
                    }
                }
            }
            return 5.00;
        }
        
        // For percentage grades (0-100)
        if ($average >= 97) return 1.00;
        if ($average >= 94) return 1.25;
        if ($average >= 91) return 1.50;
        if ($average >= 88) return 1.75;
        if ($average >= 85) return 2.00;
        if ($average >= 82) return 2.25;
        if ($average >= 79) return 2.50;
        if ($average >= 76) return 2.75;
        if ($average >= 75) return 3.00;
        
        return 5.00;
    }

    private function calculateRemarks($grade, $status)
    {
        if ($status === 'INC') {
            return 'Incomplete';
        }
        if ($status === 'FDA') {
            return 'Failure Due to Absences';
        }
        if (is_null($grade)) {
            return 'Pending';
        }
        return $grade < 5.00 ? 'Passed' : 'Failed';
    }
}