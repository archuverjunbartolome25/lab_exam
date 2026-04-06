<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'stud_id',
        'category',
        'name',
        'level',
        'description',
        'acquired_date',
        'certificate',
    ];

    protected $casts = [
        'acquired_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id');
    }
}
