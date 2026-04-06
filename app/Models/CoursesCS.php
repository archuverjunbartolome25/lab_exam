<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesCS extends Model
{
    use HasFactory;

    protected $table = 'courses_cs';
    protected $primaryKey = 'course_id';
    protected $fillable = [
        'course',
        'course_code',
        'description',
        'credits',
    ];

    protected $casts = [
        'credits' => 'integer',
    ];

    public function sections()
    {
        return $this->hasMany(SectionsCS::class, 'course_id', 'course_id');
    }
}
