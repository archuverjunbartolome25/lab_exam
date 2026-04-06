<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SectionsCS extends Model
{
    use HasFactory;

    protected $table = 'sections_cs';
    protected $primaryKey = 'section_id';
    protected $fillable = [
        'section',
        'courses',
        'num_of_stud',
        'stud_id',
    ];

    protected $casts = [
        'courses' => 'array',
        'max_students' => 'integer',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'faculty_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id', 'stud_id');
    }

    public function getCoursesInfoAttribute()
    {
        if (!$this->courses || !is_array($this->courses)) {
            return [];
        }
        
        $courses = [];
        foreach ($this->courses as $courseId) {
            $course = CoursesCS::find($courseId);
            if ($course) {
                $courses[] = [
                    'id' => $course->course_id,
                    'code' => $course->course_code,
                    'name' => $course->course,
                    'credits' => $course->credits
                ];
            }
        }
        return $courses;
    }
}
