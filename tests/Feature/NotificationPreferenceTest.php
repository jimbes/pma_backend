<?php

namespace Tests\Feature;

use App\Models\NotificationPreference;
use Tests\TestCase;

class NotificationPreferenceTest extends TestCase
{
    public function test_authenticated_user_can_list_notification_preferences(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        NotificationPreference::create([
            'user_id' => $user->id,
            'type' => 'medication_reminder',
            'channel' => 'push',
            'enabled' => true,
        ]);

        NotificationPreference::create([
            'user_id' => $user->id,
            'type' => 'appointment_reminder',
            'channel' => 'email',
            'enabled' => true,
        ]);

        $response = $this->actingAsUser($user)
            ->getJson('/api/v1/notification-preferences');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'notification_preferences');
    }

    public function test_authenticated_user_can_create_notification_preference(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $response = $this->actingAsUser($user)
            ->postJson('/api/v1/notification-preferences', [
                'type' => 'medication_reminder',
                'channel' => 'both',
                'enabled' => true,
                'reminder_minutes_before' => 30,
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['notification_preference' => ['id', 'user_id', 'type', 'channel']]);
        $this->assertDatabaseHas('notification_preferences', [
            'user_id' => $user->id,
            'type' => 'medication_reminder',
        ]);
    }

    public function test_authenticated_user_can_update_notification_preference(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $preference = NotificationPreference::create([
            'user_id' => $user->id,
            'type' => 'medication_reminder',
            'channel' => 'push',
            'enabled' => true,
        ]);

        $response = $this->actingAsUser($user)
            ->putJson("/api/v1/notification-preferences/{$preference->id}", [
                'channel' => 'email',
                'enabled' => false,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('notification_preferences', [
            'id' => $preference->id,
            'channel' => 'email',
            'enabled' => false,
        ]);
    }

    public function test_authenticated_user_can_delete_notification_preference(): void
    {
        $couple = $this->createTestCouple();
        $user = $couple->users->first();

        $preference = NotificationPreference::create([
            'user_id' => $user->id,
            'type' => 'medication_reminder',
            'channel' => 'push',
            'enabled' => true,
        ]);

        $response = $this->actingAsUser($user)
            ->deleteJson("/api/v1/notification-preferences/{$preference->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('notification_preferences', ['id' => $preference->id]);
    }

    public function test_user_cannot_access_other_users_notification_preferences(): void
    {
        $couple1 = $this->createTestCouple();
        $couple2 = $this->createTestCouple();
        $user1 = $couple1->users->first();
        $user2 = $couple2->users->first();

        $preference = NotificationPreference::create([
            'user_id' => $user2->id,
            'type' => 'medication_reminder',
            'channel' => 'push',
            'enabled' => true,
        ]);

        $response = $this->actingAsUser($user1)
            ->getJson("/api/v1/notification-preferences/{$preference->id}");

        $response->assertStatus(403);
    }
}
