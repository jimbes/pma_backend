<?php

namespace App\Policies;

use App\Models\NotificationPreference;
use App\Models\User;

class NotificationPreferencePolicy
{
    public function view(User $user, NotificationPreference $preference): bool
    {
        return $user->id === $preference->user_id;
    }

    public function update(User $user, NotificationPreference $preference): bool
    {
        return $user->id === $preference->user_id;
    }

    public function delete(User $user, NotificationPreference $preference): bool
    {
        return $user->id === $preference->user_id;
    }
}
