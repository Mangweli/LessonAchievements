<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LessonWatchedAchievementTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unlock_lesson_watched_achievement_is_listening_on_lesson_watched()
    {
        Event::fake();

        Event::assertListening(
            LessonWatched::class,
            UnlockLessonWatchedAchievement::class
        );

    }

    public function test_user_gains_achievement_with_lesson_watched()
    {
        Event::fake(); // Because you are creating an Order here

        $lesson = factory(Lesson::class)->create();
        $user = factory(User::class)->create();
        // $lessonWatched = facto
        $this->assertEmpty();

        $event = \Mockery::mock(LessonWatched::class);
        $event->lesson = $lesson;

        $listener = app()->make();
        $listener->handle($event);

        $this->assertNotEmpty();
    }

    public function test_user_does_not_get_achievement_on_rewatch()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
