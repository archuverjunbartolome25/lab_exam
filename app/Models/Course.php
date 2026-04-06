<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'course_code';
    protected $keyType = 'string';
    protected $fillable = [
        'course_code',
        'course_title',
        'Units',
        'Department',
        'course_description',
    ];

    protected $casts = [
        'Units' => 'integer',
    ];

    public function syllabus(): HasMany
    {
        return $this->hasMany(Syllabus::class, 'course_code', 'course_code');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'course_code', 'course_code');
    }

    public function academicTrackers(): HasMany
    {
        return $this->hasMany(AcademicTracker::class, 'course_code', 'course_code');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('Department', $department);
    }
}
