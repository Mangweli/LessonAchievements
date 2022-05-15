<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class UserAchievementTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_user_achievement_status()
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertStatus(200);
    }
}
