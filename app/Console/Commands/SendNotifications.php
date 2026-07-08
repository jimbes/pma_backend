<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\MedicationSchedule;
use App\Models\NotificationPreference;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Process pending notifications and schedule reminders';

    /** @var array<int, array<string, NotificationPreference>> keyed by [userId][type] */
    private array $preferences = [];

    public function __construct(private NotificationService $notificationService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        foreach (NotificationPreference::all() as $pref) {
            $this->preferences[$pref->user_id][$pref->type] = $pref;
        }

        $this->info('Processing medication reminders...');
        $medicationCount = $this->sendMedicationReminders();
        $this->info("Created {$medicationCount} medication reminders");

        $this->info('Processing appointment reminders...');
        $appointmentCount = $this->sendAppointmentReminders();
        $this->info("Created {$appointmentCount} appointment reminders");

        $this->notificationService->processPendingRetries();
        $this->info('Processed pending retries');

        return 0;
    }

    /**
     * Null means "no preference row" - in that case reminders stay on by
     * default (see notify_user_1/2 defaulting true at creation time), so
     * only an explicit disabled row should suppress sending.
     */
    private function preferenceFor(int $userId, string $type): ?NotificationPreference
    {
        return $this->preferences[$userId][$type] ?? null;
    }

    private function sendMedicationReminders(): int
    {
        $schedules = MedicationSchedule::where('start_date', '<=', now()->format('Y-m-d'))
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->format('Y-m-d'));
            })
            ->with('couple.users')
            ->get();

        $count = 0;
        $today = now()->format('Y-m-d');

        foreach ($schedules as $schedule) {
            if (!$this->shouldRemindToday($schedule)) {
                continue;
            }

            // ?? (not ?:) - an intentionally-empty array (reminders turned
            // off for this schedule) must stay empty, not fall back to the
            // default; only a genuinely unset (null) value should.
            $offsets = $schedule->reminder_offsets ?? [15];

            foreach ($schedule->reminder_times as $time) {
                $doseAt = Carbon::parse("{$today} {$time}");

                foreach ($offsets as $offsetMinutes) {
                    $reminderAt = $doseAt->copy()->subMinutes($offsetMinutes);

                    // Fire once the reminder instant has passed, but not
                    // after the dose itself - a "reminder" arriving after
                    // the dose time isn't useful (e.g. cron was down).
                    if (now()->lt($reminderAt) || now()->gt($doseAt)) {
                        continue;
                    }

                    // One instant per (reminder time, offset) per day - the
                    // natural key the dedup unique index is built around.
                    $scheduledFor = $reminderAt->format('Y-m-d H:i:s');

                    if ($schedule->notify_user_1) {
                        $userId = $schedule->couple->users->first()->id;
                        $pref = $this->preferenceFor($userId, 'medication_reminder');

                        if (!$pref || $pref->enabled) {
                            $this->notificationService->createMedicationReminder(
                                $schedule,
                                $userId,
                                $scheduledFor,
                                $offsetMinutes,
                                $pref->channel ?? 'push'
                            );
                            $count++;
                        }
                    }

                    if ($schedule->notify_user_2 && $schedule->couple->users->count() > 1) {
                        $userId = $schedule->couple->users->last()->id;
                        $pref = $this->preferenceFor($userId, 'medication_reminder');

                        if (!$pref || $pref->enabled) {
                            $this->notificationService->createMedicationReminder(
                                $schedule,
                                $userId,
                                $scheduledFor,
                                $offsetMinutes,
                                $pref->channel ?? 'push'
                            );
                            $count++;
                        }
                    }
                }
            }
        }

        return $count;
    }

    private function sendAppointmentReminders(): int
    {
        $appointments = Appointment::where('completed', false)
            ->whereNotNull('appointment_time')
            ->with('couple.users')
            ->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            // appointment_time has no Eloquent cast (it's a raw MySQL TIME
            // column), so it comes back as a plain "H:i:s" string, not a
            // Carbon instance.
            $appointmentAt = $appointment->appointment_date->copy()
                ->setTimeFromTimeString($appointment->appointment_time);

            // See note above re: ?? vs ?: for the medication reminders.
            $offsets = $appointment->reminder_offsets ?? [60];

            foreach ($offsets as $offsetMinutes) {
                $reminderAt = $appointmentAt->copy()->subMinutes($offsetMinutes);

                // Fire once the reminder instant has passed, but not for
                // appointments already in the past (e.g. a couple opening
                // the app for the first time in weeks) - a stale "reminder"
                // the day after the appointment happened isn't useful.
                if (now()->lt($reminderAt) || now()->gt($appointmentAt)) {
                    continue;
                }

                if ($appointment->notify_user_1) {
                    $userId = $appointment->couple->users->first()->id;
                    $pref = $this->preferenceFor($userId, 'appointment_reminder');

                    if (!$pref || $pref->enabled) {
                        $this->notificationService->createAppointmentNotification(
                            $appointment,
                            $userId,
                            $reminderAt,
                            $offsetMinutes,
                            $pref->channel ?? 'push'
                        );
                        $count++;
                    }
                }

                if ($appointment->notify_user_2 && $appointment->couple->users->count() > 1) {
                    $userId = $appointment->couple->users->last()->id;
                    $pref = $this->preferenceFor($userId, 'appointment_reminder');

                    if (!$pref || $pref->enabled) {
                        $this->notificationService->createAppointmentNotification(
                            $appointment,
                            $userId,
                            $reminderAt,
                            $offsetMinutes,
                            $pref->channel ?? 'push'
                        );
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    private function shouldRemindToday(MedicationSchedule $schedule): bool
    {
        if ($schedule->frequency === 'daily') {
            return true;
        }

        if ($schedule->frequency === 'specific_days') {
            $todayDayOfWeek = now()->dayOfWeek;
            $allowedDays = json_decode($schedule->days_of_week, true) ?? [];
            return in_array($todayDayOfWeek, $allowedDays);
        }

        return false;
    }
}
