<?php

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\CommentWrittenAchievement;
use App\Models\UserCommentAchievement;

class CommentRepository implements CommentRepositoryInterface
{
    public function getUserComments($user_id, $pagination = 10)
    {
        return Comment::where('user_id')
                      ->paginate($pagination);
    }

    public function getUserCommentCurrentAchievement($user_id)
    {
        return UserCommentAchievement::where("user_id", $user_id)
                                     ->last();
    }

    public function getUserNextCommentAchievement($user_id)
    {
        return CommentWrittenAchievement::where('id', '>', UserCommentAchievement::where("user_id", $user_id)
                                                                                    ->take(1)
                                                                                    ->orderBy("created_at","desc")
                                                                                    ->pluck('comment_written_achievement_id')[0]
                                        )
                                        ->first();
    }

    public function getCommentNumber($user_id)
    {

    }

    public function setUserCommentAchievement($user_id)

    {

    }
}
