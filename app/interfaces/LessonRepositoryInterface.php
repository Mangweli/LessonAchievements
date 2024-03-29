<?php

namespace App\Interfaces;

interface LessonRepositoryInterface {
    public function getUserNextLessonAchievement(int $user_id);
    public function getUserLessonNumber(int $user_id);
    public function getLessonAchievement(int $lessonNumber);
    public function getLessonAchievementReceived(int $user_id);
    public function getAllUserLessonAchievements(int $user_id);
    public function userHasAchievement(int $user_id, int $achievement_id);
    public function setLessonWatchStatus(int $user_id, int $lesson_id, $watch_status);
    public function setUserLessonAchievement(int $user_id, int $lesson_id, int $achievement_id);
}
