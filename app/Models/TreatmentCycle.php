<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentCycle extends Model
{
    use HasFactory;

    protected $fillable = ['couple_id', 'cycle_number', 'start_date', 'end_date', 'status', 'notes'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date'];
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function journeyStages()
    {
        return $this->hasMany(JourneyStage::class);
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function markSucceeded()
    {
        $this->status = 'succeeded';
        $this->save();
    }

    public function markFailed()
    {
        $this->status = 'failed';
        $this->save();
    }
}
