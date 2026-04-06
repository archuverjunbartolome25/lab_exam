<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardiansCS extends Model
{
    use HasFactory;

    protected $table = 'guardians_cs';
    protected $primaryKey = 'guardian_id';
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'relationship',
        'contact_num',
        'address',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'guardian', 'guardian_id');
    }
}
