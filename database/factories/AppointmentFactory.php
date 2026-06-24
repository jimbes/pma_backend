<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'couple_id' => \App\Models\Couple::factory(),
            'created_by' => \App\Models\User::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'appointment_date' => fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'appointment_time' => fake()->time('H:i'),
            'location' => fake()->address(),
            'doctor_name' => fake()->name(),
            'notify_user_1' => true,
            'notify_user_2' => true,
            'completed' => false,
        ];
    }
}
