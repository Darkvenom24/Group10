<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RailwayBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'pnr',
        'user_id',
        'railway_system_id',
        'passenger_name',
        'passenger_age',
        'passenger_gender',
        'passenger_phone',
        'train_number',
        'train_name',
        'source_station',
        'destination_station',
        'travel_class',
        'quota',
        'passengers_count',
        'journey_date',
        'total_fare',
        'status',
        'coach_seat',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'journey_date' => 'date',
            'total_fare' => 'decimal:2',
            'passengers_count' => 'integer',
            'passenger_age' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function railwaySystem()
    {
        return $this->belongsTo(RailwaySystem::class, 'railway_system_id');
    }
}
