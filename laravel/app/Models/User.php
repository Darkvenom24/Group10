<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'enrollment',
        'email',
        'password',
        'role',
        'is_admin',
        'assigned_system_code',
        'avatar',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function railwayBookings()
    {
        return $this->hasMany(RailwayBooking::class, 'user_id');
    }

    public function practical7Records()
    {
        return $this->hasMany(Practical7Student::class, 'user_id');
    }

    public function assignedRailwaySystem()
    {
        return $this->belongsTo(RailwaySystem::class, 'assigned_system_code', 'code');
    }

    public function isStationMaster(): bool
    {
        return $this->is_admin || str_contains(strtolower($this->role), 'station master');
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
}
