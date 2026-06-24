<?php

namespace App\Policies;

use App\Models\MedicationSchedule;
use App\Models\User;

class MedicationSchedulePolicy
{
    public function view(User $user, MedicationSchedule $schedule): bool
    {
        return $user->couple_id === $schedule->couple_id;
    }

    public function update(User $user, MedicationSchedule $schedule): bool
    {
        return $user->couple_id === $schedule->couple_id;
    }

    public function delete(User $user, MedicationSchedule $schedule): bool
    {
        return $user->couple_id === $schedule->couple_id;
    }
}
