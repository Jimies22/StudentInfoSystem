<?php

namespace App\Exports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GradesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Grade::with(['enrollment.student.user', 'enrollment.subject'])->get();
    }

    public function map($grade): array
    {
        return [
            $grade->enrollment->student->student_id,
            $grade->enrollment->student->user->name,
            $grade->enrollment->subject->subject_code,
            $grade->enrollment->subject->subject_name,
            $grade->midterm,
            $grade->final,
            $grade->grade,
            $grade->remarks,
            $grade->status,
        ];
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Student Name',
            'Subject Code',
            'Subject Name',
            'Midterm',
            'Final',
            'Grade',
            'Remarks',
            'Status'
        ];
    }
}
