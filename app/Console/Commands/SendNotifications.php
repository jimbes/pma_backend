<?php

namespace App\Console\Commands;

use App\Models\MedicationSchedule;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Process pending notifications and schedule reminders';

    public function __construct(private NotificationService $notificationService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Processing medication reminders...');

        $schedules = MedicationSchedule::where('start_date', '<=', now()->format('Y-m-d'))
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->format('Y-m-d'));
            })
            ->with('couple.users')
            ->get();

        $reminderCount = 0;

        foreach ($schedules as $schedule) {
            if ($this->shouldRemindToday($schedule)) {
                foreach ($schedule->reminder_times as $time) {
                    $reminderTime = now()->format('Y-m-d') . ' ' . $time;

                    if (now()->format('Y-m-d H:i') >= $reminderTime) {
                        if ($schedule->notify_user_1) {
                            $this->notificationService->createMedicationReminder(
                                $schedule,
                                $schedule->couple->users->first()->id
                            );
                            $reminderCount++;
                        }

                        if ($schedule->notify_user_2 && $schedule->couple->users->count() > 1) {
                            $this->notificationService->createMedicationReminder(
                                $schedule,
                                $schedule->couple->users->last()->id
                            );
                            $reminderCount++;
                        }
                    }
                }
            }
        }

        $this->info("Created {$reminderCount} medication reminders");
        $this->notificationService->processPendingRetries();
        $this->info('Processed pending retries');

        return 0;
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
