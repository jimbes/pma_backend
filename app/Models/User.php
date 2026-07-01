<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'couple_id',
        'name',
        'email',
        'password',
        'google_id',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function invitationsSent()
    {
        return $this->hasMany(CoupleInvitation::class, 'inviter_id');
    }

    public function notificationsSent()
    {
        return $this->hasMany(Notification::class);
    }

    public function partner()
    {
        return $this->couple->users()->where('id', '!=', $this->id)->first();
    }
}
