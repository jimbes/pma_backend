<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private Notification $notification)
    {
    }

    public function handle(): void
    {
        // TODO: Implement Firebase Cloud Messaging (FCM) integration
        // This is a placeholder for the actual push notification sending logic
        // In production, use the Firebase Admin SDK via Guzzle or a package

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
