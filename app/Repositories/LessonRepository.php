<?php

use App\Interfaces\LessonRepositoryInterface;
use App\Models\Comment;
use App\Models\LessonUser;
use App\Models\LessonWatchedAchievement;
use App\Models\UserLessonWatchedAchievement;

class LessonRepository implements LessonRepositoryInterface
{
    public function getUserLessonNumber($user_id) {
        return LessonUser::where("user_id", $user_id)->distinct("lesson_id")->count();
    }

    public function getLessonAchievement($lessonNumber)
    {
       return LessonWatchedAchievement::where("number_of_lessons", $lessonNumber)->first();
    }

    public function getLessonAchievementReceived(int $user_id) {
        return UserLessonWatchedAchievement::where("user_id", $user_id)->count();
    }

    public function userHasAchievement($user_id, $achievement_id)
    {
        return UserLessonWatchedAchievement::where("user_id", $user_id)
                                            ->where("lesson_achievement_id", $achievement_id)
                                            ->count();
    }

    public function setLessonWatchStatus($user_id, $lesson_id, $watch_status)
    {
        return LessonUser::create(["user_id" => $user_id, "lesson_id" => $lesson_id, "watched" => $watch_status]);
    }

    public function setUserLessonAchievement($user_id, $lesson_id, $achievement_id)
    {
       return UserLessonWatchedAchievement::create([
                                                "user_id"               => $user_id,
                                                "lesson_achievement_id" => $achievement_id,
                                                "lesson_id"             => $lesson_id
                                            ]);
    }





}
