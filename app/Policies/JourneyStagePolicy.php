<?php

namespace App\Policies;

use App\Models\JourneyStage;
use App\Models\User;

class JourneyStagePolicy
{
    public function view(User $user, JourneyStage $stage): bool
    {
        return $user->couple_id === $stage->couple_id;
    }

    public function update(User $user, JourneyStage $stage): bool
    {
        return $user->couple_id === $stage->couple_id;
    }

    public function delete(User $user, JourneyStage $stage): bool
    {
        return $user->couple_id === $stage->couple_id;
    }
}
