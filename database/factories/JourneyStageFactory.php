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

    public function configure(): static
    {
        // JourneyStageController::index() now scopes to the couple's current
        // cycle, so a factory-created stage needs a real treatment_cycle_id
        // to be visible there - default it to the owning couple's current
        // cycle unless a test explicitly overrides it (e.g. to test
        // cycle-archiving behavior).
        return $this->afterMaking(function (JourneyStage $stage) {
            if (!$stage->treatment_cycle_id && $stage->couple_id) {
                $couple = \App\Models\Couple::find($stage->couple_id);
                if ($couple) {
                    $stage->treatment_cycle_id = $couple->currentTreatmentCycle()->id;
                }
            }
        });
    }
}
