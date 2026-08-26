<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Couple extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function medicationSchedules()
    {
        return $this->hasMany(MedicationSchedule::class);
    }

    public function treatmentCycles()
    {
        return $this->hasMany(TreatmentCycle::class);
    }

    // Every couple has exactly one active cycle at a time - the one with the
    // highest cycle_number. Lazily created on first access so a brand-new
    // couple doesn't need a separate bootstrap step.
    public function currentTreatmentCycle()
    {
        return $this->treatmentCycles()->orderByDesc('cycle_number')->first()
            ?? $this->treatmentCycles()->create([
                'cycle_number' => 1,
                'start_date' => now()->toDateString(),
            ]);
    }

    public function invitations()
    {
        return $this->hasMany(CoupleInvitation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function journeyStages()
    {
        return $this->hasMany(JourneyStage::class);
    }

    public function practitioners()
    {
        return $this->hasMany(Practitioner::class);
    }
}
