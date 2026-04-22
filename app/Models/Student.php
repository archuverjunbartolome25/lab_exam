<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'stud_id';

    public function getRouteKeyName(): string
    {
        return 'stud_id';
    }

    protected $fillable = [
        'stud_num',
        'fname',
        'mname',
        'lname',
        'ext',
        'gender',
        'bday',
        'email',
        'contact_num',
        'address',
        'guardian',
        'program',
        'program_code',
        'section_id',
        'course',
        'courses',
        'standing',
        'academic_status',
        'achievements',
        'skills',
        'affiliations',
        'violations',
        'medicalRecord',
    ];

    protected $casts = [
        'courses' => 'array',
        'achievements' => 'array',
        'skills' => 'array',
        'affiliations' => 'array',
        'violations' => 'array',
        'medicalRecord' => 'array',
        'bday' => 'date',
    ];

    
    public function guardian()
    {
        if ($this->program === 'BSIT') {
            return $this->belongsTo(GuardiansIT::class, 'guardian', 'guardian_id');
        } elseif ($this->program === 'BSCS') {
            return $this->belongsTo(GuardiansCS::class, 'guardian', 'guardian_id');
        }
        return null;
    }

    public function sectionIT()
    {
        return $this->belongsTo(SectionsIT::class, 'section_id', 'section_id');
    }

    public function sectionCS()
    {
        return $this->belongsTo(SectionsCS::class, 'section_id', 'section_id');
    }

    public function section()
    {
        if ($this->program === 'BSIT') {
            return $this->sectionIT();
        } elseif ($this->program === 'BSCS') {
            return $this->sectionCS();
        }
        return null;
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'stud_id', 'stud_id');
    }

    public function getFullNameAttribute(): string
    {
        $fullName = $this->fname . ' ' . $this->lname;
        if ($this->mname) {
            $fullName = $this->fname . ' ' . $this->mname . ' ' . $this->lname;
        }
        if ($this->ext) {
            $fullName .= ' ' . $this->ext;
        }
        return $fullName;
    }

    
    public function scopeByProgram($query, $program)
    {
        return $query->where('program', $program);
    }

    public function scopeByYearLevel($query, $yearLevel)
    {
        return $query->where('year_level', $yearLevel);
    }

    public function scopeByEnrollmentStatus($query, $status)
    {
        return $query->where('enrollment_status', $status);
    }

    // New relationships for comprehensive profiling
    public function affiliations(): HasMany
    {
        return $this->hasMany(StudentAffiliation::class, 'stud_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(StudentSkill::class, 'stud_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(StudentViolation::class, 'stud_id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class, 'stud_id');
    }

    public function academicHistory(): HasMany
    {
        return $this->hasMany(AcademicHistory::class, 'stud_id');
    }

    // Computed properties for quick access
    public function getCurrentGpaAttribute(): float
    {
        return $this->academicHistory()
            ->where('status', 'completed')
            ->avg('grade') ?? 0.0;
    }

    public function getTotalCreditsAttribute(): float
    {
        return $this->academicHistory()
            ->where('status', 'completed')
            ->sum('credits') ?? 0.0;
    }

    public function getActiveViolationsAttribute(): int
    {
        return $this->violations()
            ->where('status', 'pending')
            ->count();
    }

    public function getRecentAchievementsAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->achievements()
            ->orderBy('achievement_date', 'desc')
            ->limit(5)
            ->get();
    }
}
