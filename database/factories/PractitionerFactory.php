<?php

namespace Database\Factories;

use App\Models\Practitioner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PractitionerFactory extends Factory
{
    protected $model = Practitioner::class;

    public function definition(): array
    {
        return [
            'couple_id' => \App\Models\Couple::factory(),
            'name' => fake()->name(),
            'specialty' => fake()->randomElement(['Gynécologue-fertilité', 'Infirmière', 'Biologiste', 'Cardiologue', 'Médecin généraliste']),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'clinic_name' => fake()->company(),
            'address' => fake()->address(),
        ];
    }
}
