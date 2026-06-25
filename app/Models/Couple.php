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
