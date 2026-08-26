<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\JourneyStage;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    public function test_authenticated_user_can_list_couple_appointments(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        Appointment::factory()->count(3)->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->getJson('/api/v1/appointments');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'appointments');
    }

    public function test_authenticated_user_can_create_appointment(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $response = $this->actingAsUser($user)
            ->postJson('/api/v1/appointments', [
                'title' => 'Doctor Appointment',
                'appointment_date' => '2026-07-15',
                'appointment_time' => '10:00',
                'location' => 'Hospital',
                'doctor_name' => 'Dr. Smith',
                'notify_user_1' => true,
                'notify_user_2' => true,
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['appointment' => ['id', 'title', 'appointment_date']]);
        $this->assertDatabaseHas('appointments', ['title' => 'Doctor Appointment']);
    }

    public function test_authenticated_user_can_update_appointment(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $appointment = Appointment::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/appointments/{$appointment->id}", [
                'title' => 'Updated Appointment',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'title' => 'Updated Appointment']);
    }

    public function test_authenticated_user_can_mark_appointment_complete(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $appointment = Appointment::factory()->create(['couple_id' => $couple->id, 'completed' => false]);

        $response = $this->actingAsUser($user)
            ->patchJson("/api/v1/appointments/{$appointment->id}/complete");

        $response->assertStatus(200);
        $this->assertTrue($response->json('appointment.completed'));
    }

    public function test_authenticated_user_can_link_appointment_to_a_journey_stage(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();
        $stage = JourneyStage::factory()->create(['couple_id' => $couple->id]);

        $response = $this->actingAsUser($user)
            ->postJson('/api/v1/appointments', [
                'title' => 'Consultation post-stimulation',
                'appointment_date' => '2026-07-15',
                'journey_stage_id' => $stage->id,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('appointments', ['title' => 'Consultation post-stimulation', 'journey_stage_id' => $stage->id]);
    }

    public function test_user_cannot_access_other_couples_appointments(): void
    {
        $couple1 = $this->createTestCouple();
        $couple2 = $this->createTestCouple();
        $user1 = $couple1->users->first();

        $appointment = Appointment::factory()->create(['couple_id' => $couple2->id]);

        $response = $this->actingAsUser($user1)
            ->getJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(403);
    }
}
