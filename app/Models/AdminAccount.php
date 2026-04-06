<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAccount extends Model
{
    use HasFactory;

    protected $table = 'admin_accounts';
    protected $primaryKey = 'admin_acc_id';
    protected $fillable = [
        'admin_id',
        'email',
        'password',
        'is_active',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'password_changed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'two_factor_recovery_codes' => 'array',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_user_id', 'admin_user_id');
    }

    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret);
    }

    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }
}
