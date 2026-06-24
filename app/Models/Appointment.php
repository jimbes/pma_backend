<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'created_by',
        'title',
        'description',
        'appointment_date',
        'appointment_time',
        'location',
        'doctor_name',
        'notify_user_1',
        'notify_user_2',
        'completed',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
            'completed' => 'boolean',
        ];
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
