<?php

namespace Tests\Feature;

use App\Models\JourneyStage;
use App\Models\MedicationSchedule;
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

    public function test_authenticated_user_can_set_manual_start_date_on_journey_stage(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/journey-stages/{$stage->id}", [
                'start_date' => '2026-08-15',
                'manual_start_date' => true,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('journey_stages', [
            'id' => $stage->id,
            'manual_start_date' => true,
        ]);
        $this->assertSame('2026-08-15', $stage->fresh()->start_date->toDateString());
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

    public function test_skipping_a_stage_cancels_linked_medication_schedule_reminders(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id]);
        $schedule = MedicationSchedule::factory()->create([
            'couple_id' => $couple->id,
            'journey_stage_id' => $stage->id,
            'end_date' => now()->addDays(10)->toDateString(),
        ]);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/journey-stages/{$stage->id}", ['status' => 'skipped']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('journey_stages', ['id' => $stage->id, 'status' => 'skipped']);
        $this->assertSame(
            now()->subDay()->toDateString(),
            $schedule->fresh()->end_date->toDateString(),
        );
    }

    public function test_index_only_returns_current_cycle_stages(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        // Goes into cycle 1 (auto-created lazily by the factory).
        JourneyStage::factory()->count(2)->create(['couple_id' => $couple->id]);

        $this->actingAsUser($user)->postJson('/api/v1/treatment-cycles/start-new');

        // Goes into the new cycle 2.
        JourneyStage::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)->getJson('/api/v1/journey-stages');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'journey_stages');
    }

    public function test_update_with_matching_client_known_updated_at_succeeds(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();
        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/journey-stages/{$stage->id}", [
                'status' => 'in_progress',
                'client_known_updated_at' => $stage->updated_at->toISOString(),
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('journey_stages', ['id' => $stage->id, 'status' => 'in_progress']);
    }

    public function test_update_with_stale_client_known_updated_at_returns_conflict(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();
        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id, 'status' => 'upcoming']);

        // Simulates the partner having already changed the record after this
        // client last fetched it.
        $stage->update(['notes' => 'changed by partner']);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/journey-stages/{$stage->id}", [
                'status' => 'in_progress',
                'client_known_updated_at' => '2020-01-01T00:00:00.000000Z',
            ]);

        $response->assertStatus(409);
        $response->assertJsonPath('conflict', true);
        $this->assertDatabaseHas('journey_stages', ['id' => $stage->id, 'status' => 'upcoming']);
    }

    public function test_store_ignores_client_supplied_treatment_cycle_id(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $response = $this->actingAsUser($user)
            ->postJson('/api/v1/journey-stages', [
                'treatment_cycle_id' => 999999,
                'type' => 'stimulation',
                'start_date' => '2026-07-01',
                'status' => 'upcoming',
            ]);

        $response->assertStatus(201);
        $currentCycleId = $couple->currentTreatmentCycle()->id;
        $this->assertDatabaseHas('journey_stages', [
            'type' => 'stimulation',
            'treatment_cycle_id' => $currentCycleId,
        ]);
    }
}
