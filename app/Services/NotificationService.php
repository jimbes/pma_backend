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

    /**
     * @param \DateTimeInterface $scheduledFor The exact reminder instant
     * this is for - paired with the unique index on notifications, this is
     * what stops the 15-minute cron from re-creating the same reminder
     * every run for the rest of the day.
     */
    public function createAppointmentNotification(Appointment $appointment, $userId, $scheduledFor): void
    {
        $notification = Notification::firstOrCreate(
            [
                'user_id' => $userId,
                'type' => 'appointment',
                'related_entity_id' => $appointment->id,
                'scheduled_for' => $scheduledFor,
            ],
            [
                'couple_id' => $appointment->couple_id,
                'subject' => 'Rappel: Rendez-vous médical',
                'message' => "Vous avez un rendez-vous \"{$appointment->title}\""
                    . ($appointment->location ? " à {$appointment->location}" : '')
                    . " le {$appointment->appointment_date->format('d/m/Y')}"
                    . ($appointment->appointment_time ? " à {$appointment->appointment_time}" : ''),
                'channel' => 'push',
                'status' => 'pending',
            ]
        );

        if ($notification->wasRecentlyCreated) {
            $this->queue($notification);
        }
    }

    /**
     * @param \DateTimeInterface $scheduledFor The exact reminder instant
     * (this schedule's dose time, today) - see createAppointmentNotification.
     */
    public function createMedicationReminder(MedicationSchedule $schedule, $userId, $scheduledFor): void
    {
        $notification = Notification::firstOrCreate(
            [
                'user_id' => $userId,
                'type' => 'medication_reminder',
                'related_entity_id' => $schedule->id,
                'scheduled_for' => $scheduledFor,
            ],
            [
                'couple_id' => $schedule->couple_id,
                'subject' => 'Rappel: Prendre votre médicament',
                'message' => "N'oubliez pas de prendre {$schedule->medication->name}",
                'channel' => 'push',
                'status' => 'pending',
            ]
        );

        if ($notification->wasRecentlyCreated) {
            $this->queue($notification);
        }
    }

    public function queue(Notification $notification): void
    {
        // Shared hosting here has no guaranteed persistent queue worker
        // process, so a dispatch() to the database queue could sit unsent
        // indefinitely. Sending synchronously within the cron command
        // itself (dispatchSync) guarantees it actually runs.
        if ($notification->channel === 'push') {
            SendPushNotification::dispatchSync($notification);
        } elseif ($notification->channel === 'email') {
            SendEmailNotification::dispatchSync($notification);
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
