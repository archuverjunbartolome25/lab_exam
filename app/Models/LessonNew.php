<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonNew extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    protected $fillable = [
        'syllabus_id',
        'topic_title',
        'week_number',
        'lesson_content',
    ];

    protected $casts = [
        'week_number' => 'integer',
    ];

    public function syllabus(): BelongsTo
    {
        return $this->belongsTo(SyllabusNew::class, 'syllabus_id');
    }
}
