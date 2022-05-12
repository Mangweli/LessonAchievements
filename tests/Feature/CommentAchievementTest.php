<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Listeners\UnlockCommentAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentAchievementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_unlock_comment_achievement_is_listening_on_comment_written()
    {
        Event::fake();

        Event::assertListening(
            CommentWritten::class,
            UnlockCommentAchievement::class
        );

    }
    public function test_user_gains_achievement_with_new_comment()
    {


    }

    public function test_user_does_not_gain_comment_achievement_at_the_wrong_comment_number()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_gains_comment_achievement_at_the_right_comment_number()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
