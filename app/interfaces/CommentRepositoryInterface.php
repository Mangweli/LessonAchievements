<?php

namespace App\Interfaces;

interface CommentRepositoryInterface {
    public function getUserNextCommentAchievement(int $user_id);
    public function getUserCommentNumber(int $user_id);
    public function getCommentAchievement(int $commentNumber);
    public function getCommentAchievementReceived(int $user_id);
    public function getAllUserCommentAchievements(int $user_id);
    public function userHasAchievement(int $user_id, int $achievement_id);
    public function setUserCommentAchievement(int $user_id, int $comment_id, int $achievement_id);

}
