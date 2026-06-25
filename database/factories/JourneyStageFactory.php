<?php

namespace Database\Factories;

use App\Models\JourneyStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class JourneyStageFactory extends Factory
{
    protected $model = JourneyStage::class;

    public function definition(): array
    {
        return [
            'couple_id' => \App\Models\Couple::factory(),
            'treatment_cycle_id' => null,
            'type' => fake()->randomElement(['stimulation', 'declenchement', 'ponction', 'transfert', 'attente_test']),
            'start_date' => fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'start_time' => fake()->time('H:i'),
            'status' => fake()->randomElement(['upcoming', 'in_progress', 'done']),
            'reminder_enabled' => true,
            'notes' => fake()->paragraph(),
        ];
    }
}
