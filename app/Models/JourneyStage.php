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
        'manual_start_date',
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
            'manual_start_date' => 'boolean',
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

    // The cycle's "day 1" should be the first stage's actual start date, not
    // whenever the cycle row happened to be created (e.g. a couple's first
    // cycle is lazily created at registration, which can be days before they
    // configure their first stage - without this, the app would show "J12"
    // on the first day of stage 1 instead of "J1").
    protected static function booted(): void
    {
        static::saved(function (JourneyStage $stage) {
            if ($stage->order === 0 && $stage->treatment_cycle_id) {
                $stage->treatmentCycle()->update(['start_date' => $stage->start_date]);
            }
        });
    }
}
