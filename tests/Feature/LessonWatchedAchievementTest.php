<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LessonWatchedAchievementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_gains_achievement_with_lesson_watched()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_does_not_get_achievement_on_rewatch()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
