<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentAccount extends Model
{
    use HasFactory;

    protected $table = 'students_acc';
    protected $primaryKey = 'stud_acc_id';
    protected $fillable = [
        'stud_id',
        'is_active',
        'email',
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'stud_id', 'stud_id');
    }
}
