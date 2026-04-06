<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuidanceRecord extends Model
{
    use HasFactory;

    protected $table = 'guidance_records';
    protected $primaryKey = 'record_id';
    protected $keyType = 'string';
    protected $fillable = [
        'record_id',
        'student_number',
        'counselor',
        'session_date',
        'topics_discussed',
        'action_plan',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_number', 'stud_num');
    }
}
