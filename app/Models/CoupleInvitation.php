<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoupleInvitation extends Model
{
    use HasFactory;

    protected $fillable = ['couple_id', 'inviter_id', 'invitee_email', 'token', 'accepted', 'accepted_at', 'expires_at'];

    protected function casts(): array
    {
        return ['accepted' => 'boolean', 'accepted_at' => 'datetime', 'expires_at' => 'datetime'];
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
