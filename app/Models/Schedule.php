<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id',
        'faculty_id',
        'section',
        'room',
        'building',
        'day_of_week',
        'start_time',
        'end_time',
        'academic_year',
        'semester',
        'max_students',
        'current_students',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_students' => 'integer',
        'current_students' => 'integer',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function getDurationAttribute(): string
    {
        $start = $this->start_time->format('H:i');
        $end = $this->end_time->format('H:i');
        return "{$start} - {$end}";
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByRoom($query, $room)
    {
        return $query->where('room', $room);
    }

    public function scopeByFaculty($query, $facultyId)
    {
        return $query->where('faculty_id', $facultyId);
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function isAvailable(): bool
    {
        return $this->current_students < $this->max_students;
    }
}
