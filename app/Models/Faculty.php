<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';
    protected $primaryKey = 'faculty_id';
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'ext',
        'position',
        'prog_dept_chair',
        'classes',
    ];

    protected $casts = [
        'classes' => 'array',
    ];

    
    public function account(): HasOne
    {
        return $this->hasOne(FacultyAccount::class, 'faculty_id', 'faculty_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer', 'email');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->fname} {$this->lname}";
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByEmploymentType($query, $type)
    {
        return $query->where('employment_type', $type);
    }
}
