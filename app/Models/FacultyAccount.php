<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FacultyAccount extends Model
{
    use HasFactory;

    protected $table = 'faculty_acc';
    protected $primaryKey = 'faculty_acc_id';
    protected $fillable = [
        'faculty_id',
        'email',
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function faculty(): HasOne
    {
        return $this->hasOne(Faculty::class, 'faculty_id', 'faculty_id');
    }
}
