<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id',
        'faculty_id',
        'title',
        'description',
        'content',
        'lesson_number',
        'week_number',
        'learning_objectives',
        'activities',
        'assessment',
        'resources',
        'status',
    ];

    protected $casts = [
        'lesson_number' => 'integer',
        'learning_objectives' => 'array',
        'activities' => 'array',
        'assessment' => 'array',
        'resources' => 'array',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    public function scopeByWeek($query, $week)
    {
        return $query->where('week_number', $week);
    }

    public function scopeByCurriculum($query, $curriculumId)
    {
        return $query->where('curriculum_id', $curriculumId);
    }
}
