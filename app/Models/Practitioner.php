<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;

    protected $fillable = [
        'couple_id',
        'name',
        'specialty',
        'phone',
        'email',
        'clinic_name',
        'address',
    ];

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }
}
