@extends('layouts.adminLayout')

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

    <h1 class="h3 mb-2 text-gray-800">Enrollments</h1>
    <p class="mb-4">Manage and view all enrollment records in the system.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Enrollments List</h6>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEnrollmentModal">
                <i class="fas fa-plus"></i> Add Enrollment
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>School Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->student->user->name }}</td>
                            <td>{{ $enrollment->student->student_id }}</td>
                            <td>{{ $enrollment->subject->subject_code }}</td>
                            <td>{{ $enrollment->subject->subject_name }}</td>
                            <td>{{ $enrollment->school_year }}</td>
                            <td>{{ $enrollment->semester }}</td>
                            <td>{{ $enrollment->status }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{ $enrollment->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Enrollment Modal -->
<div class="modal fade" id="addEnrollmentModal" tabindex="-1" role="dialog" aria-labelledby="addEnrollmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEnrollmentModalLabel">Add New Enrollment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.enrollments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id">Student</label>
                        <select class="form-control" id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->student_id }} - {{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject_id">Subject</label>
                        <select class="form-control" id="subject_id" name="subject_id" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_code }} - {{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school_year">School Year</label>
                        <input type="text" class="form-control" id="school_year" name="school_year" placeholder="2023-2024" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Enrolled">Enrolled</option>
                            <option value="Dropped">Dropped</option>
                            <option value="Incomplete">Incomplete</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Enrollment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Enrollment Modal -->
@foreach($enrollments as $enrollment)
<div class="modal fade" id="editModal{{ $enrollment->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $enrollment->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $enrollment->id }}">Edit Enrollment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.enrollments.update', $enrollment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_id{{ $enrollment->id }}">Student</label>
                        <select class="form-control" id="student_id{{ $enrollment->id }}" name="student_id" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $enrollment->student_id == $student->id ? 'selected' : '' }}>
                                    {{ $student->student_id }} - {{ $student->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subject_id{{ $enrollment->id }}">Subject</label>
                        <select class="form-control" id="subject_id{{ $enrollment->id }}" name="subject_id" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $enrollment->subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->subject_code }} - {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school_year{{ $enrollment->id }}">School Year</label>
                        <input type="text" class="form-control" id="school_year{{ $enrollment->id }}" name="school_year" value="{{ $enrollment->school_year }}" required>
                    </div>
                    <div class="form-group">
                        <label for="semester{{ $enrollment->id }}">Semester</label>
                        <select class="form-control" id="semester{{ $enrollment->id }}" name="semester" required>
                            @foreach(['1st', '2nd', 'Summer'] as $sem)
                                <option value="{{ $sem }}" {{ $enrollment->semester == $sem ? 'selected' : '' }}>
                                    {{ $sem }} Semester
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status{{ $enrollment->id }}">Status</label>
                        <select class="form-control" id="status{{ $enrollment->id }}" name="status" required>
                            @foreach(['Enrolled', 'Dropped', 'Incomplete'] as $status)
                                <option value="{{ $status }}" {{ $enrollment->status == $status ? 'selected' : '' }}>
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
        "dom": '<"d-flex justify-content-between align-items-center mb-4"<"d-flex align-items-center"l><"d-flex align-items-center"f>>rtip',
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries"
        }
    });
});
</script>
@endpush