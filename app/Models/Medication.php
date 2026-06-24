<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = ['couple_id', 'name', 'dosage', 'unit', 'description', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function schedules()
    {
        return $this->hasMany(MedicationSchedule::class);
    }
}
