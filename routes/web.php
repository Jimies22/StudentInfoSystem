<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/students', [AdminController::class, 'students'])->name('admin.students');
    Route::post('/admin/students', [AdminController::class, 'store'])->name('admin.students.store');
    Route::get('/admin/students/{student}/edit', [AdminController::class, 'editStudent'])->name('admin.students.edit');
    Route::put('/admin/students/{student}', [AdminController::class, 'updateStudent'])->name('admin.students.update');
    Route::delete('/admin/students/{student}', [AdminController::class, 'destroyStudent'])->name('admin.students.destroy');
    Route::get('/admin/subjects', [SubjectController::class, 'index'])->name('admin.subjects');
    Route::post('/admin/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
    Route::put('/admin/subjects/{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/admin/subjects/{subject}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');
    Route::get('/admin/enrollments', [EnrollmentController::class, 'index'])->name('admin.enrollments');
    Route::post('/admin/enrollments', [EnrollmentController::class, 'store'])->name('admin.enrollments.store');
    Route::put('/admin/enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('admin.enrollments.update');
    Route::delete('/admin/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('admin.enrollments.destroy');
    Route::get('/admin/grades', [GradeController::class, 'index'])->name('admin.grades');
    Route::post('/admin/grades', [GradeController::class, 'store'])->name('admin.grades.store');
    Route::put('/admin/grades/{grade}', [GradeController::class, 'update'])->name('admin.grades.update');
});

require __DIR__.'/auth.php';
