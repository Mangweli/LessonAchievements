<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Interfaces\BadgeRepositoryInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Interfaces\LessonRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UnlockBadgeAchievement
{
    private CommentRepositoryInterface $commentRepository;
    private LessonRepositoryInterface $lessonRepository;
    private BadgeRepositoryInterface $badgeRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CommentRepositoryInterface $commentRepository, LessonRepositoryInterface $lessonRepository,  BadgeRepositoryInterface $badgeRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->lessonRepository  = $lessonRepository;
        $this->badgeRepository   = $badgeRepository;
    }


    public function handle($event)
    {
      //  try {
            $user_id              = (property_exists($event, 'comment')) ? $event->comment->user_id : $event->user->id;
            $achievementCount     = $this->commentRepository->getCommentAchievementReceived($user_id) + $this->lessonRepository->getLessonAchievementReceived($user_id);

            $qualifiedBadgeAchievement = $this->badgeRepository->getBadgeAchievement($achievementCount);
            $qualifiedBadgeAchievement = empty($qualifiedBadgeAchievement) ? [] : $qualifiedBadgeAchievement;

            if(!empty($qualifiedBadgeAchievement)) {
                $achievementReceivedCheck = $this->badgeRepository->userHasAchievement($user_id, $qualifiedBadgeAchievement->id);

                if($achievementReceivedCheck === 0) {
                    $this->badgeRepository->setUserBadgeAchievement($user_id, $qualifiedBadgeAchievement->id);
                }
            }
        // }
        // catch (\Throwable $th) {
        //     Log::error($th);
        // }

    }
}
