<?php

namespace Tests;

use App\Models\Couple;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    protected function createTestCouple(int $count = 2): Couple
    {
        $couple = Couple::factory()->create();

        for ($i = 0; $i < $count; $i++) {
            User::factory()->create(['couple_id' => $couple->id]);
        }

        return $couple->load('users');
    }

    protected function actingAsUser(?User $user = null): self
    {
        $user = $user ?? User::factory()->create();
        return $this->actingAs($user, 'sanctum');
    }
}
