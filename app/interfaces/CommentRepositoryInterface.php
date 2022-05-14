<?php

namespace App\Interfaces;

interface CommentRepositoryInterface {
    public function getUserComments(int $user_id, int $pagination);
    public function getUserCommentCurrentAchievement(int $user_id);
    public function getUserNextCommentAchievement(int $user_id);
    public function getUserCommentNumber(int $user_id);
    public function getCommentAchievement(int $user_id);
    public function userHasAchievement(int $user_id, int $achievement_id);
    public function setUserCommentAchievement(int $user_id, int $comment_id, int $achievement_id);

}
