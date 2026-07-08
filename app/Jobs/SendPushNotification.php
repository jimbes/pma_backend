<?php

namespace App\Jobs;

use App\Models\DeviceToken;
use App\Models\Notification;
use App\Services\FirebaseMessagingService;
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

    public function handle(FirebaseMessagingService $fcm): void
    {
        $tokens = DeviceToken::where('user_id', $this->notification->user_id)
            ->where('active', true)
            ->get();

        if ($tokens->isEmpty()) {
            // Not a delivery failure worth retrying - there's simply no
            // device registered yet for this user.
            $this->notification->update([
                'status' => 'failed',
                'failed_reason' => 'No active device token for this user',
            ]);
            return;
        }

        $anySucceeded = false;

        foreach ($tokens as $deviceToken) {
            $result = $fcm->sendToToken(
                $deviceToken->token,
                $this->notification->subject,
                $this->notification->message,
                [
                    'type' => $this->notification->type,
                    'related_entity_id' => $this->notification->related_entity_id,
                    'notification_id' => $this->notification->id,
                ],
            );

            if ($result['success']) {
                $anySucceeded = true;
            } elseif ($result['invalid_token']) {
                // The device uninstalled the app or the token expired -
                // deactivate rather than keep retrying against a dead token.
                $deviceToken->update(['active' => false]);
            }
        }

        if ($anySucceeded) {
            $this->notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } else {
            $this->notification->update([
                'status' => 'failed',
                'failed_reason' => 'Delivery failed for all registered devices',
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        $this->notification->update([
            'status' => 'failed',
            'failed_reason' => $exception->getMessage(),
        ]);
    }
}
