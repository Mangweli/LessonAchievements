<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BadgeAchievementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_gains_badges_with_achievement_achieved()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_gains_gains_correct_badge_with_correct_achievement()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
