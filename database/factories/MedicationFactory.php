<?php

namespace Database\Factories;

use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicationFactory extends Factory
{
    protected $model = Medication::class;

    public function definition(): array
    {
        return [
            'couple_id' => \App\Models\Couple::factory(),
            'name' => fake()->word(),
            'dosage' => fake()->numberBetween(1, 500),
            'unit' => fake()->randomElement(['mg', 'ml', 'g', 'µg']),
            'description' => fake()->optional()->paragraph(),
            'active' => true,
        ];
    }
}
