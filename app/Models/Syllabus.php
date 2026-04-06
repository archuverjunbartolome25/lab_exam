<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Syllabus extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id',
        'faculty_id',
        'academic_year',
        'semester',
        'course_description',
        'learning_objectives',
        'topics_covered',
        'grading_system',
        'class_policies',
        'required_materials',
        'schedule_details',
        'status',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}
