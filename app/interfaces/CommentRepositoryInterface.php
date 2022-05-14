<?php

namespace App\Interfaces;

interface CommentRepositoryInterface {
    public function getUserComments(int $user_id, int $pagination);
    public function getUserCommentCurrentAchievement(int $user_id);
    public function getUserNextCommentAchievement(int $user_id);
    public function getCommentNumber(int $user_id);
    public function setUserCommentAchievement(int $user_id);
}
