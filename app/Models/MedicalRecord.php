<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';
    protected $primaryKey = 'id';
    protected $fillable = [
        'stud_id',
        'blood_type',
        'allergies',
        'medications',
        'medical_conditions',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'medical_history',
        'physician_name',
        'physician_phone',
        'last_checkup',
        'insurance_provider',
        'insurance_policy_number',
    ];

    protected $casts = [
        'last_checkup' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id', 'stud_id');
    }
}
