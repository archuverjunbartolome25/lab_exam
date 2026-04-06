<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curriculum extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'department',
        'credits',
        'hours_per_week',
        'course_type',
        'prerequisites',
        'year_level',
        'semester',
        'status',
        'learning_outcomes',
        'assessment_methods',
    ];

    protected $casts = [
        'credits' => 'integer',
        'hours_per_week' => 'integer',
        'year_level' => 'integer',
        'learning_outcomes' => 'array',
        'assessment_methods' => 'array',
    ];

    public function syllabus(): HasMany
    {
        return $this->hasMany(Syllabus::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot(['academic_year', 'semester', 'grade', 'status'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByCourseType($query, $type)
    {
        return $query->where('course_type', $type);
    }

    public function scopeByYearLevel($query, $yearLevel)
    {
        return $query->where('year_level', $yearLevel);
    }
}
