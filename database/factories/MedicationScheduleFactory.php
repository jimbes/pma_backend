<?php

namespace Database\Factories;

use App\Models\MedicationSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicationScheduleFactory extends Factory
{
    protected $model = MedicationSchedule::class;

    public function definition(): array
    {
        return [
            'medication_id' => \App\Models\Medication::factory(),
            'couple_id' => \App\Models\Couple::factory(),
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
            'frequency' => 'daily',
            'days_of_week' => null,
            'reminder_times' => json_encode(['08:00', '20:00']),
            'reminder_offset_hours' => 1,
            'notify_user_1' => true,
            'notify_user_2' => true,
        ];
    }

    public function specificDays(): static
    {
        return $this->state(fn (array $attributes) => [
            'frequency' => 'specific_days',
            'days_of_week' => json_encode([1, 3, 5]),
        ]);
    }
}
