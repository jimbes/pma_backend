<?php

namespace App\Services;

use App\Jobs\SendEmailNotification;
use App\Jobs\SendPushNotification;
use App\Models\Appointment;
use App\Models\MedicationSchedule;
use App\Models\Notification;

class NotificationService
{
    private const RETRY_INTERVALS = [60, 300, 900, 3600, 21600, 86400]; // 1min, 5min, 15min, 1h, 6h, 24h

    public function createAppointmentNotification(Appointment $appointment, $userId): void
    {
        $notification = Notification::create([
            'couple_id' => $appointment->couple_id,
            'user_id' => $userId,
            'type' => 'appointment',
            'related_entity_id' => $appointment->id,
            'subject' => 'Rappel: Rendez-vous médicai',
            'message' => "Vous avez un rendez-vous avec {$appointment->doctor_name} le {$appointment->appointment_date}",
            'channel' => 'push',
            'status' => 'pending',
        ]);

        $this->queue($notification);
    }

    public function createMedicationReminder(MedicationSchedule $schedule, $userId): void
    {
        $notification = Notification::create([
            'couple_id' => $schedule->couple_id,
            'user_id' => $userId,
            'type' => 'medication_reminder',
            'related_entity_id' => $schedule->id,
            'subject' => 'Rappel: Prendre votre médicament',
            'message' => "N'oubliez pas de prendre {$schedule->medication->name}",
            'channel' => 'push',
            'status' => 'pending',
        ]);

        $this->queue($notification);
    }

    public function queue(Notification $notification): void
    {
        if ($notification->channel === 'push') {
            SendPushNotification::dispatch($notification);
        } elseif ($notification->channel === 'email') {
            SendEmailNotification::dispatch($notification);
        }
    }

    public function retry(Notification $notification): void
    {
        if ($notification->retry_count >= count(self::RETRY_INTERVALS)) {
            $notification->update(['status' => 'failed']);
            return;
        }

        $retryInterval = self::RETRY_INTERVALS[$notification->retry_count] ?? 86400;
        $notification->update([
            'retry_count' => $notification->retry_count + 1,
            'next_retry_at' => now()->addSeconds($retryInterval),
        ]);

        $this->queue($notification);
    }

    public function processPendingRetries(): void
    {
        $notifications = Notification::where('status', 'failed')
            ->whereNotNull('next_retry_at')
            ->where('next_retry_at', '<=', now())
            ->limit(100)
            ->get();

        foreach ($notifications as $notification) {
            $notification->update(['status' => 'pending']);
            $this->retry($notification);
        }
    }
}
