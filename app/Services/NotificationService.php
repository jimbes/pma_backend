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
     * @param int $offsetMinutes How long before the appointment this fires
     * - included in the message so multiple reminders for the same
     * appointment (e.g. 24h before AND 12h before) read as distinct.
     */
    public function createAppointmentNotification(Appointment $appointment, $userId, $scheduledFor, int $offsetMinutes = 60, string $channel = 'push'): void
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
                'offset_minutes' => $offsetMinutes,
                'subject' => 'Rappel: Rendez-vous médical (' . self::formatOffsetLabel($offsetMinutes) . ')',
                'message' => "Vous avez un rendez-vous \"{$appointment->title}\""
                    . ($appointment->location ? " à {$appointment->location}" : '')
                    . " le {$appointment->appointment_date->format('d/m/Y')}"
                    . ($appointment->appointment_time ? " à {$appointment->appointment_time}" : ''),
                'channel' => $channel,
                'status' => 'pending',
            ]
        );

        if ($notification->wasRecentlyCreated) {
            $this->queue($notification);
        }
    }

    /**
     * @param \DateTimeInterface $scheduledFor The exact reminder instant
     * (dose time minus this offset, today) - see createAppointmentNotification.
     * @param int $offsetMinutes How long before the dose this fires -
     * included in the message so multiple reminders for the same dose
     * (e.g. 1h before AND 15min before) read as distinct.
     * @param string $doseTime "HH:mm" - the dose time itself, needed
     * (together with $offsetMinutes and $weekday) to compute the same
     * notification id the client's local scheduler used for this reminder,
     * so the push and the local alarm collapse into one notification
     * instead of showing a duplicate - see medicationReminderNotificationId().
     * @param int|null $weekday 0=Monday..6=Sunday - only for specific_days
     * schedules, where each weekday gets its own recurring local alarm.
     */
    public function createMedicationReminder(MedicationSchedule $schedule, $userId, $scheduledFor, int $offsetMinutes = 15, string $channel = 'push', string $doseTime = '', ?int $weekday = null): void
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
                'offset_minutes' => $offsetMinutes,
                'dose_time' => $doseTime,
                'weekday' => $weekday,
                'subject' => 'Rappel: Prendre votre médicament (' . self::formatOffsetLabel($offsetMinutes) . ')',
                'message' => "N'oubliez pas de prendre {$schedule->medication->name}",
                'channel' => $channel,
                'status' => 'pending',
            ]
        );

        if ($notification->wasRecentlyCreated) {
            $this->queue($notification);
        }
    }

    /**
     * Standard CRC-32 (IEEE 802.3, same as PHP's crc32() by definition) of
     * a canonical string - kept as an explicit helper (rather than just
     * calling crc32() inline) so both id builders below stay obviously in
     * sync with lib/utils/notification_id.dart on the Flutter side, which
     * implements the identical algorithm by hand (Dart has no built-in
     * crc32) to compute the same ids independently.
     */
    private static function crc32Id(string $key): int
    {
        return crc32($key) & 0x7FFFFFFF;
    }

    /**
     * Must match medicationReminderNotificationId() in
     * pma_flutter/lib/utils/notification_id.dart exactly.
     */
    public static function medicationReminderNotificationId(string $scheduleId, string $doseTime, int $offsetMinutes, ?int $weekday = null): int
    {
        $key = $weekday === null
            ? "med:{$scheduleId}:{$doseTime}:{$offsetMinutes}"
            : "med:{$scheduleId}:{$doseTime}:{$offsetMinutes}:{$weekday}";
        return self::crc32Id($key);
    }

    /**
     * Must match appointmentReminderNotificationId() in
     * pma_flutter/lib/utils/notification_id.dart exactly.
     */
    public static function appointmentReminderNotificationId(string $appointmentId, int $offsetMinutes): int
    {
        return self::crc32Id("apt:{$appointmentId}:{$offsetMinutes}");
    }

    private static function formatOffsetLabel(int $minutes): string
    {
        if ($minutes <= 0) {
            return 'maintenant';
        }
        if ($minutes % 60 === 0) {
            $hours = intdiv($minutes, 60);
            return $hours === 1 ? 'dans 1h' : "dans {$hours}h";
        }
        return "dans {$minutes}min";
    }

    public function queue(Notification $notification): void
    {
        // Shared hosting here has no guaranteed persistent queue worker
        // process, so a dispatch() to the database queue could sit unsent
        // indefinitely. Sending synchronously within the cron command
        // itself (dispatchSync) guarantees it actually runs.
        if (in_array($notification->channel, ['push', 'both'], true)) {
            SendPushNotification::dispatchSync($notification);
        }
        if (in_array($notification->channel, ['email', 'both'], true)) {
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
