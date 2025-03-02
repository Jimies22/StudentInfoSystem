<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Exports\GradesExport;
use Maatwebsite\Excel\Facades\Excel;

class GradeExportController extends Controller
{
    public function export()
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        return Excel::download(new GradesExport, 'grades.xlsx');
    }
}
