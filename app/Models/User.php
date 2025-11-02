<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- PENTING: Import BelongsTo
use App\Notifications\ResetPasswordNotification; 

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    
    protected $fillable = [
        'username',
        'email', 
        'password',
        'nama_lengkap',
        'peran',
        'id_unit',
        'is_aktif',
        'nomor_telepon',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token, $this));
    }

    // --- Relasi ---
    public function unit(): BelongsTo
    {
        // id_unit di tabel users merujuk ke id_unit di tabel unit
        return $this->belongsTo(Unit::class, 'id_unit', 'id_unit');
    }

    // --- Peran Cek ---
    public function isAdmin(): bool
    {
        return $this->peran === 'Admin';
    }

    public function isApprover(): bool
    {
        return in_array($this->peran, ['Dekan', 'Kepala_Unit', 'WR_1', 'WR_2', 'WR_3', 'Rektor']);
    }

    public function isUnitHead(): bool
    {
        return in_array($this->peran, ['Dekan', 'Kepala_Unit']);
    }
}