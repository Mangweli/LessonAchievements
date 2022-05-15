<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\Achievements\CommentWrittenAchievement;
use App\Models\Users\UserCommentAchievement;

class CommentRepository implements CommentRepositoryInterface
{
    public function getUserNextCommentAchievement($user_id)
    {
        $currentAchievement = UserCommentAchievement::where("user_id", $user_id)
                                                    ->orderBy("comment_written_achievement_id","desc")
                                                    ->first();

        if(empty($currentAchievement)) {
            return CommentWrittenAchievement::first();
        }

        return CommentWrittenAchievement::where("number_of_comments", ">", $currentAchievement->comment_written_achievement_id)
                                        ->first();
    }

    public function getUserCommentNumber($user_id)
    {
        return Comment::where("user_id", $user_id)->count();
    }

    public function getCommentAchievement($commentNumber)
    {
       return CommentWrittenAchievement::where("number_of_comments", $commentNumber)->first();
    }

    public function getCommentAchievementReceived($user_id)
    {
        return UserCommentAchievement::where("user_id", $user_id)->count();
    }

    public function getAllUserCommentAchievements($user_id)
    {
        return UserCommentAchievement::where("user_id", $user_id)
                                        ->join("comment_written_achievements","comment_written_achievements.id", "=", "user_comment_achievements.comment_written_achievement_id")
                                        ->pluck("name")
                                        ->toArray();
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
