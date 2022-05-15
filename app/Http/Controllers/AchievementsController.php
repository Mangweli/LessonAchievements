<?php

namespace App\Http\Controllers;

use App\Interfaces\BadgeRepositoryInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Interfaces\LessonRepositoryInterface;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Users\UserCommentAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AchievementsController extends Controller
{
    private CommentRepositoryInterface $commentRepository;
    private LessonRepositoryInterface $lessonRepository;
    private BadgeRepositoryInterface $badgeRepository;


    /**
     * __construct
     *
     * @param  mixed $commentRepository
     * @param  mixed $lessonRepository
     * @param  mixed $badgeRepository
     * @return void
     */
    public function __construct(CommentRepositoryInterface $commentRepository, LessonRepositoryInterface $lessonRepository,  BadgeRepositoryInterface $badgeRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->lessonRepository  = $lessonRepository;
        $this->badgeRepository   = $badgeRepository;
    }

    /**
     * index
     *
     * @param  mixed $user
     * @return void
     */

    public function index(User $user)
    {
        try {
            $commentAchievement     = $this->commentRepository->getUserNextCommentAchievement($user->id);
            $commentAchievement     = empty($commentAchievement) ? 'Unavailable'    : $commentAchievement->name;
            $lessonAchievement      = $this->lessonRepository->getUserNextLessonAchievement($user->id);
            $lessonAchievement      = empty($lessonAchievement) ? 'Unavailable'     : $lessonAchievement->name;
            $badgeAchievement       = $this->badgeRepository->getUserNextBadgeAchievement($user->id);
            $nextBadgeTarget        = $badgeAchievement->number_of_achievements;
            $badgeAchievement       = empty($badgeAchievement) ? 'Unavailable'      : $badgeAchievement->name;
            $totalachievements      = $this->commentRepository->getCommentAchievementReceived($user->id) + $this->lessonRepository->getLessonAchievementReceived($user->id);
            $currentBadge           = $this->badgeRepository->getUserCurrentBadge($user->id);
            $currentBadge           = empty($currentBadge) ? 'Unavailable'          : $currentBadge->name;
            $allCommentAchievements = $this->commentRepository->getAllUserCommentAchievements($user->id);
            $allCommentAchievements = empty($allCommentAchievements) ? 'Unavailable': $allCommentAchievements;
            $allLessonAchievements  = $this->lessonRepository->getAllUseLessonAchievements($user->id);
            $allLessonAchievements  = empty($allLessonAchievements) ? 'Unavailable' : $allLessonAchievements;

            return response()->json([
                'unlocked_achievements' => [
                                                'comment_achievement' => $allCommentAchievements,
                                                'lesson_achievement'  => $allLessonAchievements
                                            ],
                'next_available_achievements' => [
                                                    'comment_achievement' => $commentAchievement,
                                                    'lesson_achievement'  => $lessonAchievement
                                                ],
                'current_badge' => $currentBadge,
                'next_badge'    => $badgeAchievement,
                'remaing_to_unlock_next_badge' => $nextBadgeTarget - $totalachievements
            ]);
        }
        catch (\Throwable $th) {
            Log::error($th);

            return response()->json([
                'status'  => 'false',
                'message' => 'Unable to process your request',
                'error'   => $th
            ]);
        }
    }
}
