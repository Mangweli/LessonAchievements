<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\Achievements\CommentWrittenAchievement;
use App\Models\Users\UserCommentAchievement;

class CommentRepository implements CommentRepositoryInterface
{
    // public function getUserComments($user_id, $pagination = 10)
    // {
    //     return Comment::where('user_id')->paginate($pagination);
    // }

    // public function getUserCommentCurrentAchievement($user_id)
    // {
    //     return UserCommentAchievement::where("user_id", $user_id)->last();
    // }

    // public function getUserNextCommentAchievement($user_id)
    // {
    //     return CommentWrittenAchievement::where("id", ">", UserCommentAchievement::where("user_id", $user_id)
    //                                                                              ->take(1)
    //                                                                              ->orderBy("created_at","desc")
    //                                                                              ->pluck("comment_written_achievement_id")[0]
    //                                     )
    //                                     ->first();
    // }

    public function getUserCommentNumber($user_id)
    {
        return Comment::where("user_id", $user_id)->count();
    }

    public function getCommentAchievement($commentNumber)
    {
       return CommentWrittenAchievement::where("number_of_comments", $commentNumber)->first();
    }

    public function getCommentAchievementReceived(int $user_id) {
        return UserCommentAchievement::where("user_id", $user_id)->count();
    }

    public function userHasAchievement($user_id, $achievement_id)
    {
        return UserCommentAchievement::where("user_id", $user_id)
                                     ->where("comment_written_achievement_id", $achievement_id)
                                     ->count();
    }

    public function setUserCommentAchievement($user_id, $comment_id, $achievement_id)
    {
       return UserCommentAchievement::create([
                                                "user_id"                        => $user_id,
                                                "comment_written_achievement_id" => $achievement_id,
                                                "comment_id"                     => $comment_id
                                            ]);
    }
}
