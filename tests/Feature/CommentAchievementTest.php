<?php

namespace Tests\Feature;

use App\Interfaces\CommentRepositoryInterface;
use App\Events\CommentWritten;
use App\Listeners\UnlockCommentAchievement;
use App\Models\Comment;
use App\Models\User;
use App\Models\Users\UserCommentAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentAchievementTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private CommentRepositoryInterface $commentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentRepository = $this->app->make(CommentRepositoryInterface::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /**
     * Create the event listener.
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

    public function test_get_correct_user_comments_number()
    {
        $this->withoutExceptionHandling();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->assertDatabaseMissing('comments', ['user_id' => $user1->user_id]);
        $this->assertDatabaseMissing('comments', ['user_id' => $user2->user_id]);

        Comment::factory()->count(3)->create(['user_id' => $user1->id]);
        Comment::factory()->count(5)->create(['user_id' => $user2->id]);

        $this->assertEquals(3, $this->commentRepository->getUserCommentNumber($user1->id));
        $this->assertEquals(5, $this->commentRepository->getUserCommentNumber($user2->id));
    }

    public function test_get_user_qualified_comment_achievement()
    {
        $commentNumbers = [1, 3, 5, 10, 20];

        $this->assertEquals('First Comment Written', $this->commentRepository->getCommentAchievement($commentNumbers[0])->name);
        $this->assertEquals('3 Comments Written', $this->commentRepository->getCommentAchievement($commentNumbers[1])->name);
        $this->assertEquals('5 Comments Written', $this->commentRepository->getCommentAchievement($commentNumbers[2])->name);
        $this->assertEquals('10 Comment Written', $this->commentRepository->getCommentAchievement($commentNumbers[3])->name);
        $this->assertEquals('20 Comment Written', $this->commentRepository->getCommentAchievement($commentNumbers[4])->name);
    }

    public function test_user_does_not_gain_comment_achievement_at_the_wrong_comment_number()
    {
        $commentNumbers = [2, 6, 7, 9, 15];

        $this->assertEquals(null, $this->commentRepository->getCommentAchievement($commentNumbers[0]));
        $this->assertEquals(null, $this->commentRepository->getCommentAchievement($commentNumbers[1]));
        $this->assertEquals(null, $this->commentRepository->getCommentAchievement($commentNumbers[2]));
        $this->assertEquals(null, $this->commentRepository->getCommentAchievement($commentNumbers[3]));
        $this->assertEquals(null, $this->commentRepository->getCommentAchievement($commentNumbers[4]));
    }

    public function test_user_has_gained_a_specific_comment_achievement()
    {
        $user           = User::factory()->create();
        $achievement_id = $this->commentRepository->getCommentAchievement(1)->id;

        $this->assertEquals(0, $this->commentRepository->userHasAchievement($user->id, $achievement_id));

        $comment = Comment::factory()->create(['user_id' => $user->id]);

        UserCommentAchievement::factory()->create([
                                                    'user_id' => $user->id,
                                                    'comment_written_achievement_id' => $achievement_id,
                                                    'comment_id' => $comment->id
                                                    ]);

        $this->assertEquals(1, $this->commentRepository->userHasAchievement($user->id, $achievement_id));
    }

    public function test_user_can_gain_a_specific_achievement()
    {
        $user           = User   ::factory()->create();
        $achievement_id = $this->commentRepository->getCommentAchievement(1)->id;
        $comment        = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseMissing('user_comment_achievements', ['user_id' => $user->id, 'comment_id' => $comment->id]);

        $this->commentRepository->setUserCommentAchievement($user->id, $comment->id, $achievement_id);

        $this->assertDatabaseHas('user_comment_achievements', ['user_id' => $user->id, 'comment_id' => $comment->id]);
    }
}
