<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'curriculum_id',
        'schedule_id',
        'academic_year',
        'semester',
        'enrollment_date',
        'status',
        'grade',
        'remarks',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'grade' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Enrolled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function hasPassed(): bool
    {
        return $this->status === 'Completed' && $this->grade && $this->grade >= 75;
    }
}
