<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Interfaces\LessonRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UnlockLessonWatchedAchievement
{
    private LessonRepositoryInterface $lessonRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(LessonRepositoryInterface $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
       // try {
            $lesson = $event->lesson;
            $user   = $event->user;

            $this->lessonRepository->setLessonWatchStatus($user->id, $lesson->id, true);

            $LessonNumber = $this->lessonRepository->getUserLessonNumber($user->id);
            $qualifiedAchievement = $this->lessonRepository->getLessonAchievement($LessonNumber);
            $qualifiedAchievement = empty($qualifiedAchievement) ? [] : $qualifiedAchievement;

            if(!empty($qualifiedAchievement)) {
                $achievementReceivedCheck = $this->lessonRepository->userHasAchievement($user->id, $qualifiedAchievement->id);

                if($achievementReceivedCheck === 0) {
                    $setAchievement = $this->lessonRepository->setUserLessonAchievement($user->id, $lesson->id, $qualifiedAchievement->id);
                    if(!empty($setAchievement)) {
                        //achievement unlock event
                    }
                }
            }
        // }
        // catch (\Throwable $th) {
        //     Log::error($th);
        // }
    }
}
