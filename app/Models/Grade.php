<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'midterm',
        'final',
        'grade',
        'remarks',
        'status'
    ];

    protected $casts = [
        'midterm' => 'decimal:2',
        'final' => 'decimal:2',
        'grade' => 'decimal:2'
    ];

    public static $gradeScale = [
        1.00, 1.25, 1.50, 1.75,
        2.00, 2.25, 2.50, 2.75,
        3.00, 5.00
    ];

    public static $statusOptions = [
        'Regular',
        'INC',
        'FDA'
    ];

    // Add this to ensure proper ID handling
    protected $primaryKey = 'id';
    public $incrementing = true;

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
