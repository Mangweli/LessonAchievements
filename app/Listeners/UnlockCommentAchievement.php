<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Interfaces\CommentRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UnlockCommentAchievement
{
    private CommentRepositoryInterface $commentRepository;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        try {
            $user_id              = $event->Comment_user_id;
            $qualifiedAchievement = empty($this->commentRepository->getCommentAchievement($user_id)) ? [] : $this->commentRepository->getUserCommentNumber($user_id);

            if(!empty($qualifiedAchievement)) {
                $achievementReceivedCheck = $this->commentRepository->userHasAchievement($user_id, $qualifiedAchievement->id);

                if($achievementReceivedCheck === 0) {
                    $this->commentRepository->setUserCommentAchievement($user_id, $comment_id, $achievement_id);
                }
            }
        }
        catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
