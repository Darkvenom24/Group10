<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practical7Student extends Model
{
    use HasFactory;

    protected $table = 'practical7_students';

    protected $fillable = [
        'user_id',
        'enrollment',
        'full_name',
        'email',
        'phone',
        'department',
        'semester',
        'gender',
        'dob',
        'skills',
        'project_title',
        'project_desc',
        'address',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
