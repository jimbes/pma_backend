<?php

namespace Tests\Feature;

use App\Models\Medication;
use Tests\TestCase;

class MedicationTest extends TestCase
{
    public function test_authenticated_user_can_list_medications(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        Medication::factory()->count(3)->create(['couple_id' => $couple->id, 'active' => true]);
        Medication::factory()->create(['couple_id' => $couple->id, 'active' => false]);

        $response = $this->actingAsUser($user)
            ->getJson('/api/v1/medications');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'medications');
    }

    public function test_authenticated_user_can_create_medication(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $response = $this->actingAsUser($user)
            ->postJson('/api/v1/medications', [
                'name' => 'Aspirin',
                'dosage' => '500',
                'unit' => 'mg',
                'description' => 'Pain reliever',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('medications', ['name' => 'Aspirin']);
    }

    public function test_authenticated_user_can_deactivate_medication(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $medication = Medication::factory()->create(['couple_id' => $couple->id, 'active' => true]);

        $response = $this->actingAsUser($user)
            ->deleteJson("/api/v1/medications/{$medication->id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('medications', ['id' => $medication->id, 'active' => false]);
    }

    public function test_user_cannot_access_other_couples_medications(): void
    {
        $couple1 = $this->createTestCouple();
        $couple2 = $this->createTestCouple();
        $user1 = $couple1->users->first();

        $medication = Medication::factory()->create(['couple_id' => $couple2->id]);

        $response = $this->actingAsUser($user1)
            ->getJson("/api/v1/medications/{$medication->id}");

        $response->assertStatus(403);
    }
}
