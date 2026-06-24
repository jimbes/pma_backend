<?php

namespace App\Jobs;

use App\Mail\NotificationEmail;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Notification $notification)
    {
    }

    public function handle(): void
    {
        Mail::to($this->notification->user->email)->send(
            new NotificationEmail($this->notification)
        );

        $this->notification->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->notification->update([
            'status' => 'failed',
            'failed_reason' => $exception->getMessage(),
        ]);
    }
}
