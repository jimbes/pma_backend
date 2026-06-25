<?php

namespace Tests\Feature;

use App\Models\JourneyStage;
use Tests\TestCase;

class JourneyStageTest extends TestCase
{
    public function test_authenticated_user_can_list_journey_stages(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        JourneyStage::factory()->count(3)->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->getJson('/api/v1/journey-stages');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'journey_stages');
    }

    public function test_authenticated_user_can_create_journey_stage(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $response = $this->actingAsUser($user)
            ->postJson('/api/v1/journey-stages', [
                'type' => 'stimulation',
                'start_date' => '2026-07-01',
                'start_time' => '09:00',
                'status' => 'upcoming',
                'reminder_enabled' => true,
                'notes' => 'Starting stimulation phase',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['journey_stage' => ['id', 'type', 'start_date']]);
        $this->assertDatabaseHas('journey_stages', ['type' => 'stimulation']);
    }

    public function test_authenticated_user_can_update_journey_stage(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/journey-stages/{$stage->id}", [
                'status' => 'in_progress',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('journey_stages', ['id' => $stage->id, 'status' => 'in_progress']);
    }

    public function test_authenticated_user_can_delete_journey_stage(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->deleteJson("/api/v1/journey-stages/{$stage->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('journey_stages', ['id' => $stage->id]);
    }

    public function test_user_cannot_access_other_couples_journey_stages(): void
    {
        $couple1 = $this->createTestCouple();
        $couple2 = $this->createTestCouple();
        $user1 = $couple1->users->first();

        $stage = JourneyStage::factory()->create(['couple_id' => $couple2->id]);

        $response = $this->actingAsUser($user1)
            ->getJson("/api/v1/journey-stages/{$stage->id}");

        $response->assertStatus(403);
    }
}
