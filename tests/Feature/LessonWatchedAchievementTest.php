<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Interfaces\LessonRepositoryInterface;
use App\Listeners\UnlockLessonWatchedAchievement;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;
use App\Models\Users\UserLessonWatchedAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedAchievementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private LessonRepositoryInterface $lessonRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lessonRepository = $this->app->make(LessonRepositoryInterface::class);
    }

    public function test_unlock_lesson_watched_achievement_is_listening_on_lesson_watched()
    {
        Event::fake();

        Event::assertListening(
            LessonWatched::class,
            UnlockLessonWatchedAchievement::class
        );
    }

    public function test_get_correct_user_lesson_number()
    {
        $user = User::factory()->create();

        $this->assertDatabaseMissing('lesson_user', ['user_id' => $user->user_id, 'lesson_id' => '1']);
        $lesson = Lesson::factory()->create();
        LessonUser::factory()->create(['user_id' => $user->id, 'lesson_id' => $lesson->id, 'watched' => true]);

        $this->assertEquals(1, $this->lessonRepository->getUserLessonNumber($user->id));
        $this->assertNotEquals(5, $this->lessonRepository->getUserLessonNumber($user->id));
    }

    public function test_get_user_qualified_lesson_achievement()
    {

        $lessonNumbers = [1, 5, 10, 25, 50];

        $this->assertEquals('First Lesson Watched', $this->lessonRepository->getLessonAchievement($lessonNumbers[0])->name);
        $this->assertEquals('5 Lessons Watched', $this->lessonRepository->getLessonAchievement($lessonNumbers[1])->name);
        $this->assertEquals('10 Lessons Watched', $this->lessonRepository->getLessonAchievement($lessonNumbers[2])->name);
        $this->assertEquals('25 Lessons Watched', $this->lessonRepository->getLessonAchievement($lessonNumbers[3])->name);
        $this->assertEquals('50 Lessons Watched', $this->lessonRepository->getLessonAchievement($lessonNumbers[4])->name);
    }

    public function test_user_does_not_gain_lesson_achievement_at_the_wrong_lesson_number()
    {
        $lessonNumbers = [2, 7, 15, 30, 45];

        $this->assertEquals(null, $this->lessonRepository->getLessonAchievement($lessonNumbers[0]));
        $this->assertEquals(null, $this->lessonRepository->getLessonAchievement($lessonNumbers[1]));
        $this->assertEquals(null, $this->lessonRepository->getLessonAchievement($lessonNumbers[2]));
        $this->assertEquals(null, $this->lessonRepository->getLessonAchievement($lessonNumbers[3]));
        $this->assertEquals(null, $this->lessonRepository->getLessonAchievement($lessonNumbers[4]));
    }

    public function test_user_has_gained_a_specific_lesson_achievement()
    {
        $user           = User::factory()->create();
        $achievement_id = $this->lessonRepository->getLessonAchievement(1)->id;

        $this->assertEquals(0, $this->lessonRepository->userHasAchievement($user->id, $achievement_id));

        $lesson = Lesson::factory()->create();
        LessonUser::factory()->create(['user_id' => $user->id, 'lesson_id' => $lesson->id, 'watched' => true]);

        UserLessonWatchedAchievement::factory()->create([
                                                    'user_id' => $user->id,
                                                    'lesson_achievement_id' => $achievement_id,
                                                    'lesson_id' => $lesson->id
                                                    ]);

        $this->assertEquals(1, $this->lessonRepository->userHasAchievement($user->id, $achievement_id));
    }

    public function test_user_can_gain_a_specific_lesson_achievement()
    {
        $user           = User::factory()->create();
        $achievement_id = $this->lessonRepository->getLessonAchievement(1)->id;

        $lesson = Lesson::factory()->create();
        LessonUser::factory()->create(['user_id' => $user->id, 'lesson_id' => $lesson->id, 'watched' => true]);

        $this->assertDatabaseMissing('user_lesson_watched_achievements', [
                                                                            'user_id' => $user->id,
                                                                            'lesson_id' => $lesson->id,
                                                                            'lesson_achievement_id' => $achievement_id
                                                                        ]);

        $this->lessonRepository->setUserLessonAchievement($user->id, $lesson->id, $achievement_id);

        $this->assertDatabaseHas('user_lesson_watched_achievements', ['user_id' => $user->id, 'lesson_id' => $lesson->id, 'lesson_achievement_id' => $achievement_id]);
    }

    public function test_user_does_not_get_achievement_on_rewatch()
    {
        $user           = User  ::factory()->create();
        $achievement_id = $this->lessonRepository->getLessonAchievement(1)->id;
        $lesson         = Lesson::factory()->create();

        $this->assertDatabaseMissing('lesson_user', [
                                                        'user_id' => $user->id,
                                                        'lesson_id' => $lesson->id,
                                    ]);

        LessonUser::factory()->count(2)->create(['user_id' => $user->id, 'lesson_id' => $lesson->id, 'watched' => true]);
        $this->assertEquals(2, LessonUser::where('user_id', $user->id)
                                            ->where('lesson_id', $lesson->id)
                                            ->where('watched', true)
                                            ->count());

        $this->assertEquals(1, $this->lessonRepository->getUserLessonNumber($user->id));
    }
}
