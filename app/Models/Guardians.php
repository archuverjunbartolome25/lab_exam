<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guardians extends Model
{
    use HasFactory;

    protected $table = 'guardians';
    protected $primaryKey = 'guardian_id';
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'relationship',
        'contact_num',
        'address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'guardian', 'guardian_id');
    }

    public function getFullNameAttribute(): string
    {
        $fullName = $this->fname . ' ' . $this->lname;
        if ($this->mname) {
            $fullName = $this->fname . ' ' . $this->mname . ' ' . $this->lname;
        }
        return $fullName;
    }
}
