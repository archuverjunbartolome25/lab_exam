<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_num',
        'fname',
        'lname',
        'email',
        'password',
        'phone',
        'role',
        'status',
        'department',
        'permissions',
        'last_login',
    ];

    protected $casts = [
        'permissions' => 'array',
        'last_login' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Scope to only get admin users
    public function scopeAdminOnly($query)
    {
        return $query->where('role', 'admin');
    }

    public function account(): HasOne
    {
        return $this->hasOne(AdminAccount::class, 'admin_user_id', 'id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->fname} {$this->lname}";
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    // Get admin number (maps from user_num)
    public function getAdminNumAttribute(): string
    {
        return $this->user_num;
    }
}
