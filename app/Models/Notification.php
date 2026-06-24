<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['couple_id', 'user_id', 'type', 'related_entity_id', 'subject', 'message', 'channel', 'push_token', 'status', 'sent_at', 'failed_reason', 'retry_count', 'next_retry_at'];

    protected function casts(): array
    {
        return ['sent_at' => 'datetime', 'next_retry_at' => 'datetime'];
    }

    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function markSent()
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
    }

    public function markFailed($reason)
    {
        $this->status = 'failed';
        $this->failed_reason = $reason;
        $this->save();
    }

    public function scheduleRetry($minutes = 1)
    {
        $this->retry_count++;
        $this->next_retry_at = now()->addMinutes($minutes);
        $this->save();
    }
}
