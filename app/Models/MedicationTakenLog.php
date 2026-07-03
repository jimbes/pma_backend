<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationTakenLog extends Model
{
    use HasFactory;

    protected $table = 'medication_taken_log';
    protected $fillable = ['medication_schedule_id', 'date', 'time', 'taken', 'taken_at', 'user_logged_by', 'notes'];

    protected function casts(): array
    {
        // No cast for taken_at: Eloquent has no built-in "time" cast type
        // (only date/datetime/timestamp) - declaring one throws
        // InvalidCastException on every create(). Left as the raw "H:i:s"
        // string MySQL returns for a TIME column; the Flutter client parses
        // it manually alongside `date`.
        return ['date' => 'date', 'taken' => 'boolean'];
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
