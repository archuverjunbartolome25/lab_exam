<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicHistory extends Model
{
    use HasFactory;

    protected $table = 'academic_history';

    protected $fillable = [
        'stud_id',
        'course_id',
        'course_code',
        'course_name',
        'semester',
        'year',
        'grade',
        'credits',
        'grade_letter',
        'status',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'credits' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }
}
