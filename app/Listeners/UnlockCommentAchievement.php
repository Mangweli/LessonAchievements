<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Interfaces\CommentRepositoryInterface;
use App\Models\User;
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
            $user_id              = $event->comment->user_id;
            $commentNumber        = $this->commentRepository->getUserCommentNumber($user_id);
            $qualifiedAchievement = $this->commentRepository->getCommentAchievement($commentNumber);
            $qualifiedAchievement = empty($qualifiedAchievement) ? [] : $qualifiedAchievement;

            if(!empty($qualifiedAchievement)) {
                $achievementReceivedCheck = $this->commentRepository->userHasAchievement($user_id, $qualifiedAchievement->id);

                if($achievementReceivedCheck === 0) {
                    $setAchievement = $this->commentRepository->setUserCommentAchievement($user_id, $event->comment->id, $qualifiedAchievement->id);
                    if(!empty($setAchievement)) {
                        event(new AchievementUnlocked($qualifiedAchievement->name, User::where('id', $user_id)->first()));
                    }
                }
            }
        }
        catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
