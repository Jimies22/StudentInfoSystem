@extends('layouts.adminLayout')
@section('title', 'Grades')
@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Grades Management</h6>
            <div>
                <a href="{{ route('admin.grades.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export Grades
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGradeModal">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Add Grade
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Subject</th>
                            <th>Midterm</th>
                            <th>Final</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->student->user->name }}</td>
                            <td>{{ $enrollment->student->student_id }}</td>
                            <td>{{ $enrollment->subject->subject_code }} - {{ $enrollment->subject->subject_name }}</td>
                            <td>{{ $enrollment->grade->midterm ?? 'N/A' }}</td>
                            <td>{{ $enrollment->grade->final ?? 'N/A' }}</td>
                            <td>{{ $enrollment->grade->grade ?? 'N/A' }}</td>
                            <td>{{ $enrollment->grade->status ?? 'Regular' }}</td>
                            <td>{{ $enrollment->grade->remarks ?? 'Pending' }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{ $enrollment->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Grade Modal -->
<div class="modal fade" id="addGradeModal" tabindex="-1" role="dialog" aria-labelledby="addGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGradeModalLabel">Add New Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.grades.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="enrollment_id">Student - Subject</label>
                        <select class="form-control" id="enrollment_id" name="enrollment_id" required>
                            <option value="">Select Enrollment</option>
                            @foreach($enrollments as $enrollment)
                                @if(!$enrollment->grade)
                                    <option value="{{ $enrollment->id }}">
                                        {{ $enrollment->student->student_id }} - 
                                        {{ $enrollment->student->user->name }} - 
                                        {{ $enrollment->subject->subject_code }}
                                        ({{ $enrollment->school_year }} - {{ $enrollment->semester }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="midterm">Midterm Grade</label>
                        <input type="number" class="form-control" id="midterm" name="midterm" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="final">Final Grade</label>
                        <input type="number" class="form-control" id="final" name="final" min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            @foreach(['Regular', 'INC', 'FDA'] as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Grade Modals -->
@foreach($enrollments as $enrollment)
<div class="modal fade" id="editModal{{ $enrollment->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $enrollment->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $enrollment->id }}">Edit Grade</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $enrollment->grade ? route('admin.grades.update', $enrollment->grade->id) : route('admin.grades.store') }}" method="POST">
                @csrf
                @if($enrollment->grade)
                    @method('PUT')
                @endif
                <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Student: {{ $enrollment->student->user->name }}</label><br>
                        <label>Subject: {{ $enrollment->subject->subject_code }} - {{ $enrollment->subject->subject_name }}</label>
                    </div>
                    <div class="form-group">
                        <label for="midterm{{ $enrollment->id }}">Midterm Grade</label>
                        <input type="number" class="form-control" id="midterm{{ $enrollment->id }}" 
                               name="midterm" value="{{ $enrollment->grade->midterm ?? '' }}" 
                               min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="final{{ $enrollment->id }}">Final Grade</label>
                        <input type="number" class="form-control" id="final{{ $enrollment->id }}" 
                               name="final" value="{{ $enrollment->grade->final ?? '' }}" 
                               min="0" max="100" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="status{{ $enrollment->id }}">Status</label>
                        <select class="form-control" id="status{{ $enrollment->id }}" name="status" required>
                            @foreach(['Regular', 'INC', 'FDA'] as $status)
                                <option value="{{ $status }}" {{ ($enrollment->grade->status ?? 'Regular') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('styles')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[0, "asc"]],
        "pageLength": 25
    });
});
</script>
@endpush
