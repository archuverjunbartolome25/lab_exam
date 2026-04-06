<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicTracker extends Model
{
    use HasFactory;

    protected $table = 'academic_trackers';
    protected $primaryKey = 'tracker_id';
    protected $keyType = 'string';
    protected $fillable = [
        'tracker_id',
        'student_number',
        'course_code',
        'semester',
        'grade',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_number', 'stud_num');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_code', 'course_code');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}
