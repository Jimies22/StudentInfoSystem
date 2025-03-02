@extends('layouts.studentLayout')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Dashboard</h1>
    </div>

    <!-- Welcome Card -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Welcome</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ Auth::user()->name }} - {{ Auth::user()->student->student_id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">My Enrollments</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>School Year</th>
                                    <th>Semester</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Units</th>
                                    <th>Midterm</th>
                                    <th>Final</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->enrollments as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->school_year }}</td>
                                        <td>{{ $enrollment->semester }}</td>
                                        <td>{{ $enrollment->subject->subject_code }}</td>
                                        <td>{{ $enrollment->subject->subject_name }}</td>
                                        <td>{{ $enrollment->subject->units }}</td>
                                        <td>{{ $enrollment->grade->midterm ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->grade->final ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->grade->grade ?? 'N/A' }}</td>
                                        <td>
                                            @if($enrollment->grade)
                                                @if($enrollment->grade->status !== 'Regular')
                                                    {{ $enrollment->grade->status }}
                                                @else
                                                    {{ $enrollment->grade->remarks }}
                                                @endif
                                            @else
                                                Pending
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No enrollments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
