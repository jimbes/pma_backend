<?php

namespace Tests\Feature;

use App\Models\Practitioner;
use Tests\TestCase;

class PractitionerTest extends TestCase
{
    public function test_authenticated_user_can_list_practitioners(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        Practitioner::factory()->count(3)->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->getJson('/api/v1/practitioners');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'practitioners');
    }

    public function test_authenticated_user_can_create_practitioner(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $response = $this->actingAsUser($user)
            ->postJson('/api/v1/practitioners', [
                'name' => 'Dr. Jean Dupont',
                'specialty' => 'Gynécologue-fertilité',
                'phone' => '+33 1 23 45 67 89',
                'email' => 'dr@clinic.fr',
                'clinic_name' => 'Clinique ABC',
                'address' => '123 Rue de Paris',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['practitioner' => ['id', 'name', 'specialty']]);
        $this->assertDatabaseHas('practitioners', ['name' => 'Dr. Jean Dupont']);
    }

    public function test_authenticated_user_can_update_practitioner(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $practitioner = Practitioner::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/practitioners/{$practitioner->id}", [
                'specialty' => 'Cardiologue',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('practitioners', ['id' => $practitioner->id, 'specialty' => 'Cardiologue']);
    }

    public function test_authenticated_user_can_delete_practitioner(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $practitioner = Practitioner::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->deleteJson("/api/v1/practitioners/{$practitioner->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('practitioners', ['id' => $practitioner->id]);
    }

    public function test_user_cannot_access_other_couples_practitioners(): void
    {
        $couple1 = $this->createTestCouple();
        $couple2 = $this->createTestCouple();
        $user1 = $couple1->users->first();

        $practitioner = Practitioner::factory()->create(['couple_id' => $couple2->id]);

        $response = $this->actingAsUser($user1)
            ->getJson("/api/v1/practitioners/{$practitioner->id}");

        $response->assertStatus(403);
    }
}
