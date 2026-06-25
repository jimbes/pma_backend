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
        'start_date',
        'start_time',
        'status',
        'reminder_enabled',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'reminder_enabled' => 'boolean',
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
}
