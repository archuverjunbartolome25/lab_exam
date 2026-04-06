<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stud_id',
        'type',
        'title',
        'description',
        'achievement_date',
        'level',
        'awarder',
    ];

    protected $casts = [
        'achievement_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }
}
