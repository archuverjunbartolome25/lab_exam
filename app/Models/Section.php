<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';
    protected $primaryKey = 'section_code';
    protected $keyType = 'string';
    protected $fillable = [
        'section_code',
        'course_code',
        'semester',
        'schedule_day',
        'start_time',
        'end_time',
        'capacity',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_code', 'course_code');
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }
}
