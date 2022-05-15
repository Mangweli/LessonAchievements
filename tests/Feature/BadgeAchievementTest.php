<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Interfaces\BadgeRepositoryInterface;
use App\Listeners\UnlockBadgeAchievement;
use App\Models\BadgeUserAchievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BadgeAchievementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private BadgeRepositoryInterface $badgeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->badgeRepository = $this->app->make(BadgeRepositoryInterface::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_unlock_badge_achievement_is_listening_on_comment_written() {
        Event::fake();

        Event::assertListening(
            CommentWritten::class,
            UnlockBadgeAchievement::class
        );
    }

    public function test_unlock_badge_achievement_is_listening_on_lesson_watch() {
        Event::fake();

        Event::assertListening(
            LessonWatched::class,
            UnlockBadgeAchievement::class
        );
    }

    public function test_user_gains_correct_qualified_badge_achievement()
    {
        $badgeNumbers = [0, 4, 8, 10];

        $this->assertEquals('Beginner', $this->badgeRepository->getBadgeAchievement($badgeNumbers[0])->name);
        $this->assertEquals('Intermediate', $this->badgeRepository->getBadgeAchievement($badgeNumbers[1])->name);
        $this->assertEquals('Advanced', $this->badgeRepository->getBadgeAchievement($badgeNumbers[2])->name);
        $this->assertEquals('Master', $this->badgeRepository->getBadgeAchievement($badgeNumbers[3])->name);
    }

    public function test_user_does_not_gain_badge_achievement_at_the_wrong_achievement_number()
    {
        $badgeNumbers = [3, 7, 9, 15];

        $this->assertEquals('Beginner', $this->badgeRepository->getBadgeAchievement($badgeNumbers[0])->name);
        $this->assertEquals('Intermediate', $this->badgeRepository->getBadgeAchievement($badgeNumbers[1])->name);
        $this->assertEquals('Advanced', $this->badgeRepository->getBadgeAchievement($badgeNumbers[2])->name);
        $this->assertEquals('Master', $this->badgeRepository->getBadgeAchievement($badgeNumbers[3])->name);
    }

    public function test_user_has_gained_a_specific_badge_achievement()
    {
        $user           = User::factory()->create();
        $achievement_id = $this->badgeRepository->getBadgeAchievement(0)->id;

        BadgeUserAchievement::create(['user_id' => $user->id, 'badge_id' =>  $achievement_id]);

        $this->assertEquals(1, $this->badgeRepository->userHasAchievement($user->id, $achievement_id));
    }

    public function test_user_can_gain_a_specific_badge_achievement()
    {
        $user           = User::factory()->create();
        $achievement_id = $this->badgeRepository->getBadgeAchievement(1)->id;

        $this->assertDatabaseMissing('badge_user_achievements', [
                                                                    'user_id' => $user->id,
                                                                    'badge_id' => $achievement_id
                                                                ]);


        $this->badgeRepository->setUserBadgeAchievement($user->id, $achievement_id);

        $this->assertDatabaseHas('badge_user_achievements', ['user_id' => $user->id, 'badge_id' => $achievement_id]);
    }

}
