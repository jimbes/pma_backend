<?php

namespace Database\Factories;

use App\Models\NotificationPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationPreferenceFactory extends Factory
{
    protected $model = NotificationPreference::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'type' => fake()->randomElement(['medication_reminder', 'appointment_reminder', 'journey_stage_reminder']),
            'channel' => fake()->randomElement(['push', 'email', 'both']),
            'enabled' => true,
            'reminder_minutes_before' => fake()->randomElement([15, 30, 60, 120, 1440]),
        ];
    }
}
