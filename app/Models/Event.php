<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'category',
        'start_datetime',
        'end_datetime',
        'venue',
        'organizer',
        'target_audience',
        'max_participants',
        'current_participants',
        'registration_fee',
        'requirements',
        'contact_person',
        'contact_email',
        'contact_phone',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
        'registration_fee' => 'decimal:2',
    ];

    public function isUpcoming(): bool
    {
        return $this->start_datetime > now();
    }

    public function isOngoing(): bool
    {
        return $this->start_datetime <= now() && $this->end_datetime >= now();
    }

    public function isCompleted(): bool
    {
        return $this->end_datetime < now();
    }

    public function hasAvailableSlots(): bool
    {
        return !$this->max_participants || $this->current_participants < $this->max_participants;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_datetime', '<=', now())
                    ->where('end_datetime', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_datetime', '<', now());
    }

    public function scopeCurricular($query)
    {
        return $query->where('type', 'Curricular');
    }

    public function scopeExtracurricular($query)
    {
        return $query->where('type', 'Extra-curricular');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
