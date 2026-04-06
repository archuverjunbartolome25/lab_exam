<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stud_id',
        'type',
        'severity',
        'description',
        'violation_date',
        'resolved_date',
        'status',
        'sanction',
    ];

    protected $casts = [
        'violation_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }
}
