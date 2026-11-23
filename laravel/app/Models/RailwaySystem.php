<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RailwaySystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'zone',
        'division',
        'headquarters',
        'station_master_name',
        'station_master_username',
        'station_master_email',
        'station_master_phone',
        'total_trains',
        'daily_passengers',
        'description',
        'badge_color',
    ];

    public function bookings()
    {
        return $this->hasMany(RailwayBooking::class, 'railway_system_id');
    }

    public function stationMasterUser()
    {
        return $this->hasOne(User::class, 'assigned_system_code', 'code');
    }
}
