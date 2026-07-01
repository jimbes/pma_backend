<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationSchedule extends Model
{
    use HasFactory;

    protected $table = 'medication_schedules';
    protected $fillable = ['medication_id', 'couple_id', 'journey_stage_id', 'start_date', 'end_date', 'frequency', 'days_of_week', 'reminder_times', 'reminder_offset_hours', 'notify_user_1', 'notify_user_2'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'days_of_week' => 'array',
            'reminder_times' => 'array',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ];
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function journeyStage()
    {
        return $this->belongsTo(JourneyStage::class);
    }

    public function takenLog()
    {
        return $this->hasMany(MedicationTakenLog::class);
    }

    public function isScheduledToday($date = null)
    {
        $date = $date ?: today();
        if ($date < $this->start_date || ($this->end_date && $date > $this->end_date)) {
            return false;
        }
        if ($this->frequency === 'daily') {
            return true;
        }
        if ($this->frequency === 'specific_days' && $this->days_of_week) {
            $dayOfWeek = (string) $date->dayOfWeek;
            return in_array($dayOfWeek, $this->days_of_week);
        }
        return false;
    }
}
