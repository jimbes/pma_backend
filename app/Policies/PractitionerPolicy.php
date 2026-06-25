<?php

namespace App\Policies;

use App\Models\Practitioner;
use App\Models\User;

class PractitionerPolicy
{
    public function view(User $user, Practitioner $practitioner): bool
    {
        return $user->couple_id === $practitioner->couple_id;
    }

    public function update(User $user, Practitioner $practitioner): bool
    {
        return $user->couple_id === $practitioner->couple_id;
    }

    public function delete(User $user, Practitioner $practitioner): bool
    {
        return $user->couple_id === $practitioner->couple_id;
    }
}
