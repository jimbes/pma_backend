<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationTakenLog extends Model
{
    use HasFactory;

    protected $table = 'medication_taken_log';
    protected $fillable = ['medication_schedule_id', 'date', 'taken', 'taken_at', 'user_logged_by', 'notes'];

    protected function casts(): array
    {
        return ['date' => 'date', 'taken_at' => 'time:H:i:s', 'taken' => 'boolean'];
    }

    public function schedule()
    {
        return $this->belongsTo(MedicationSchedule::class, 'medication_schedule_id');
    }

    public function loggedBy()
    {
        return $this->belongsTo(User::class, 'user_logged_by');
    }
}
