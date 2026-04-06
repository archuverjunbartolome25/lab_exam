<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyllabusNew extends Model
{
    use HasFactory;

    protected $table = 'syllabus';
    protected $fillable = [
        'course_code',
        'description',
        'learning_outcomes',
        'grading_policy',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_code', 'course_code');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'syllabus_id');
    }
}
