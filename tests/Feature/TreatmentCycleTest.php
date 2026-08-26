<?php

namespace Tests\Feature;

use App\Models\JourneyStage;
use Tests\TestCase;

class TreatmentCycleTest extends TestCase
{
    public function test_current_cycle_is_auto_created_for_a_fresh_couple(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $response = $this->actingAsUser($user)->getJson('/api/v1/treatment-cycles/current');

        $response->assertStatus(200);
        $response->assertJsonPath('treatment_cycle.cycle_number', 1);
        $this->assertDatabaseHas('treatment_cycles', ['couple_id' => $couple->id, 'cycle_number' => 1]);
    }

    public function test_start_new_increments_cycle_number_and_archives_previous_stages(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        JourneyStage::factory()->count(2)->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)->postJson('/api/v1/treatment-cycles/start-new');

        $response->assertStatus(201);
        $response->assertJsonPath('treatment_cycle.cycle_number', 2);

        $stages = $this->actingAsUser($user)->getJson('/api/v1/journey-stages');
        $stages->assertJsonCount(0, 'journey_stages');

        $history = $this->actingAsUser($user)->getJson('/api/v1/treatment-cycles');
        $history->assertJsonCount(2, 'treatment_cycles');
    }

    public function test_can_view_stages_of_an_archived_cycle(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id]);
        $oldCycleId = $stage->treatment_cycle_id;

        $this->actingAsUser($user)->postJson('/api/v1/treatment-cycles/start-new');

        $response = $this->actingAsUser($user)
            ->getJson("/api/v1/treatment-cycles/{$oldCycleId}/journey-stages");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'journey_stages');
    }

    public function test_user_cannot_view_another_couples_cycle_stages(): void
    {
        $couple1 = $this->createTestCouple();
        $couple2 = $this->createTestCouple();
        $user1 = $couple1->users->first();

        $stage = JourneyStage::factory()->create(['couple_id' => $couple2->id]);

        $response = $this->actingAsUser($user1)
            ->getJson("/api/v1/treatment-cycles/{$stage->treatment_cycle_id}/journey-stages");

        $response->assertStatus(403);
    }
}
