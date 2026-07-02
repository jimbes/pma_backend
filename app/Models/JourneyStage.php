<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneyStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'treatment_cycle_id',
        'type',
        'custom_name',
        'order',
        'start_date',
        'start_time',
        'end_date',
        'duration_days',
        'manual_end_date',
        'status',
        'reminder_enabled',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'start_time' => 'datetime',
            'end_date' => 'date',
            'duration_days' => 'integer',
            'order' => 'integer',
            'reminder_enabled' => 'boolean',
            'manual_end_date' => 'boolean',
        ];
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function treatmentCycle()
    {
        return $this->belongsTo(TreatmentCycle::class);
    }

    public function medicationSchedules()
    {
        return $this->hasMany(MedicationSchedule::class);
    }
}
