<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'couple_id' => $this->couple_id,
            'partner' => $this->when($this->relationLoaded('couple'), fn() => $this->couple?->users()->where('id', '!=', $this->id)->first()?->only('id', 'name', 'email')),
            // "user1" vs "user2" (as used by notify_user_1/notify_user_2 on
            // appointments/schedules) isn't a stored flag - it's whichever
            // couple member ->users()->first()/->last() resolves to
            // (see SendNotifications::sendMedicationReminders). The client
            // needs to know which one it is to know which notify_user_*
            // flag applies to itself when scheduling local reminders.
            'is_primary_user' => $this->when(
                $this->relationLoaded('couple'),
                fn() => $this->couple?->users()->first()?->id === $this->id,
            ),
            'created_at' => $this->created_at,
        ];
    }
}
